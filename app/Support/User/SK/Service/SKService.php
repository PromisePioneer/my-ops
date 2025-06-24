<?php

namespace App\Support\User\SK\Service;

use AllowDynamicProperties;
use App\Http\Requests\User\SKRequest;
use App\Models\SK;
use App\Support\User\Role\Repository\RoleRepository;
use App\Support\User\SK\Repository\SKRepository;
use App\Support\User\User\UserRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Throwable;
use function App\Helper\convertToRoman;
use function App\Helper\formatDate;

#[AllowDynamicProperties] class SKService
{
    private static int $perPage = 10;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->skRepository = new SKRepository();
        $this->roleRepository = new RoleRepository();
        $this->sk = new SK();
    }


    public function generateSKNumber(SKRequest $request): string
    {
        $sk = $this->sk->query()->latest()->first();
        $skMonth = convertToRoman(Carbon::parse($request->input('date'))->format('m'));
        $skYear = Carbon::parse($request->input('date'))->format('Y');
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
        $sk = $this->skRepository->data()->paginate(self::$perPage);
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
        $query = $this->sk->search($search)->query(static function ($query) {
            $query->join('users', 'users.id', '=', 'sk.user_id');
        })->paginate(self::$perPage);
        return self::formattedData($query);
    }


    public function filter(Request $request): LengthAwarePaginator
    {
        $query = $this->skRepository->data();
        $sk = SKQueryFilter::apply($query, $request)->paginate(self::$perPage);
        return self::formattedData($sk);
    }

    /**
     * @throws Throwable
     */
    public function store(SKRequest $request): void
    {
        DB::transaction(callback:  function () use ($request) {
            $user = $this->userRepository->findById($request->input('user_id'));
            SK::create([
                'sk_number' => self::generateSKNumber($request),
                'user_id' => $request->input('user_id'),
                'sk_type' => $request->input('sk_type'),
                'date' => $request->input('date'),
                'old_branch_id' => $user->branch_id ?? null,
                'new_branch_id' => $request->input('new_branch_id'),
                'old_role_id' => $user->roles?->first()?->id,
                'new_role_id' => $request->input('role_id'),
            ]);

            $newRole = $this->roleRepository->findById($request->input('role_id'))->name;
            $user->syncRoles($newRole);
            $user->branch_id = $request->input('new_branch_id');
            $user->save();
        });
    }

    /**
     * @throws Throwable
     */
    public function update(SKRequest $request, SK $sk): void
    {
        DB::transaction(callback: function () use ($request, $sk) {
            $user = $this->userRepository->findById($request->input('user_id'));
            $sk->update([
                'user_id' => $request->input('user_id'),
                'sk_type' => $request->input('sk_type'),
                'date' => $request->input('date'),
                'old_branch_id' => $user->branch_id ?? null,
                'new_branch_id' => $request->input('new_branch_id'),
                'old_role_id' => $user->roles?->first()?->id,
                'new_role_id' => $request->input('role_id'),
            ]);

            $newRole = $this->roleRepository->findById($request->input('role_id'))->name;
            $user->syncRoles($newRole);
            $user->branch_id = $request->input('new_branch_id');
            $user->save();
        });
    }


}
