<?php

namespace App\Models;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * 
 *
 * @property int $id
 * @property string $date
 * @property int|null $account_id
 * @property int|null $sub_account_id
 * @property string $description
 * @property float $debit
 * @property float $credit
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Account|null $account
 * @property-read \App\Models\Branch|null $branch
 * @property-read \App\Models\SubAccount|null $subAccount
 * @method static \Illuminate\Database\Eloquent\Builder|AccountTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountTransaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountTransaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountTransaction whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountTransaction whereCredit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountTransaction whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountTransaction whereDebit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountTransaction whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountTransaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountTransaction whereSubAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountTransaction whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AccountTransaction extends Model
{
    use HasFactory;

    protected $table = 'account_transactions';

    protected $fillable = [
        'date',
        'account_id',
        'sub_account_id',
        'description',
        'debit',
        'credit',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id', 'id');
    }

    public function subAccount(): BelongsTo
    {
        return $this->belongsTo(SubAccount::class, 'sub_account_id', 'id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    //eloquent
    public function getAccountTransactionBasedOnUserBranch(): LengthAwarePaginator
    {
        $accountTransaction = self::with('account', 'subAccount')
            ->whereHas('account', static function ($query) {
                $query->where('branch_id', Auth::user()->branch_id);
            })->orWhereHas('subAccount.account', function ($query) {
                $query->where('branch_id', Auth::user()->branch_id);
            })->paginate(10);

        self::formattedAccounTransactionData($accountTransaction);

        return $accountTransaction;
    }

    public function searchAccountTransactionBasedOnUserBranch(Request $request, int $perPage): LengthAwarePaginator
    {
        $search = $request->search;
        $accountTransaction = self::with('account', 'subAccount')
            ->whereHas('account', static function ($query) {
                $query->where('branch_id', Auth::user()->branch_id);
            })->orWhereHas('subAccount.account', function ($query) {
                $query->where('branch_id', Auth::user()->branch_id);
            })->where('description', 'like', '%'.$search.'%')->paginate($perPage);

        self::formattedAccounTransactionData($accountTransaction);

        return $accountTransaction;
    }

    private static function formattedAccounTransactionData(LengthAwarePaginator $accountTransaction): LengthAwarePaginator
    {
        $formattedAccountTransaction = $accountTransaction->getCollection()->map(static function ($item) {
            return [
                'id' => $item->id,
                'date' => $item->date,
                'account' => $item->account->name ?? $item->subAccount->name,
                'description' => $item->description,
                'debit' => number_format($item->debit, 2, ',', '.'),
                'credit' => number_format($item->credit, 2, ',', '.'),
                'total' => number_format($item->debit - $item->credit, 2, ',', '.'),
            ];
        });

        $accountTransaction->setCollection($formattedAccountTransaction);

        return $accountTransaction;
    }

    public function getGeneralJournalPeriodBasedOnUserBranch(int $perPage): LengthAwarePaginator
    {
        return self::with('account', 'subAccount')->whereHas('account', static function ($query) {
            $query->where('branch_id', Auth::user()->branch_id);
        })->orWhereHas('subAccount.account', static function ($query) {
            $query->where('branch_id', Auth::user()->branch_id);
        })->selectRaw("CONCAT(MONTH(date), '-', YEAR(date)) as waktu")
            ->distinct()
            ->paginate($perPage);
    }

    public function getGeneralJournalDataBasedOnUserBranchAndPeriod(string $month, string $year): LengthAwarePaginator
    {
        return self::with('account', 'subAccount')->whereHas('account', static function ($query) {
            $query->where('branch_id', Auth::user()->branch_id);
        })->orWhereHas('subAccount.account', static function ($query) {
            $query->where('branch_id', Auth::user()->branch_id);
        })->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->orderBy('date')
            ->paginate($this->perPage);
    }

    public function getGeneralJournalDataDetails($month, $year)
    {
        return self::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderBy('date', 'asc')
            ->leftJoin('accounts', 'accounts.id', '=', 'account_transactions.account_id')
            ->leftJoin('sub_accounts', 'sub_accounts.id', '=', 'account_transactions.sub_account_id')
            ->select('account_transactions.*',
                'sub_accounts.code as sub_account_code', 'sub_accounts.name as sub_account_name',
                'accounts.code as account_code', 'accounts.name as account_name'
            )->get()
            ->groupBy('description')
            ->map(function (Collection $group) {
                return [
                    'tanggal' => $group->first()->created_at->format('d/m/Y'),
                    'description' => $group->first()->description,
                    'debit' => $group->where('debit', '>', 0)->map(function ($transaction) {
                        return [
                            'code' => $transaction->account_code ?? $transaction->sub_account_code,
                            'account_name' => $transaction->account_name ?? $transaction->sub_account_name,
                            'amount' => number_format($transaction->debit),
                        ];
                    }),
                    'credit' => $group->where('credit', '>', 0)->map(function ($transaction) {
                        return [
                            'code' => $transaction->account_code ?? $transaction->sub_account_code,
                            'account_name' => $transaction->account_name ?? $transaction->sub_account_name,
                            'amount' => number_format($transaction->credit),
                        ];
                    })->values(),
                ];
            })->values();
    }

    public function getCurrentPPNOnInvoice($branchId, $description): self
    {
        return self::whereHas('subAccount.account', static function ($query) use ($branchId) {
            $query->where('branch_id', $branchId);
        })->whereHas('subAccount', function ($query) {
            $query->where('code', '213-01');
        })->where('description', $description)->first();
    }
}
