<?php

namespace App\Service;

use App\Http\Requests\User\UserRequest;
use App\Models\Branch;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use function App\Helper\randomDigits;

class UserService
{

    private Branch $branch;
    private User $user;

    public function __construct()
    {
        $this->branch = new Branch();
        $this->user = new User();
    }

    public function store(UserRequest $request): void
    {
        $data = $request->validated();
        $data['placement'] = $request->branch_id ? 'Cabang' : 'Pusat';
        $data['branch_id'] = $request->branch_id;
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
        return str_replace('-', '', $branchCode.$date.$request->absent_id);
    }

    public function update(UserRequest $request, User $user): void
    {
        $data = $request->validated();
        $data['placement'] = $request->branch_id ? 'Cabang' : 'Pusat';
        $data['branch_id'] = $request->branch_id;
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

    public function isUserHasRoleBranchManager(Request $request, Collection $roles): void
    {
        if ($request->user()->hasRole('Branch Manager')) {
            $roles = $roles->filter(function ($role) {
                return in_array($role->name, [
                    'Finance & Accounting Staff', 'Stocker Staff', 'Customer Service Staff', 'Head Engineer',
                    'Senior Engineer',
                ]);
            });
        }
    }

    public function filter(Request $request)
    {
        $users = $this->user->getData()->where('active', $request->active ?? true);

        if ($request->branch_id) {
            $users->where('branch_id', $request->branch_id);
        }

        if ($request->year) {
            $users->whereYear('join_date', $request->year);
        }

        if ($request->month) {
            $users->whereMonth('join_date', '=', $request->month);
        }

        if ($request->month && $request->year) {
            $users->whereDate('join_date', Carbon::parse('01-'.$request->month.'-'.$request->year));
        }


        return $users;
    }


}