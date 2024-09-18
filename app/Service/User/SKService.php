<?php

namespace App\Service\User;

use App\Http\Requests\User\SKRequest;
use App\Models\Role;
use App\Models\SK;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

use function App\Helper\convertToRoman;

class SKService
{


    private static int $perPage = 10;
    private SK $sk;


    public function __construct()
    {
        $this->sk = new SK();
    }

    public function data()
    {
        $sk = $this->sk->getData()->paginate(10);
        return self::formattedData($sk);
    }

    private static function formattedData(LengthAwarePaginator $sk): LengthAwarePaginator
    {
        $data = $sk->getCollection()->map(callback: function ($item) {
            return [
                'id' => $item->id,
                'sk_number' => $item->sk_number,
                'user_name' => '('.$item->user->nip.')'.$item->user->name,
                'sk_type' => $item->sk_type,
                'date' => Carbon::parse($item->date)->locale('id')->settings(['formatFunction' => 'translatedFormat']
                )->format('l, j F Y'),
            ];
        });

        $sk->setCollection($data);
        return $sk;
    }

    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $query = $this->sk->getData();

        if (!empty($search)) {
            $query->where('sk_number', 'like', '%'.$search.'%')
                ->orWhere('sk_type', 'like', '%'.$search.'%')
                ->orWhere('date', 'like', '%'.$search.'%')
                ->orWhereHas('newRole', function ($query) use ($search) {
                    $query->where('name', 'like', '%'.$search.'%');
                })->orWhereHas('user', function ($query) use ($search) {
                    $query->where('name', 'like', '%'.$search.'%');
                    $query->orWhere('nip', 'like', '%'.$search.'%');
                });
        }


        $data = $query->paginate(self::$perPage);
        return self::formattedData($data);
    }

    public function store(SKRequest $request)
    {
        DB::transaction(callback: function () use ($request) {
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

    private static function generateSKNumber(SKRequest $request): string
    {
        $sk = SK::latest()->first();

        $skMonth = convertToRoman(Carbon::parse($request->date)->format('m'));
        $skYear = Carbon::parse($request->date)->format('Y');

        if ($sk) {
            $convertInvNumberToArray = explode('/', $sk->sk_number);
            $startingNumber = $convertInvNumberToArray[0];
            $startValue = str_pad((int)$startingNumber + 1, 3, '0', STR_PAD_LEFT);

            return $startValue.'/MY-SP/'.$skMonth.'/'.$skYear;
        }

        $startingNumber = '000';
        $startValue = str_pad((int)$startingNumber + 1, 3, '0', STR_PAD_LEFT);

        return $startValue.'/MY-SK/'.$skMonth.'/'.$skYear;
    }

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