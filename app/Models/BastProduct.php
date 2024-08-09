<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 
 *
 * @property int $id
 * @property int $bast_id
 * @property string $product_name
 * @property string $qty
 * @property string $serial_number
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Bast $bast
 * @method static \Illuminate\Database\Eloquent\Builder|BastProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BastProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BastProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|BastProduct whereBastId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BastProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BastProduct whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BastProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BastProduct whereProductName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BastProduct whereQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BastProduct whereSerialNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BastProduct whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BastProduct extends Model
{
    protected $table = 'bast_products';

    protected $fillable = [
        'bast_id',
        'product_name',
        'qty',
        'serial_number',
        'description',
    ];

    public function bast(): BelongsTo
    {
        return $this->belongsTo(Bast::class, 'bast_id');
    }

    //eloquent
    public function getData(int $bastId)
    {
        return self::with('bast')
            ->where('bast_id', $bastId)
            ->get();
    }
}
