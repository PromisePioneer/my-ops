<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;

class WorkTime extends Model
{
    use HasFactory;

    protected $table = 'work_time';

    protected $fillable = [
        'name',
        'clock_in',
        'clock_out',
        'time_to_checkin',
        'end_time_to_checkin',
        'time_to_checkout',
        'end_time_to_checkout',
    ];


    public function getData(Request $request): array
    {
        $search = $request->input('search');
        $query = self::orderby('name', 'asc');

        if ($search !== '') {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $workTime = $query->get(['name', 'id']);

        return $workTime->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->name,
            ];
        })->toArray();
    }

    public function getSelectedData(int $workTimeId): array
    {
        $workTime = self::where('id', $workTimeId)->first();

        return [
            'id' => $workTime->id,
            'name' => $workTime->name,
        ];
    }

    public function userWorktime(): HasMany
    {
        return $this->hasMany(UserWorkTime::class, 'work_time_id', 'id');
    }
}
