<?php

namespace App\Support\User\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class UserRepository
{

    public function __construct()
    {
        $this->user = new User();
    }

    public function getUsers($query, Request $request)
    {
        return $query->with('branch', 'roles', 'company')
            ->when($request->user()->hasRole('Branch Manager'), function ($query) use ($request) {
                $query->where('branch_id', $request->user()->branch_id)
                    ->where('id', '!=', $request->user()->id);
            })->orderBy('absent_id');
    }


    public function getUsersAbsentId()
    {
        return User::orderBy('absent_id')->pluck('absent_id')->toArray();
    }


    public function getUnassignedTechnician(Request $request, int $branchId): Builder
    {
        $search = $request->search;
        $query = User::with('roles')->whereDoesntHave('userHasArea')
            ->whereHas('roles', function ($query) {
                $query->whereIn('name', ['Head Engineer', 'Engineer', 'Senior Engineer', 'Vendor', 'KU Head Engineer', 'KU Engineer']);
            })
            ->where('branch_id', $branchId)
            ->orderBy('name')
            ->select('id', 'name', 'nip');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('nip', 'like', '%' . $search . '%');
            });
        }

        return $query;
    }

    public function findById(int $id)
    {
        return $this->user->query()
            ->with('roles')
            ->find($id);
    }

}
