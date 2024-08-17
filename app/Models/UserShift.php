<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserShift extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shift_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(ManageShift::class, 'shift_id');
    }


    public function getSelectedUserShift(int $shiftId): array
    {
        $shift = self::with('user')->where('shift_id', $shiftId)->get();

        return $shift->map(function ($shift) {
            return [
                'id' => $shift->user->id,
                'name' => $shift->user->name,
            ];
        })->toArray();
    }
}
