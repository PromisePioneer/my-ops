<?php

namespace App\Support\User\User;

use AllowDynamicProperties;
use App\Http\Requests\User\UserRequest;
use App\Models\User;
use App\Models\WeekHoliday;
use App\Support\HelperService\UserSelect2QueryFilter;
use App\Support\Master\Common\Branch\Service\BranchService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Activitylog\Contracts\Activity;
use Throwable;
use function App\Helper\formatDate;

#[AllowDynamicProperties] class UserService
{
    private static int $perPage = 10;

    public function __construct()
    {
        $this->branchService = new BranchService();
        $this->userRepository = new UserRepository();
        $this->userQueryFilter = new UserQueryFilter();
        $this->user = new User();
    }


    public function data(User $user, Request $request): LengthAwarePaginator
    {
        $data = $this->userRepository->getUsers($user, $request)
            ->paginate(self::$perPage);
        return self::formattedData($data);
    }


    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $query = User::search($search)->query(function ($query) use ($request) {
            $getUsers = $this->userRepository->getUsers($query, $request);
            UserQueryFilter::apply($getUsers, $request);
        });

        $data = $query->paginate(self::$perPage);
        return self::formattedData($data);
    }


    public function filter(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $users = User::search($search)->query(function ($query) use ($request) {
            $getUsers = $this->userRepository->getUsers($query, $request);
            UserQueryFilter::apply($getUsers, $request);
        })->paginate(self::$perPage);
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
                'join_date' => formatDate($user->join_date),
                'profile_pic' => $user->profile_pic,
                'active' => $user->active,
                'email' => $user->email,
            ];
        });

        $user->setCollection($data);
        return $user;
    }


    /**
     * @throws Throwable
     */
    public function store(UserRequest $request): void
    {
        DB::transaction(function () use ($request) {
            $data = $request->validated();
            $data['placement'] = $request->placement;
            $data['branch_id'] = $request->branch_id;
            if ($request->user()->hasRole('Branch Manager')) {
                $data['placement'] = 'Cabang';
                $data['branch_id'] = $request->user()->branch_id;
            }
            $data['password'] = Hash::make($request->password);
            $data['nip'] = $request->roles[0] === 'Vendor' ? null : $this->formattedNip($data);
            $user = User::create($data);
            $user->syncRoles($request->roles);


            $day = ['Sening', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
            if (in_array($request->input('day'), $day)) {
                WeekHoliday::updateOrCreate([
                    'user_id' => $user->id,
                ], [
                    'day' => $request->day
                ]);
            }
        });
    }

    /**
     * @throws Throwable
     */
    public function update(UserRequest $request, User $user): void
    {
        DB::transaction(function () use ($request, $user) {
            $data = $request->validated();
            $data['placement'] = $request->placement;
            $data['branch_id'] = $request->branch_id;
            if ($request->user()->hasRole('Branch Manager')) {
                $data['placement'] = 'Cabang';
                $data['branch_id'] = $request->user()->branch_id;
            }
            $data['password'] = empty($request->password)
                ? $user->password : Hash::make($request->password);
            $data['nip'] = $request->roles[0] === 'Vendor' ? null : $this->formattedNip($data);
            $user->update($data);
            $user->syncRoles($request->roles);

            $day = ['Sening', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
            if (in_array($request->input('day'), $day)) {
                WeekHoliday::updateOrCreate([
                    'user_id' => $user->id,
                ], [
                    'day' => $request->day
                ]);
            }
        });
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
                'Vendor',
                'KU Engineer',
                'KU Head Engineer'
            ]);
        });
    }

    public function getUsers(Request $request)
    {
        $search = $request->input('search');
        $user = User::search($search)->query(function ($query) {
            $query->where('active', true);
        })->get();


        return $user->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->name
            ];
        });
    }

    public function selectedUser(User $user): array
    {
        $data = User::where('id', $user->id)->first();

        return [
            'id' => $data->id,
            'name' => $data->name
        ];
    }


    public function getUserHasArea(Request $request)
    {
        $search = $request->input('search');
        $user = User::search($search)->query(function ($query) use ($request) {
            $newQuery = $query->where('active', true);
            UserSelect2QueryFilter::apply($newQuery, $request);
        })->get();

        return $user->map(function ($query) {
            return [
                'id' => $query->id,
                'text' => $query->name
            ];
        });
    }

    public function getUserByBranchId(int $branchId): \Illuminate\Support\Collection
    {
        $data = $this->userRepository->getUserByBranchId($branchId)->get();
        return $data->map(function ($query) {
            return [
                'id' => $query->id,
                'text' => $query->name
            ];
        });
    }


    public function getArchivedData(Request $request): LengthAwarePaginator
    {
        $data = $this->userRepository->getTrashed($request)->paginate(self::$perPage);
        return self::formattedData($data);
    }


    public function searchArchivedData(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $data = $this->user::search($search)->onlyTrashed()->paginate(self::$perPage);
        return self::formattedData($data);
    }


    public function restore(Request $request, User $user): ?bool
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        return $user->whereIn('id', $explodeID)->restore();
    }


    public function forceDelete(Request $request, User $user): void
    {
        $contacts = $user->with(['transaction', 'offeringLetter', 'initialInventoryBalance'])->whereIn('id', $request->get('id'))
            ->onlyTrashed()
            ->forceDelete();
    }

    public function filterArchivedData(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $users = User::search($search)->query(function ($query) use ($request) {
            $getUsers = $this->userRepository->getUsers($query, $request);
            UserQueryFilter::apply($getUsers, $request);
        })->onlyTrashed()->paginate(self::$perPage);
        return self::formattedData($users);
    }
}
