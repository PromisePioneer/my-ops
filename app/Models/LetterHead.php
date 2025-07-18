<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string|null $header
 * @property string|null $footer
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|LetterHead newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LetterHead newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LetterHead query()
 * @method static \Illuminate\Database\Eloquent\Builder|LetterHead whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LetterHead whereFooter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LetterHead whereHeader($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LetterHead whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LetterHead whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class LetterHead extends Model
{
    protected $table = 'letter_head';

    protected $fillable = [
        'company_id',
        'header',
        'footer',
        'is_active'
    ];


    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
