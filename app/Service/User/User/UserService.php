<?php

namespace App\Service\User\User;

use AllowDynamicProperties;
use App\Http\Requests\User\UserRequest;
use App\Models\User;
use App\Service\Master\General\Branch\BranchService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

#[AllowDynamicProperties] class UserService
{
    private static int $perPage = 10;

    public function __construct()
    {
        $this->branchService = new BranchService();
        $this->userRepository = new UserRepository();
        $this->userQueryFilter = new UserQueryFilter();
    }


    public function data(User $user, Request $request): LengthAwarePaginator
    {
        $data = $this->userRepository->getUsers($user, $request)
            ->paginate(self::$perPage);
        return self::formattedData($data);
    }


    public function search(User $user, Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $query = User::search($search)->query(function () use ($user, $request) {
            $this->userRepository->getUsers($user, $request);
        })->paginate(self::$perPage);
        return self::formattedData($query);
    }


    public function filter(Request $request): LengthAwarePaginator
    {
        $query = User::query();
        $users = UserQueryFilter::apply($query, $request)->paginate(self::$perPage);

        return self::formattedData($users);
    }


    public function formattedData(LengthAwarePaginator $user): LengthAwarePaginator
    {
        $data = $user->getCollection()->map(function ($user) {
            return [
                'id' => $user->id,
                'nik' => $user->nip,
                'name' => $user->name,
                'roles' => $user->roles[0]?->name ?? '',
                'branch' => $user->branch?->name,
                'company' => $user->company?->name ?? '',
                'join_date' => $user->join_date,
                'profile_pic' => $user->profile_pic,
                'active' => $user->active,
            ];
        });

        $user->setCollection($data);
        return $user;
    }


    public function store(UserRequest $request): void
    {
        $data = $request->validated();
        $data['placement'] = $request->placement;
        $data['branch_id'] = $request->branch_id;
        if ($request->user()->hasRole('Branch Manager')) {
            $data['placement'] = 'Cabang';
            $data['branch_id'] = $request->user()->branch_id;
        }
        $data['password'] = Hash::make($request->password);
        $data['nip'] = $this->formattedNip($data);
        $user = User::create($data);
        $user->syncRoles($request->roles);
    }

    public function update(UserRequest $request, User $user): void
    {
        $data = $request->validated();
        $data['placement'] = $request->placement;
        $data['branch_id'] = $request->branch_id;
        if ($request->user()->hasRole('Branch Manager')) {
            $data['placement'] = 'Cabang';
            $data['branch_id'] = $request->user()->branch_id;
        }
        $data['password'] = empty($request->password) ? $user->password : Hash::make($request->password);
        $data['nip'] = $this->formattedNip($data);
        $user->update($data);
        if ($request->user()->hasRole('Super Admin')) {
            $user->syncRoles($request->roles);
        }
    }


    public function formattedNip(array $data): array|string
    {
        $branch = $this->branchService->selectedBranch($data['branch_id']);
        $date = Carbon::parse($data['join_date'])->format('d-m-y');
        $branchCode = $branch['code'] ?? '100';
        return str_replace('-', '', $branchCode . $date . $data['absent_id']);
    }


    public function randomAbsentId(): int
    {
        $getAllAbsentId = $this->userRepository->getUsersAbsentId();
        do {
            $n = rand(1, 999);
        } while ($n == $getAllAbsentId);

        return $n;
    }

    public function isUserHasRoleBranchManager(Collection $roles): Collection|\Illuminate\Support\Collection
    {
        return $roles->filter(function ($role) {
            return in_array($role->name, [
                'Finance & Accounting Staff',
                'Stocker Staff',
                'Customer Service Staff',
                'Head Engineer',
                'Senior Engineer',
                'Engineer',
                'Vendor'
            ]);
        });
    }
}
