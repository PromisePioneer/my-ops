<?php

namespace App\Models;

use Eloquent;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $branch_id
 * @property string $description
 * @property int $debit_account_id
 * @property int $credit_account_id
 * @property string $amount
 * @property int $status_confirmation
 * @property string $file
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static Builder|Expenditure newModelQuery()
 * @method static Builder|Expenditure newQuery()
 * @method static Builder|Expenditure query()
 * @method static Builder|Expenditure whereAmount($value)
 * @method static Builder|Expenditure whereBranchId($value)
 * @method static Builder|Expenditure whereCreatedAt($value)
 * @method static Builder|Expenditure whereCreditAccountId($value)
 * @method static Builder|Expenditure whereDebitAccountId($value)
 * @method static Builder|Expenditure whereDescription($value)
 * @method static Builder|Expenditure whereFile($value)
 * @method static Builder|Expenditure whereId($value)
 * @method static Builder|Expenditure whereStatusConfirmation($value)
 * @method static Builder|Expenditure whereUpdatedAt($value)
 *
 * @mixin Eloquent
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
        return $this->belongsTo(Account::class, 'debit_account_id');
    }

    public function creditAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'credit_account_id');
    }

    public function getDataWithPaginationBasedOnUserBranch(?int $branchId, int $perPage): LengthAwarePaginator
    {
        $expenditure = self::with('debitAccount', 'creditAccount')
            ->where('branch_id', $branchId)
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

    public function filterDataBasedOnBranch(int $branchId, int $perPage): LengthAwarePaginator
    {
        $expenditure = self::with('debitAccount', 'creditAccount')->where('branch_id', $branchId)->paginate($perPage);
        self::formattedData($expenditure);

        return $expenditure;
    }

    public function getDebitAccountForExpenditure(Request $request)
    {
        $search = $request->input('search');


        $query = Account::whereIn(
            'code',
            [
                '111-01',
                '111-02',
                '111-03',
                '111-04',
                '111-05',
                '112-01',
                '112-02',
                '113-01',
                '113-02',
                '114-01',
                '114-02',
                '115-01',
                '115-02',
                '115-03',
                '121',
                '122',
                '123',
                '124',
                '125',
                '126',
                '130',
                '211',
                '212',
                '213-01',
                '213-02',
                '213-03',
                '213-04',
                '213-05',
                '214',
                '215-01',
                '215-02',
                '215-03',
                '216',
                '221',
                '222',
                '223',
                '300',
                '311',
                '312',
                '313',
                '320',
                '500-01',
                '500-02',
                '500-03',
                '501-01',
                '501-02',
                '501-03',
                '502-01',
                '502-02',
                '502-03',
                '503-01',
                '503-02',
                '503-03',
                '503-04',
                '504-01',
                '504-02',
                '505-01',
                '505-02',
                '505-03',
                '506-01',
                '506-02',
                '506-03',
                '507-01',
                '507-02',
                '507-03',
                '507-04',
                '507-05',
                '508-01',
                '508-02',
                '508-03',
                '508-04',
                '508-05',
                '510',
                '511',
                '511-01',
                '511-02',
                '511-03',
                '511-04',
                '512',
                '513',
                '514',
            ]
        )
            ->orderby('code', 'asc');


        return $this->extracted($search, $query);
    }

    public function extracted(mixed $search, $query): mixed
    {
        if ($search !== '') {
            $query->where('name', 'like', '%'.$search.'%');
            $query->where('code', 'like', '%'.$search.'%');
        }

        $debitAccount = $query->get();

        return $debitAccount->map(function ($query) {
            $nameAndCode = $query->code.'-'.$query->name;

            return [
                'id' => $query->id,
                'text' => $nameAndCode,
            ];
        })->toArray();
    }

    public function getCreditAccountForExpenditure(Request $request)
    {
        $search = $request->input('search');

        $query = Account::whereIn(
            'code',
            [
                '111-01',
                '111-02',
                '111-03',
                '111-04',
                '111-05',
                '112-01',
                '112-02',
                '113-01',
                '113-02',
                '114-01',
                '114-02',
                '115-01',
                '115-02',
                '115-03',
                '121',
                '122',
                '123',
                '124',
                '125',
                '126',
                '130',
                '211',
                '212',
                '213-01',
                '213-02',
                '213-03',
                '213-04',
                '213-05',
                '214',
                '215-01',
                '215-02',
                '215-03',
                '216',
                '221',
                '222',
                '223',
                '300',
                '311',
                '312',
                '313',
                '320',
                '401-01',
                '401-02',
                '402-01',
                '402-02',
                '403-01',
                '403-02',
                '403-03',
                '403-04',
            ]
        )->orderby('code', 'asc');

        return $this->extracted($search, $query);
    }
}
