<?php

namespace App\Http\Controllers\ManageUser;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserRequest;
use App\Imports\UserImport;
use App\Models\Attendances;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use App\Service\UserService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public int $perPage = 10;
    private Branch $branch;
    private User $user;
    private Department $department;
    private Attendances $attendances;
    private UserService $userService;

    public function __construct()
    {
        $this->user = new User();
        $this->branch = new Branch();
        $this->department = new Department();
        $this->attendances = new Attendances();
        $this->userService = new UserService();
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
    public function data(): JsonResponse
    {
        $this->authorize('view', User::class);
        $query = $this->user->getData()->paginate($this->perPage)->onEachSide(1);
        return response()->json($query);
    }

    /**
     * @throws AuthorizationException
     */
    public function search(Request $request): JsonResponse
    {
        $this->authorize('view', User::class);
        return response()->json($this->user->searchData($request));
    }

    /**
     * @throws AuthorizationException
     */
    public function branchData(Request $request): JsonResponse
    {
        $this->authorize('view', User::class);
        return response()->json($this->branch->getData($request));
    }

    /**
     * @throws AuthorizationException
     */
    public function rolesData(Request $request): JsonResponse
    {
        $this->authorize('create', User::class);
        $roles = Role::all();
        $this->userService->isUserHasRoleBranchManager($request, $roles);
        return response()->json($roles);
    }

    /**
     * @throws AuthorizationException
     */
    public function filter(Request $request): JsonResponse
    {
        $this->authorize('view', User::class);

        return response()->json($this->userService->filter($request)->paginate(10)->onEachSide(1));
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
    public function create(): View
    {
        $this->authorize('create', User::class);
        $randomAbsentId = $this->userService->randomAbsentId();

        return view('pages.manage-users.user.create', compact('randomAbsentId'));
    }

    /**
     * @throws AuthorizationException
     */
    public function edit(User $user): View
    {
        $this->authorize('update', User::class);
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();
        return view('pages.manage-users.user.edit', compact('user', 'userRole', 'roles'));
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(User $user): JsonResponse
    {
        $this->authorize('delete', User::class);
        $user->delete();
        return response()->json([
            'message' => 'data sukses dihapus!',
            'data' => $user,
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function getSelectedBranch(User $user): JsonResponse
    {
        $this->authorize('update', User::class);
        $branch = $this->branch->getSelectedData($user->branch_id);
        return response()->json($branch);
    }

    public function detail(User $user): View
    {
        $role = Role::with('department')->where('id', $user->roles[0]->id ?? null)->first();
        return view('pages.manage-users.user.detail', compact('user', 'role'));
    }

    /**
     * @throws AuthorizationException
     */
    public function update(UserRequest $request, User $user): JsonResponse
    {
        $this->authorize('viewDetail', User::class);
        $this->userService->update($request, $user);
        return response()->json([
            'message' => 'data sukses diupdate!',
        ]);
    }

    public function getDepartmentData(Request $request): JsonResponse
    {
        return response()->json($this->department->getData($request));
    }

    public function getAbsentData(User $user): JsonResponse
    {
        return response()->json($this->attendances->getAttendancesDataBasedOnUserId($this->perPage, $user->absent_id));
    }

    public function show(User $user): JsonResponse
    {
        $users = $user->with('roles')->find($user->id);
        return response()->json($users);
    }

    public function import(Request $request): JsonResponse
    {
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
        $user->active = !$user->active;
        $user->save();

        return response()->json(['message' => 'data sukses diupdate!']);
    }
}
