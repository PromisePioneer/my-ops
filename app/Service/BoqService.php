<?php

namespace App\Service;

use App\Http\Requests\BoqRequest;
use App\Models\Boq;
use App\Models\BoqCommodity;
use App\Models\BoqTimelineProject;
use App\Models\Branch;
use App\Service\HelperService\HandleFileUploadService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Throwable;

use function App\Helper\formatDate;

class BoqService
{
    private static int $perPage = 10;
    private HandleFileUploadService $handleFileUploadService;

    public function __construct()
    {
        $this->handleFileUploadService = new HandleFileUploadService();
    }

    private static function generateBoqNumber($request): string
    {
        $boq = Boq::latest()->first();
        $branchCode = Branch::where('id', $request->user()->branch_id)->first()?->code ?? '100';
        $boqYear = Carbon::parse($request->date)->format('Y');

        if ($boq) {
            $convertBoqNumberToArray = explode('/', $boq->boq_number);
            $startingNumber = $convertBoqNumberToArray[0];
            $startValue = str_pad((int)$startingNumber + 1, 3, '0', STR_PAD_LEFT);

            return $startValue.'/'.$branchCode.'/BoQ/'.$boqYear;
        }

        $startingNumber = '000';
        $startValue = str_pad((int)$startingNumber + 1, 3, '0', STR_PAD_LEFT);


        return $startValue.'/'.$branchCode.'/BoQ/'.$boqYear;
    }


    private static function boqDataQuery(Request $request)
    {
        return Boq::with('branch', 'submitterName')
            ->when($request->user()->can('Lihat Data BoQ Sesuai Cabang Masing2'), function ($query) use ($request) {
                $query->where('branch_id', $request->user()->branch_id);
            })->when($request->user()->can('Lihat Pengajuan BoQ Pribadi'), function ($query) use ($request) {
                $query->where('submitter_id', $request->user()->id);
            });
    }


    public function data(Request $request): LengthAwarePaginator
    {
        $boq = self::boqDataQuery($request)->paginate(self::$perPage);
        return self::formattedData($boq);
    }


    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $query = self::boqDataQuery($request);

        if (!empty($search)) {
            $query->orWhere('title', 'like', '%'.$search.'%');
        }

        $data = $query->paginate(self::$perPage);
        return self::formattedData($data);
    }

    public function formattedData(LengthAwarePaginator $boq): LengthAwarePaginator
    {
        $data = $boq->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'boq_number' => $item->boq_number,
                'branch' => $item->branch?->name,
                'title' => $item->title,
                'date' => formatDate($item->date),
                'status' => $item->operational_manager_approval,
                'submitter' => $item->submitterName?->name,
                'submitter_id' => $item->submitter_id,
                'approved_by' => $item->approvedBy?->name,
                'known_by' => $item->knownBy?->name,
            ];
        });

        $boq->setCollection($data);
        return $boq;
    }

    /**
     * @throws Throwable
     */
    public function store(BoqRequest $request): void
    {
        DB::transaction(function () use ($request) {
            $data = $request->validated();
            $data['boq_number'] = self::generateBoqNumber($request);
            $data['branch_id'] = $request->user()->branch_id;
            $data['attachment'] = $this->handleFileUploadService->upload(
                $request,
                'documents/boq/attachment',
                'attachment'
            );
            $data['submitter_id'] = $request->user()->id;
            $boq = Boq::create($data);
            $this->boqRequestStoreOrUpdate($request, $boq);
            $this->projectTimelineStoreOrUpdate($request, $boq);
        });
    }


    /**
     * @throws Throwable
     */
    public function update(BoqRequest $request, Boq $boq): void
    {
        DB::transaction(function () use ($request, $boq) {
            $data = $request->validated();
            $data['boq_number'] = self::generateBoqNumber($request);
            $data['branch_id'] = $request->user()->branch_id;
            $data['attachment'] = $this->handleFileUploadService->upload(
                $request,
                'documents/boq/attachment',
                'attachment',
                $boq->attachment
            );
            $data['submitter'] = $request->user()->id;
            $boq->update($data);
            BoqCommodity::whereIn('boq_id', [$boq->id])->delete();
            BoqTimelineProject::whereIn('boq_id', [$boq->id])->delete();
            $this->boqRequestStoreOrUpdate($request, $boq);
            $this->projectTimelineStoreOrUpdate($request, $boq);
        });
    }


    public function boqRequestStoreOrUpdate(BoqRequest $request, Boq $boq): void
    {
        foreach ($request['data'] as $key => $value) {
            $value['boq_id'] = $boq->id;
            $value['total_price'] = $value['qty'] * $value['unit_price'];
            BoqCommodity::create($value);
        }
    }


    public function projectTimelineStoreOrUpdate(BoqRequest $request, Boq $boq): void
    {
        foreach ($request['projectTimeline'] as $key => $value) {
            $value['boq_id'] = $boq->id;
            BoqTimelineProject::create($value);
        }
    }
}