<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

class JointClosureCode extends Model
{
    use HasFactory;

    protected $table = 'joint_closures_code';
    protected $fillable = [
        'code',
        'branch_id',
    ];


    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }


    public function getData(Request $request): array
    {
        $search = $request->input('search');
        $query = self::orderby('code')->where('branch_id', $request->user()->branch_id)->select('id', 'code');

        if ($search !== '') {
            $query->where('code', 'like', '%'.$search.'%');
        }

        $jointClosuresCode = $query->get();

        return $jointClosuresCode->map(function ($c) {
            return [
                'id' => $c->id,
                'text' => $c->code,
            ];
        })->toArray();
    }

    public function getSelectedData(?int $codeId): ?array
    {
        $branch = self::where('id', $codeId)->first();

        if ($codeId === null) {
            return null;
        }

        return [
            'id' => $branch->id,
            'code' => $branch->code,
        ];
    }
}
