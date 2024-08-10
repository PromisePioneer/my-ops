<?php

namespace App\Models;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

/**
 * @property int $id
 * @property int $branch_id
 * @property string $description
 * @property int $debit_account_id
 * @property int $credit_account_id
 * @property string $amount
 * @property int $status_confirmation
 * @property string $file
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Branch $branch
 * @property-read \App\Models\SubAccount $creditAccount
 * @property-read \App\Models\SubAccount $debitAccount
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Expenditure newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Expenditure newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Expenditure query()
 * @method static \Illuminate\Database\Eloquent\Builder|Expenditure whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expenditure whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expenditure whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expenditure whereCreditAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expenditure whereDebitAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expenditure whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expenditure whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expenditure whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expenditure whereStatusConfirmation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expenditure whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Expenditure extends Model
{
    protected $table = 'expenditure';

    protected $fillable = [
        'branch_id',
        'description',
        'credit_account_id',
        'debit_account_id',
        'amount',
        'file',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function debitAccount(): BelongsTo
    {
        return $this->belongsTo(SubAccount::class, 'debit_account_id');
    }

    public function creditAccount(): BelongsTo
    {
        return $this->belongsTo(SubAccount::class, 'credit_account_id');
    }

    public function getDataWithPaginationBasedOnUserBranch(?int $branchId, int $perPage): LengthAwarePaginator
    {
        $expenditure = self::with('debitAccount', 'creditAccount')
            ->where('branch_id', $branchId)
            ->paginate($perPage);
        self::formattedData($expenditure);

        return $expenditure;
    }

    public function searchDataWithPagination(Request $request, int $perPage): LengthAwarePaginator
    {
        $search = $request->input('search');
        $expenditure = self::with('debitAccount', 'creditAccount')
            ->where('description', 'like', '%'.$search.'%')
            ->orWhereHas('debitAccount', function ($query) use ($search) {
                $query->where('code', 'like', '%'.$search.'%');
                $query->where('name', 'like', '%'.$search.'%');
            })->orWhere('amount', 'like', '%'.$search.'%')
            ->where('branch_id', $request->user()->branch_id)
            ->paginate($perPage);

        self::formattedData($expenditure);

        return $expenditure;
    }

    private static function formattedData($expenditure): void
    {
        $formattedData = $expenditure->getCollection()->map(function ($expenditure) {
            return [
                'id' => $expenditure->id,
                'description' => $expenditure->description,
                'debit_account' => "{$expenditure->debitAccount->code} - {$expenditure->debitAccount->name}",
                'credit_account' => "{$expenditure->creditAccount->code} - {$expenditure->creditAccount->name}",
                'amount' => number_format($expenditure->amount),
                'status_confirmation' => $expenditure->status_confirmation,
                'file' => $expenditure->file,
                'created_at' => $expenditure->created_at->format('d-m-Y'),
            ];
        });

        $expenditure->setCollection($formattedData);
    }

    public function filterDataBasedOnBranch(int $branchId, int $perPage): LengthAwarePaginator
    {
        $expenditure = self::with('debitAccount', 'creditAccount')->where('branch_id', $branchId)->paginate($perPage);
        self::formattedData($expenditure);

        return $expenditure;
    }

    public function getDebitAccountForExpenditure(Request $request)
    {
        $search = $request->input('search');

        $query = SubAccount::whereHas('account', static function ($query) use ($request) {
            $query->where('branch_id', $request->user()->branch_id ?? null);
            $query->where('code', '<', '400');
            $query->orWhere('code', '>=', '500');
        })->orderby('code', 'asc');

        return $this->extracted($search, $query);
    }

    public function getCreditAccountForExpenditure(Request $request)
    {
        $search = $request->search;

        $query = SubAccount::whereHas('account', static function ($query) use ($request) {
            $query->where('branch_id', $request->user()->branch_id);
        })->orderby('name', 'asc')
            ->whereBetween('code', ['111-01', '111-04'])
            ->select('id', 'name', 'code')
            ->limit(5);

        return $this->extracted($search, $query);
    }

    public function extracted(mixed $search, $query): mixed
    {
        if ($search !== '') {
            $query->where('name', 'like', '%'.$search.'%');
            $query->where('code', 'like', '%'.$search.'%');
        }

        $debitAccount = $query->get();

        return $debitAccount->map(function ($c) {
            $nameAndCode = $c->code.'-'.$c->name;

            return [
                'id' => $c->id,
                'text' => $nameAndCode,
            ];
        })->toArray();
    }
}
