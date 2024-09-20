<?php

namespace App\Service\UserDeduction;

use App\Http\Requests\Deduction\SlaDeductionRequest;
use App\Models\SLADeduction;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

use function App\Helper\formatDate;

class SLADeductionService
{
    private static int $perPage = 10;
    
    public function data(): LengthAwarePaginator
    {
        $data = SLADeduction::with('kca', 'technician')->paginate(self::$perPage);
        self::formattedData($data);
        return $data;
    }

    public function formattedData(LengthAwarePaginator $slaDeduction): LengthAwarePaginator
    {
        $data = $slaDeduction->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'kca' => $item->kca->name,
                'date' => formatDate($item->date),
                'technician' => $item->technician->name,
                'spk_amount' => $item->spk_amount,
                'total_deduction_amount' => number_format($item->total_deduction_amount),
            ];
        });

        $slaDeduction->setCollection($data);
        return $slaDeduction;
    }

    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $query = SLADeduction::with('kca', 'technician');

        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->whereHas('kca', function ($query) use ($search) {
                    $query->where('name', 'like', '%'.$search.'%');
                });
            });
        }

        $data = $query->paginate(self::$perPage);
        return self::formattedData($data);
    }


    public function store(SlaDeductionRequest $request): void
    {
        foreach ($request->technician_id as $technician) {
            SLADeduction::create([
                'date' => $request->date,
                'technician_id' => $technician,
                'kca_id' => $request->user()->id,
                'spk_amount' => $request->spk_amount,
                'total_deduction_amount' => $request->spk_amount * 10000,
            ]);
        }
    }

    public function update(SlaDeductionRequest $request, SLADeduction $SLADeduction): bool
    {
        $data = $request->validated();
        $data['kca_id'] = $request->user()->id;
        $data['total_deduction_amount'] = $request->spk_amount * 10000;
        return $SLADeduction->update($data);
    }


}