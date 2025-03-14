<?php

namespace App\Support\User\SK;

use App\Http\Requests\User\SKRequest;
use App\Models\Role;
use App\Models\SK;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Throwable;
use function App\Helper\convertToRoman;
use function App\Helper\formatDate;

class SKService
{
    private static int $perPage = 10;


    private static function generateSKNumber(SKRequest $request): string
    {
        $sk = SK::latest()->first();

        $skMonth = convertToRoman(Carbon::parse($request->date)->format('m'));
        $skYear = Carbon::parse($request->date)->format('Y');

        if ($sk) {
            $convertInvNumberToArray = explode('/', $sk->sk_number);
            $startingNumber = $convertInvNumberToArray[0];
            $startValue = str_pad((int)$startingNumber + 1, 3, '0', STR_PAD_LEFT);

            return $startValue . '/MY-SP/' . $skMonth . '/' . $skYear;
        }

        $startingNumber = '000';
        $startValue = str_pad((int)$startingNumber + 1, 3, '0', STR_PAD_LEFT);

        return $startValue . '/MY-SK/' . $skMonth . '/' . $skYear;
    }

    public function data(): LengthAwarePaginator
    {
        $sk = SK::with('user', 'oldBranch', 'newBranch', 'oldRole', 'newRole')
            ->paginate(self::$perPage);
        return self::formattedData($sk);
    }

    private static function formattedData(LengthAwarePaginator $sk): LengthAwarePaginator
    {
        $data = $sk->getCollection()->map(callback: static function ($item) {
            return [
                'id' => $item->id,
                'sk_number' => $item->sk_number,
                'user_name' => '('.$item->user->nip.')'.$item->user->name,
                'sk_type' => $item->sk_type,
                'date' => formatDate($item->date),
            ];
        });

        $sk->setCollection($data);
        return $sk;
    }

    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $query = SK::search($search)->query(static function ($query) {
            $query->join('users', 'users.id', '=', 'sk.user_id');
        })->paginate(self::$perPage);
        return self::formattedData($query);
    }


    public function filter(Request $request): LengthAwarePaginator
    {
        $query = SK::with('user', 'oldBranch', 'newBranch', 'oldRole', 'newRole');
        $sk = SKQueryFilter::apply($query, $request)->paginate(self::$perPage);
        return self::formattedData($sk);
    }

    /**
     * @throws Throwable
     */
    public function store(SKRequest $request): void
    {
        DB::transaction(callback: static function () use ($request) {
            $user = User::with('roles')->where('id', $request->user_id)->first();
            SK::create([
                'sk_number' => self::generateSKNumber($request),
                'user_id' => $request->user_id,
                'sk_type' => $request->sk_type,
                'date' => $request->date,
                'old_branch_id' => $user->branch_id,
                'new_branch_id' => $user->branch_id,
                'old_role_id' => $user->roles?->first()?->id,
                'new_role_id' => $request->role_id,
            ]);


            $user = User::where('id', $request->user_id)->first();
            $newRole = Role::where('id', $request->role_id)->first()->name;
            $user->syncRoles($newRole);
            $user->branch_id = $request->branch_id;
            $user->save();
        });
    }

    /**
     * @throws Throwable
     */
    public function update(SKRequest $request, SK $sk): void
    {
        DB::transaction(function () use ($request, $sk) {
            $user = User::with('roles')->where('id', $request->user_id)->first();
            $sk->update([
                'user_id' => $request->user_id,
                'sk_type' => $request->sk_type,
                'date' => $request->date,
                'old_branch_id' => $user->branch_id,
                'new_branch_id' => $user->branch_id,
                'old_role_id' => $user->roles?->first()?->id,
                'new_role_id' => $request->role_id,
            ]);


            $user = User::where('id', $request->user_id)->first();
            $newRole = Role::where('id', $request->role_id)->first()->name;
            $user->syncRoles($newRole);
            $user->branch_id = $request->branch_id;
            $user->save();
        });
    }


}
