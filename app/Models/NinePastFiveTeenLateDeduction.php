<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Pagination\LengthAwarePaginator;

use function App\Helper\formatDate;

class NinePastFiveTeenLateDeduction extends Model
{
    use HasFactory;

    protected $table = 'nine_past_fiveteen_late_deduction';
    protected $fillable = [
        'date',
        'kca_id',
        'technician_id',
        'total_amount_of_late',
        'total_deduction_amount',
    ];


    public function kca(): BelongsTo
    {
        return $this->belongsTo(User::class, 'kca_id');
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_id');
    }


    public function data(): LengthAwarePaginator
    {
        $data = self::with('kca', 'technician')->paginate(10);
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
}
