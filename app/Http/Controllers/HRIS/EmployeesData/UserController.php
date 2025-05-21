<?php

namespace App\Http\Controllers\HRIS\EmployeesData;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserRequest;
use App\Imports\UserImport;
use App\Models\Attendances;
use App\Models\Company;
use App\Models\Department;
use App\Models\Master\Common\Branch;
use App\Models\Role;
use App\Models\User;
use App\Support\User\User\UserService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

#[AllowDynamicProperties] class UserController extends Controller
{
    public int $perPage = 10;


    public function __construct()
    {
        $this->user = new User();
        $this->branch = new Branch();
        $this->department = new Department();
        $this->userService = new UserService();
        $this->company = new Company();
    }

    /**
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('view', User::class);
        return view('pages.manage-users.user.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function data(User $user, Request $request): JsonResponse
    {
        $this->authorize('view', User::class);
        return response()->json($this->userService->data($user, $request));
    }

    /**
     * @throws AuthorizationException
     */
    public function search(Request $request): JsonResponse
    {
        $this->authorize('view', User::class);
        return response()->json($this->userService->search($request));
    }

    /**
     * @throws AuthorizationException
     */

    /**
     * @throws AuthorizationException
     */
    public function rolesData(Request $request): JsonResponse
    {
        $this->authorize('create', User::class);
        $roles = Role::all();

        if ($request->user()->hasRole('Branch Manager')) {
            return response()->json($this->userService->isUserHasRoleBranchManager($roles));
        }
        return response()->json($roles);
    }

    /**
     * @throws AuthorizationException
     */
    public function filter(Request $request): JsonResponse
    {
        $this->authorize('view', User::class);
        return response()->json($this->userService->filter($request));
    }

    public function create(): View
    {
        $this->authorize('create', User::class);
        $randomAbsentId = $this->userService->randomAbsentId();

        return view('pages.manage-users.user.form', compact('randomAbsentId'));
    }
    /**
     * @throws AuthorizationException
     */
    public function store(UserRequest $request): JsonResponse
    {
        $this->authorize('create', User::class);
        $this->userService->store($request);
        return response()->json(['message' => 'data berhasil disimpan']);
    }

    /**
     * @throws AuthorizationException
     */
    public function edit(User $user): View
    {
        $this->authorize('update', $user);
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();
        $randomAbsentId = $this->userService->randomAbsentId();

        return view('pages.manage-users.user.form', compact('randomAbsentId', 'user', 'userRole', 'roles'));
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(Request $request, User $user): JsonResponse
    {
        $this->authorize('delete', User::class);
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $user->whereIn('id', $explodeID)->delete();

        return response()->json(['message' => 'data berhasil dihapus']);
    }

    /**
     * @throws AuthorizationException
     */
    public function detail(User $user): View
    {
        $this->authorize('viewDetail', $user);
        $role = Role::with('department')
            ->where('id', $user->roles[0]->id ?? null)
            ->first();
        return view('pages.manage-users.user.detail', compact('user', 'role'));
    }

    /**
     * @throws AuthorizationException
     */
    public function update(UserRequest $request, User $user): JsonResponse
    {
        $this->authorize('update', User::class);
        $this->userService->update($request, $user);
        return response()->json(['message' => 'data sukses diupdate!']);
    }

    public function getDepartmentData(Request $request): JsonResponse
    {
        return response()->json($this->department->getData($request));
    }

    public function show(User $user): JsonResponse
    {
        $users = $user->with('roles')->find($user->id);
        return response()->json($users);
    }

    public function import(Request $request): JsonResponse
    {
        $this->authorize('import', User::class);
        ini_set('max_execution_time', 180);
        $file = $request->file('file_import');
        Excel::import(new UserImport(), $file);

        return response()->json(['message' => 'Data berhasil diimport']);
    }

    /**
     * @throws AuthorizationException
     */
    public function changeStatusActive(User $user): JsonResponse
    {
        $this->authorize('setActive', User::class);
        $user->update(['active' => !$user->active]);
        $user->absent_id = $user->active === false ? null : $user->absent_id;
        $user->save();

        return response()->json(['message' => 'data sukses diupdate!']);
    }


    public function getUsers(Request $request): JsonResponse
    {
        return response()->json($this->userService->getUsers($request));
    }

    public function selectedUser(User $user): JsonResponse
    {
        return response()->json($this->userService->selectedUser($user));
    }


    public function getUserHasArea(Request $request): JsonResponse
    {
        return response()->json($this->userService->getUserHasArea($request));
    }


    public function getStockerByBranchId(Request $request): JsonResponse
    {
        return response()->json($this->userService->getStockerByBranchId($request));
    }
}
