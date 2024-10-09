<?php

namespace App\Models;

use Eloquent;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * @property int $id
 * @property string $date
 * @property int|null $account_id
 * @property int|null $sub_account_id
 * @property string $description
 * @property float $debit
 * @property float $credit
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Account|null $account
 * @property-read Branch|null $branch
 *
 * @method static Builder|AccountTransaction newModelQuery()
 * @method static Builder|AccountTransaction newQuery()
 * @method static Builder|AccountTransaction query()
 * @method static Builder|AccountTransaction whereAccountId($value)
 * @method static Builder|AccountTransaction whereCreatedAt($value)
 * @method static Builder|AccountTransaction whereCredit($value)
 * @method static Builder|AccountTransaction whereDate($value)
 * @method static Builder|AccountTransaction whereDebit($value)
 * @method static Builder|AccountTransaction whereDescription($value)
 * @method static Builder|AccountTransaction whereId($value)
 * @method static Builder|AccountTransaction whereUpdatedAt($value)
 *
 * @mixin Eloquent
 */
//#[ObservedBy([AccountTransactionObserver::class])]
class AccountTransaction extends Model
{
    use HasFactory;

    protected $table = 'account_transactions';
    protected $fillable = [
        'branch_id',
        'date',
        'account_id',
        'description',
        'transaction_type',
        'entries_type',
        'amount',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id', 'id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    //eloquent
    public function getAccountTransactionBasedOnUserBranch(): LengthAwarePaginator
    {
        $accountTransaction = self::with('account')
            ->whereHas('account', static function ($query) {
                $query->where('branch_id', Auth::user()->branch_id);
            })->paginate(10);

        self::formattedAccounTransactionData($accountTransaction);

        return $accountTransaction;
    }

    private static function formattedAccounTransactionData(LengthAwarePaginator $accountTransaction
    ): LengthAwarePaginator {
        $formattedAccountTransaction = $accountTransaction->getCollection()->map(static function ($item) {
            return [
                'id' => $item->id,
                'date' => $item->date,
                'account' => $item->account->name,
                'description' => $item->description,
                'debit' => number_format($item->debit, 2, ',', '.'),
                'credit' => number_format($item->credit, 2, ',', '.'),
                'total' => number_format($item->debit - $item->credit, 2, ',', '.'),
            ];
        });

        $accountTransaction->setCollection($formattedAccountTransaction);

        return $accountTransaction;
    }

    public function searchAccountTransactionBasedOnUserBranch(Request $request, int $perPage): LengthAwarePaginator
    {
        $search = $request->search;
        $accountTransaction = self::with('account')
            ->where('description', 'like', '%'.$search.'%')
            ->paginate($perPage);

        self::formattedAccounTransactionData($accountTransaction);

        return $accountTransaction;
    }

    public function getGeneralJournalPeriodBasedOnUserBranch(int $perPage): LengthAwarePaginator
    {
        return self::with('account')
            ->selectRaw("CONCAT(MONTH(date), '-', YEAR(date)) as waktu")
            ->distinct()
            ->paginate($perPage);
    }


    public function getGeneralJournalDataDetails()
    {
    }

    public function getCurrentPPNOnInvoice($description): self
    {
        return self::whereHas('account')->whereHas('account', function ($query) {
            $query->where('code', '213-01');
        })->where('description', $description)->first();
    }
}
