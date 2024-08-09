<?php

namespace App\Models;

use Eloquent;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * @property int $id
 * @property int|null $branch_id
 * @property string $code
 * @property string $name
 * @property float $debit_balance
 * @property float $credit_balance
 * @property float $balance
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, AccountTransaction> $accountTransaction
 * @property-read int|null $account_transaction_count
 * @property-read Branch|null $branch
 * @property-read Collection<int, SubAccount> $subAccount
 * @property-read int|null $sub_account_count
 *
 * @method static Builder|Account newModelQuery()
 * @method static Builder|Account newQuery()
 * @method static Builder|Account query()
 * @method static Builder|Account whereBalance($value)
 * @method static Builder|Account whereBranchId($value)
 * @method static Builder|Account whereCode($value)
 * @method static Builder|Account whereCreatedAt($value)
 * @method static Builder|Account whereCreditBalance($value)
 * @method static Builder|Account whereDebitBalance($value)
 * @method static Builder|Account whereId($value)
 * @method static Builder|Account whereName($value)
 * @method static Builder|Account whereUpdatedAt($value)
 *
 * @mixin Eloquent
 */
class Account extends Model
{
    protected $table = 'accounts';

    protected $fillable = [
        'branch_id',
        'name',
        'code',
        'debit_balance',
        'credit_balance',
        'balance',
    ];

    // relationship
    public function subAccount(): HasMany
    {
        return $this->hasMany(SubAccount::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function accountTransaction(): HasMany
    {
        return $this->hasMany(AccountTransaction::class, 'account_id');
    }

    // eloquent
    public function getAccountsBasedOnUserBranch(?int $branchId, int $perPage): LengthAwarePaginator
    {
        $accounts = self::with(['subAccount' => static function ($query) {
            $query->orderBy('code', 'ASC');
        }])->where('branch_id', $branchId)
            ->paginate($perPage);

        return self::formatAccounts($accounts);
    }

    private static function formatAccounts(LengthAwarePaginator $accounts): LengthAwarePaginator
    {
        $formattedAccounts = $accounts->getCollection()->map(static function ($account) {
            $formattedSubAccounts = $account->subAccount->map(static function ($subAccount) {
                return [
                    'sub_account_id' => $subAccount->id,
                    'sub_account_code' => $subAccount->code,
                    'sub_account_name' => $subAccount->name,
                    'sub_account_debit_balance' => number_format($subAccount->debit_balance, 2, ',', '.'),
                    'sub_account_credit_balance' => number_format($subAccount->credit_balance, 2, ',', '.'),
                    'sub_account_balance' => number_format($subAccount->balance, 2, ',', '.'),
                ];
            });

            return [
                'account_id' => $account->id,
                'account_code' => $account->code,
                'account_name' => $account->name,
                'account_debit_balance' => number_format($account->debit_balance, 2, ',', '.'),
                'account_credit_balance' => number_format($account->credit_balance, 2, ',', '.'),
                'account_balance' => number_format($account->balance, 2, ',', '.'),
                'sub_accounts' => $formattedSubAccounts,
            ];
        });
        $accounts->setCollection($formattedAccounts);

        return $accounts;
    }

    public function filteringAccountBasedOnBranch(int $branchId, int $perPage): LengthAwarePaginator
    {
        $accounts = self::with(['subAccount' => static function ($query) {
            $query->orderBy('code', 'ASC');
        }])->where('branch_id', $branchId)
            ->paginate($perPage);

        return self::formatAccounts($accounts);
    }

    public function searchAccounts(Request $request, int $perPage): LengthAwarePaginator
    {
        $searchTerm = $request->input('search');
        $query = self::with(['subAccount' => function ($query) {
            $query->orderBy('code', 'ASC');
        }])->where('branch_id', Auth::user()->branch_id);

        if (! empty($searchTerm)) {
            $query->where(function ($query) use ($searchTerm) {
                $query->where('code', 'like', '%'.$searchTerm.'%')
                    ->orWhere('name', 'like', '%'.$searchTerm.'%');
            });
        }
        $accounts = $query->paginate($perPage);
        $formattedAccounts = $accounts->getCollection()->map(function ($account) {
            $formattedSubAccounts = $account->subAccount->map(function ($subAccount) {
                return [
                    'sub_account_id' => $subAccount->id,
                    'sub_account_code' => $subAccount->code,
                    'sub_account_name' => $subAccount->name,
                    'sub_account_debit_balance' => number_format($subAccount->debit_balance, 2, ',', '.'),
                    'sub_account_credit_balance' => number_format($subAccount->credit_balance, 2, ',', '.'),
                    'sub_account_balance' => number_format($subAccount->balance, 2, ',', '.'),
                ];
            });

            return [
                'account_id' => $account->id,
                'account_code' => $account->code,
                'account_name' => $account->name,
                'account_debit_balance' => number_format($account->debit_balance, 2, ',', '.'),
                'account_credit_balance' => number_format($account->credit_balance, 2, ',', '.'),
                'account_balance' => number_format($account->balance, 2, ',', '.'),
                'sub_accounts' => $formattedSubAccounts,
            ];
        });

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $formattedAccounts,
            $accounts->total(),
            $accounts->currentPage(),
            ['path' => $request->url(), 'query' => $request->query()]
        );
    }

    public function getAccountDataAndSpecificBranchWithoutPagination(Request $request): array
    {
        $search = $request->input('search');

        $query = self::orderby('name', 'asc')
            ->select('id', 'name')
            ->where('branch_id', Auth::user()->branch_id)
            ->limit(5);

        if ($search !== '') {
            $query->where('name', 'like', '%'.$search.'%');
        }
        $account = $query->get();

        return $account->map(function ($c) {
            return [
                'id' => $c->id,
                'text' => $c->name,
            ];
        })->toArray();
    }

    public function getSelectedAccount(int $accountId): array
    {
        $account = self::where('id', $accountId)->first();

        return [
            'id' => $account->id,
            'name' => $account->name,
        ];
    }

    public function getAssetAccount(Request $request): array
    {
        $search = $request->input('search');
        $account = self::where('branch_id', $request->user()->branch_id)->whereBetween('code', ['121', '126']);

        if ($search !== '') {
            $account->where('branch_id', $request->user()->branch_id)
                ->whereBetween('code', ['121', '126'])
                ->where('name', 'like', '%'.$search.'%');
        }

        return $account->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->name,
            ];
        })->toArray();
    }
}
