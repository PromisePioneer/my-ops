<?php

namespace App\Support\User\User;

use AllowDynamicProperties;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

#[AllowDynamicProperties] class UserRepository
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


    public function getUsersAbsentId(): array
    {
        return User::orderBy('absent_id')->pluck('absent_id')->toArray();
    }


    public function getUserByBranchId(int $branchId): User
    {
        return User::where('branch_id', $branchId);
    }

    public function getTrashed(): Builder
    {
        return $this->user->onlyTrashed();
    }

}
