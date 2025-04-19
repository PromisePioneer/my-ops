<?php

namespace App\Support\UserDeduction;

use App\Http\Requests\NinePastFiveTeenLateDeductionRequest;
use App\Models\NinePastFiveTeenLateDeduction;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

use function App\Helper\formatDate;

class NinePastFiveteenLateDeductionService
{

    private const int AMOUNT_OF_LATE = 10000;
    private static int $perPage = 10;

    public function data(): LengthAwarePaginator
    {
        $data = NinePastFiveTeenLateDeduction::with('kca', 'technician')->paginate(self::$perPage);
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
                'total_amount_of_late' => $item->total_amount_of_late,
                'amount' => number_format($item->total_deduction_amount),
            ];
        });

        $slaDeduction->setCollection($data);
        return $slaDeduction;
    }


    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $query = NinePastFiveTeenLateDeduction::with('kca', 'technician');


        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->whereHas('kca', function ($query) use ($search) {
                    $query->where('name', 'like', '%'.$search.'%');
                })->orWhereHas('technician', function ($query) use ($search) {
                    $query->where('name', 'like', '%'.$search.'%');
                })->orWhere('total_amount_of_late', 'like', '%'.$search.'%');
            });
        }


        $data = $query->paginate(self::$perPage);
        return self::formattedData($data);
    }


    public function store(NinePastFiveTeenLateDeductionRequest $request)
    {
        $data = $request->validated();
        $data['kca_id'] = $request->user()->id;
        $data['total_deduction_amount'] = $data['total_amount_of_late'] * self::AMOUNT_OF_LATE;
        return NinePastFiveTeenLateDeduction::create($data);
    }

    public function update(
        NinePastFiveTeenLateDeductionRequest $request,
        NinePastFiveTeenLateDeduction $ninePastFiveTeenLateDeduction
    ): bool {
        $data = $request->validated();
        $data['total_deduction_amount'] = $data['total_amount_of_late'] * self::AMOUNT_OF_LATE;
        return $ninePastFiveTeenLateDeduction->update($data);
    }

}
