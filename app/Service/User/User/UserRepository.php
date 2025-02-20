<?php

namespace App\Service\User\User;

use App\Models\User;
use Illuminate\Http\Request;

class UserRepository
{
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

}
