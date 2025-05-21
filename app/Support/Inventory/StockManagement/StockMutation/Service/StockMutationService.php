<?php

namespace App\Support\Inventory\StockManagement\StockMutation\Service;

use AllowDynamicProperties;
use App\Http\Requests\StockMutationRequest;
use App\Models\ItemCatalog;
use App\Models\Stock;
use App\Models\StockMutation;
use App\Models\StockMutationItem;
use App\Support\Inventory\StockManagement\StockMutation\Repository\StockMutationRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Throwable;
use function App\Helper\formatDate;

#[AllowDynamicProperties] class StockMutationService
{
    private static int $perPage = 10;

    public function __construct()
    {
        $this->stockMutationRepository = new StockMutationRepository();
    }

    public function data(): LengthAwarePaginator
    {
        $stockMutations = $this->stockMutationRepository->getData()->paginate(self::$perPage);
        return self::formattedData($stockMutations);
    }

    public function search(Request $request)
    {
    }

    public function filter(Request $request)
    {
    }

    public function formattedData(LengthAwarePaginator $stockMutations): LengthAwarePaginator
    {
        $data = $stockMutations->getCollection()->map(function ($query) {

            return [
                'id' => $query->id,
                'date' => formatDate($query->date),
                'old_branch_name' => $query->oldBranch->name,
                'new_branch_name' => $query->newBranch->parent->name,
                'sender_name' => $query->sender->name,
                'receiver_name' => $query->receiver->name,
                'sender_signature' => $query->sender_signature,
            ];
        });


        $stockMutations->setCollection($data);
        return $stockMutations;
    }


    /**
     * @throws Throwable
     */
    public function store(StockMutationRequest $request): void
    {
        DB::transaction(function () use ($request) {
            $stockMutation = StockMutation::create([
                'date' => Carbon::now()->format('Y-m-d'),
                'old_branch_id' => $request->input('from_branch'),
                'new_branch_id' => $request->input('to_branch'),
                'sender_id' => $request->user()->id,
                'receiver_id' => $request->input('receiver_id'),
                'description' => $request->input('description')
            ]);

            $this->stockMutationItemStore($request, $stockMutation);
        });

    }


    public function stockMutationItemStore(StockMutationRequest $request, StockMutation $stockMutation): void
    {
        if ($request->has('itemWithCodeFields')) {
            foreach ($request->itemWithCodeFields as $value) {
                $itemCatalog = ItemCatalog::find($value);
                StockMutationItem::create([
                    'stock_mutation_id' => $stockMutation->id,
                    'stock_id' => $itemCatalog->stock_id,
                    'code' => $itemCatalog->code,
                    'qty' => 1,
                ]);
            }
        }

        if ($request['itemWithoutCodeFields']) {
            foreach ($request['itemWithoutCodeFields'] as $key => $value) {
                $stockWithoutCode = Stock::with('item', 'itemCatalog')
                    ->where('id', $value['stock_id'])
                    ->first();
                $value['stock_mutation_id'] = $stockMutation->id;
                $value['stock_id'] = $stockWithoutCode->id;
                StockMutationItem::create($value);
            }
        }
    }

    /**
     * @throws Throwable
     */
    public function sendItem(StockMutation $stockMutation): void
    {
        $hash = Hash::make($stockMutation->id);
        $image = QrCode::format('png')->size(200)
            ->generate($hash);

        $signaturePath = 'documents/stock-mutation/sender-signature/' . $hash . '.png';
        Storage::disk('public')->put($signaturePath, $image);

        $stockMutation->update([
            'sender_id' => Auth::id(),
            'sender_signature' => $signaturePath,
        ]);
    }
}
