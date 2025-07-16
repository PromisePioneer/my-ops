<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Laravel\Scout\Searchable;

class Company extends Model
{
    use HasFactory, Searchable;

    protected $table = 'companies';
    protected $fillable = [
        'code',
        'name',
    ];


    public function toSearchableArray(): array
    {
        return [
            'code' => $this->code,
            'name' => $this->name,
        ];
    }


    public function getData(Request $request): array
    {
        $search = $request->input('search');
        $query = self::orderby('name')->select('id', 'name', 'code');

        if ($search !== '') {
            $query->where('name', 'like', '%'.$search.'%');
        }

        $company = $query->get();

        return $company->map(function ($c) {
            return [
                'id' => $c->id,
                'text' => $c->name,
            ];
        })->toArray();
    }

    public function getSelectedData(int $companyId): ?array
    {
        $company = self::where('id', $companyId)->first();

        return [
            'id' => $company->id,
            'name' => $company->name,
        ];
    }
}
