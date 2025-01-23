<?php

namespace App\Service\User;

use App\Http\Requests\User\UserRequest;
use App\Models\Branch;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use function App\Helper\randomDigits;

class UserService
{
    private Branch $branch;
    private static int $perPage = 10;

    public function __construct()
    {
        $this->branch = new Branch();
    }


    public function data(Request $request): LengthAwarePaginator
    {
        $data = User::with('branch', 'roles', 'company')->when($request->user()->hasRole('Branch Manager'), function ($query) use ($request) {
            $query->where('branch_id', $request->user()->branch_id);
        })->paginate(self::$perPage);
        return self::formattedData($data);
    }


    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $query = User::with('branch', 'roles', 'company')->when($request->user()->hasRole('Branch Manager'), function ($query) use ($request) {
            $query->where('branch_id', $request->user()->branch_id);
        });
        if (!empty($search)) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->orWhere('nip', 'like', '%' . $search . '%');
        }

        $data = $query->paginate(self::$perPage);
        return self::formattedData($data);
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
        $data['placement'] = $request->user()->branch_id ? 'Cabang' : 'Pusat';
        $data['branch_id'] = $request->user()->branch_id ? $request->user()->branch_id : $request->branch_id;
        $data['password'] = Hash::make('password');
        $data['nip'] = $this->formattedNip($request);
        $user = User::create($data);
        $user->syncRoles($request->role);
    }

    public function formattedNip(UserRequest $request): array|string
    {
        $branch = $this->branch->getSelectedData($request->branch_id);
        $date = Carbon::parse($request->join_date)->format('d-m-y');
        $branchCode = $branch['code'] ?? '100';
        return str_replace('-', '', $branchCode . $date . $request->absent_id);
    }

    public function update(UserRequest $request, User $user): void
    {
        $data = $request->validated();
        $data['placement'] = $request->user()->branch_id ? 'Cabang' : 'Pusat';
        $data['branch_id'] = $request->user()->branch_id ? $request->user()->branch_id : $request->branch_id;
        $data['password'] = Hash::make('password');
        $data['nip'] = $this->formattedNip($request);
        $user->update($data);
        $user->syncRoles($request->role);
    }


    public function randomAbsentId(): int|string
    {
        $randomAbsentId = randomDigits();
        while (User::where('absent_id', $randomAbsentId)->count() > 0) {
            $randomAbsentId = mt_rand();
        }
        return $randomAbsentId;
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
                'Engineer'
            ]);
        });
    }

    public function filter(Request $request): LengthAwarePaginator
    {
        $users = User::with('branch', 'roles', 'company');

        if ($request->user()->can('Filter Data Karyawan Berdasarkan Cabang') && $request->branch_id) {
            $users->where('branch_id', $request->branch_id);
        }

        if ($request->user()->can('Filter Data Karyawan Berdasarkan Perusahaan') && $request->company_id) {
            $users->where('company_id', $request->company_id);
        }

        if ($request->user()->can('Filter Data Karyawan Berdasarkan Tahun') && $request->year) {
            $users->whereYear('join_date', $request->year);
        }

        if ($request->user()->can('Filter Data Karyawan Berdasarkan Bulan') && $request->month) {
            $users->whereMonth('join_date', '=', $request->month);
        }

        if (
            $request->user()->can('Filter Data Karyawan Berdasarkan Tahun')
            && $request->user()->can('Filter Data Karyawan Berdasarkan Bulan')
            && $request->month
            && $request->year
        ) {
            $users->whereDate('join_date', Carbon::parse('01-' . $request->month . '-' . $request->year));
        }

        $data = $users->when($request->user()->hasRole('Branch Manager'), function ($query) use ($request) {
            $query->where('branch_id', $request->user()->branch_id);
        })->paginate(self::$perPage);
        return self::formattedData($data);
    }
}
