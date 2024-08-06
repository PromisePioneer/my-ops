<?php

namespace App\Http\Controllers\ManageUser;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserAttendanceRequest;
use App\Models\User;
use App\Models\UserAttendance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserAttendanceController extends Controller
{
    public function index(): View
    {
        return view('pages.manage-users.attendance.index');
    }

    public function data(): JsonResponse
    {
        $attendance = UserAttendance::with('user', 'user.roles', 'jobInformation', 'jobInformation.department')
            ->paginate(10);

        return response()->json($attendance);
    }

    public function search(Request $request): JsonResponse
    {
        $search = $request->input('search');
        $query = UserAttendance::with('user', 'user.roles', 'jobInformation', 'jobInformation.department')
            ->where('date', 'like', '%'.$search.'%')
            ->whereHas('user', static function ($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%');
                $query->where('nip', 'like', '%'.$search.'%');
            })->get();

        return response()->json($query);
    }

    public function getUserData(Request $request): JsonResponse
    {
        $search = $request->search;
        if ($search === '') {
            $user = User::with('roles')->orderby('name', 'asc')
                ->select('id', 'name')
                ->get();
        } else {
            $user = User::with('roles')
                ->orderby('name', 'asc')
                ->select('id', 'name')
                ->where('name', 'like', '%'.$search.'%')
                ->get();
        }
        $response = [];
        foreach ($user as $u) {
            $response[] = [
                'id' => $u->id,
                'text' => $u->name,
            ];
        }

        return response()->json($response);
    }

    public function store(UserAttendanceRequest $request): JsonResponse
    {
        $attendance = UserAttendance::create($request->validated());

        return response()->json($attendance);
    }

    public function edit(UserAttendance $userAttendance): JsonResponse
    {
        return response()->json($userAttendance);
    }

    public function update(): JsonResponse {}

    public function destroy(UserAttendance $userAttendance): JsonResponse
    {
        return response()->json($userAttendance->delete());
    }
}
