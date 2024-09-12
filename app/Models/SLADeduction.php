<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Pagination\LengthAwarePaginator;

use function App\Helper\formatDate;

class SLADeduction extends Model
{
    use HasFactory;

    protected $table = 'sla_deduction';
    protected $fillable = [
        'date',
        'kca_id',
        'technician_id',
        'spk_amount',
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
                'spk_amount' => $item->spk_amount,
                'total_deduction_amount' => number_format($item->total_deduction_amount),
            ];
        });

        $slaDeduction->setCollection($data);
        return $slaDeduction;
    }
}
