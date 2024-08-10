<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int $account_id
 * @property float $debit_balance
 * @property float $credit_balance
 * @property float $balance
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Account $account
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AccountTransaction> $accountTransactions
 * @property-read int|null $account_transactions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AccountTransaction> $subAccount
 * @property-read int|null $sub_account_count
 *
 * @method static Builder|SubAccount newModelQuery()
 * @method static Builder|SubAccount newQuery()
 * @method static Builder|SubAccount query()
 * @method static Builder|SubAccount whereAccountId($value)
 * @method static Builder|SubAccount whereBalance($value)
 * @method static Builder|SubAccount whereCode($value)
 * @method static Builder|SubAccount whereCreatedAt($value)
 * @method static Builder|SubAccount whereCreditBalance($value)
 * @method static Builder|SubAccount whereDebitBalance($value)
 * @method static Builder|SubAccount whereId($value)
 * @method static Builder|SubAccount whereName($value)
 * @method static Builder|SubAccount whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class SubAccount extends Model
{
    protected $table = 'sub_accounts';

    protected $fillable = [
        'code',
        'name',
        'account_id',
        'debit_balance',
        'credit_balance',
        'balance',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id', 'id');
    }

    public function subAccount(): HasMany
    {
        return $this->hasMany(AccountTransaction::class, 'sub_account_id', 'id');
    }

    public function accountTransactions()
    {
        return $this->hasMany(AccountTransaction::class);
    }

    //eloquent
    public function getSubAccountBasedOnUserBranch()
    {
        $query = self::whereHas('account', static function ($query) {
            $query->where('branch_id', Auth::user()->branch_id);
        })->orderBy('code', 'ASC')->paginate(10);

        self::formattedData($query);

        return $query;
    }

    public function searchSubAccountBasedOnUserBranch(Request $request)
    {
        $subAccount = self::whereHas('account', static function ($query) {
            $query->where('branch_id', Auth::user()->branch_id);
        })->where('name', 'like', '%'.$request->search.'%')
            ->orWhere('code', 'like', '%'.$request->search.'%')
            ->orderBy('code', 'ASC')
            ->paginate(10);

        self::formattedData($subAccount);

        return $subAccount;
    }

    private static function formattedData($query): void
    {
        $formattedData = $query->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'code' => $item->code,
                'name' => $item->name,
                'debit_balance' => number_format($item->debit_balance, 2, ',', '.'),
                'credit_balance' => number_format($item->credit_balance, 2, ',', '.'),
                'balance' => number_format($item->balance, 2, ',', '.'),
            ];
        });
        $query->setCollection($formattedData);
    }

    public function getSubAccountForInvoiceStore(Request $request): array
    {
        $elo = self::with('account')->whereHas('account', function ($query) use ($request) {
            $query->where('branch_id', $request->user()->branch_id);
            $query->whereBetween('accounts.code', ['401', '402']);
        })->get();

        return $elo->map(function ($account) {
            return [
                'id' => $account->id,
                'text' => $account->name,
            ];
        })->toArray();
    }

    public function getSelectedSubAccount(Request $request, int $subAccountId): self
    {
        return self::whereHas('account', function ($query) use ($request) {
            $query->where('branch_id', $request->user()->branch_id);
        })->where('id', $subAccountId)->first();
    }

    public function findPiutangPelangganSubAccount($branchId): self
    {
        return self::whereHas('account', static function ($query) use ($branchId) {
            $query->where('branch_id', $branchId);
        })->where('code', '113-01')->first();
    }

    public function findPPNSubAccount($branchId): self
    {
        return self::whereHas('account', static function ($query) use ($branchId) {
            $query->where('branch_id', $branchId);
        })->where('code', '213-01')->first();
    }

    public function findRekeningMayatamaPusatSubAccount($branchId): self
    {
        return self::whereHas('account', static function ($query) use ($branchId) {
            $query->where('branch_id', $branchId);
        })->where('code', '111-04')->first();
    }

    public function findPPH23SubAccount($branchId): self
    {
        return self::whereHas('account', static function ($query) use ($branchId) {
            $query->where('branch_id', $branchId);
        })->where('code', '115-02')->first();
    }

    public function getAllPersediaanSubAccount(Request $request): array
    {

        $search = $request->input('search');
        $query = self::with('account')->whereHas('account', function ($query) use ($request) {
            $query->where('branch_id', $request->user()->branch_id);
            $query->where('code', '112');
            $query->orderBy('name');
        });

        if ($search !== '') {
            $query->where('name', 'like', '%'.$search.'%');
        }

        return $query->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->name,
            ];
        })->toArray();
    }

    public function getKasSubAccount(): self
    {
        return self::where('code', '111-01')->first();
    }

    public function getPenjualanAtauPendapatanJasaLainnyaSubAccount(?int $branchId): Model|Builder|null
    {
        return self::with('account')->whereHas('account', static function ($query) use ($branchId) {
            $query->where('branch_id', $branchId);
        })->where('code', '403-04')->first();
    }
}
