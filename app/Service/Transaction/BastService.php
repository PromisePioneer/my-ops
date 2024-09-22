<?php

namespace App\Service\Transaction;

use App\Models\Bast;
use App\Models\BastProduct;
use App\Service\HelperService\HandleFileUploadService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Throwable;

use function App\Helper\formatDate;

class BastService
{
    private static int $perPage = 10;
    private HandleFileUploadService $handleFileUploadService;

    public function __construct()
    {
        $this->handleFileUploadService = new HandleFileUploadService();
    }


    public function data(Request $request): LengthAwarePaginator
    {
        $data = Bast::with('contact', 'user')
            ->where('branch_id', $request->user()->branch_id)
            ->paginate(self::$perPage);

        return self::formattedData($data);
    }


    public function formattedData(LengthAwarePaginator $bastData): LengthAwarePaginator
    {
        $data = $bastData->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'bast_number' => $item->bast_number,
                'contact' => $item->contact->full_name,
                'status' => $item->status,
                'created_at' => formatDate($item->created_at),
                'created_by' => $item->user->name,
            ];
        });

        $bastData->setCollection($data);
        return $bastData;
    }


    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');

        $query = Bast::with('contact', 'user')->where('branch_id', $request->user()->branch_id);


        if (!empty($search)) {
            $query->where('bast_number', 'like', '%'.$search.'%')
                ->where('branch_id', $request->user()->branch_id)
                ->orWhereHas('contact', function ($query) use ($search) {
                    $query->where('full_name', 'like', '%'.$search.'%');
                    $query->orWhere('company_name', 'like', '%'.$search.'%');
                })
                ->orWhere('date', 'like', '%'.$search.'%')
                ->orWhere('first_party_identity_name', 'like', '%'.$search.'%')
                ->orWhere('first_party_position', 'like', '%'.$search.'%')
                ->orWhere('objective', 'like', '%'.$search.'%')
                ->orWhere('file', 'like', '%'.$search.'%')
                ->orWhere('status', 'like', '%'.$search.'%');
        }


        $data = $query->paginate(self::$perPage);
        return self::formattedData($data);
    }


    /**
     * @throws Throwable
     */
    public function store($request): void
    {
        DB::transaction(function () use ($request) {
            $data = $request->validated();
            $data['branch_id'] = $request->user()->branch_id;
            $data['file'] = $this->handleFileUploadService->upload($request, 'documents/bast', 'file');
            $data['created_by'] = $request->user()->id;
            $bast = Bast::create($data);
            BastProduct::whereIn('bast_id', [$bast->id])->delete();
            $this->bastProductCreateOrUpdate($request, $bast);
        });
    }

    public function bastProductCreateOrUpdate($request, $bast): void
    {
        foreach ($request['data'] as $key => $value) {
            $value['bast_id'] = $bast->id;
            BastProduct::create($value);
        }
    }

    /**
     * @throws Throwable
     */
    public function update($request, $bast): void
    {
        DB::transaction(function () use ($request, $bast) {
            $data = $request->validated();
            $data['branch_id'] = $request->user()->branch_id;
            $data['file'] = $this->handleFileUploadService->upload($request, 'documents/bast', 'file', $bast->file);
            $data['created_by'] = $request->user()->id;
            $bast->update($data);
            BastProduct::whereIn('bast_id', [$bast->id])->delete();
            $this->bastProductCreateOrUpdate($request, $bast);
        });
    }
}
