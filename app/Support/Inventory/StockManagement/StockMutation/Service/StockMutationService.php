<?php

namespace App\Support\Inventory\StockManagement\StockMutation\Service;

use AllowDynamicProperties;
use App\Http\Requests\StockMutationRequest;
use App\Models\Account;
use App\Models\Asset;
use App\Models\ItemCatalog;
use App\Models\ItemCollection;
use App\Models\Master\Common\Branch;
use App\Models\Stock;
use App\Models\StockMutation;
use App\Models\StockMutationItem;
use App\Support\HelperService\UsefulLifeService;
use App\Support\Inventory\StockManagement\StockMutation\Repository\StockMutationRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

            $senderBranchId = $request->user()->branch_id ?? Branch::where('name', 'Dumai')->first()->id;

            $stockMutation = StockMutation::create([
                'stock_mutation_number' => GenerateStockMutationNumber::apply($senderBranchId, Carbon::now()->format('Y-m-d')),
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
        DB::transaction(function () use ($stockMutation) {

            foreach ($stockMutation->stockMutationItems as $stock) {
                Stock::find($stock->stock_id)->decrement('qty', $stock->qty);
                ItemCatalog::where('code', $stock->code)->delete();
                Asset::where('code', $stock->code)->delete();
            }

            $this->generateSenderSignature($stockMutation);
        });
    }


    /**
     * @throws Throwable
     */
    public function cancelDelivery(StockMutation $stockMutation): void
    {
        DB::transaction(function () use ($stockMutation) {
            foreach ($stockMutation->stockMutationItems as $stock) {
                $currentStock = Stock::with('item', 'transaction', 'initialInventoryBalance')->find($stock->stock_id);
                $currentStock->increment('qty', $stock->qty);

                if (!empty($stock->code)) {
                    ItemCatalog::create([
                        'transaction_id' => $currentStock->transaction_id,
                        'stock_id' => $currentStock->id,
                        'draft_stock_id' => $currentStock->draft_stock_id,
                        'item_id' => $currentStock->item_id,
                        'code' => $stock->code,
                        'condition' => $currentStock->condition,
                        'created_by' => Auth::id(),
                        'initial_balance_inventory_id' => $currentStock->initial_balance_inventory_id,
                        'asset_id' => $currentStock->asset_id,
                    ]);

                    $item = ItemCollection::find($currentStock->item_id);
                    $account = Account::find($item->asset_account_id);
                    Asset::create([
                        'branch_id' => $currentStock->branch_id,
                        'code' => $stock->code,
                        'item_id' => $currentStock->item_id,
                        'date_received' => $currentStock->transaction?->date ?? $currentStock->initialInventoryBalance->date,
                        'unit' => 1,
                        'useful_life' => UsefulLifeService::getUsefulLife($account->code, $item->category->name, $item->building_type),
                        'price_per_unit' => $currentStock->transaction?->unit_price ?? $currentStock->initialInventoryBalance->unit_price,
                        'total_price' => $currentStock->transaction?->unit_price ?? $currentStock->initialInventoryBalance->unit_price,
                        'residu' => $currentStock->transaction?->unit_price ?? $currentStock->initialInventoryBalance->unit_price / UsefulLifeService::getUsefulLife($account->code, $item->category->name, $item->building_type),
                    ]);
                }
            }


            $stockMutation->update([
                'sender_signature' => null
            ]);
        });
    }


    private function generateSenderSignature(StockMutation $stockMutation): void
    {
        $image = QrCode::format('png')->size(200)
            ->generate($stockMutation->date);
        $signaturePath = 'documents/stock-mutation/sender-signature/' . $stockMutation->date . '.png';
        Storage::disk('public')->put($signaturePath, $image);

        $stockMutation->update([
            'sender_id' => Auth::id(),
            'sender_signature' => $signaturePath,
        ]);
    }

    public function receiveItem(StockMutation $stockMutation)
    {
    }
}
