<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
    use HasFactory;

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
        $account = self::where('branch_id', $request->user()->branch_id)
            ->whereBetween('code', ['121', '126']);

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
