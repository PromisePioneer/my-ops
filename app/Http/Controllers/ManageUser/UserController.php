<?php

namespace App\Http\Controllers\ManageUser;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\IdentityInformationRequest;
use App\Http\Requests\User\JobInformationRequest;
use App\Http\Requests\User\UserRequest;
use App\Models\Branch;
use App\Models\Department;
use App\Models\User;
use App\Models\UserAttendance;
use App\Models\UserIdentityInformation;
use App\Models\UserJobInformation;
use App\Service\IdentityInformationService;
use App\Service\JobInformationService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public int $perPage = 10;

    private IdentityInformationService $identityInformationService;

    private UserJobInformation $jobInformation;

    private UserIdentityInformation $identityInformation;

    private Branch $branch;

    private User $user;

    private UserAttendance $attendance;

    private Department $department;

    private JobInformationService $jobInformationService;

    public function __construct()
    {
        $this->middleware('permission:lihat user', ['only' => ['index']]);
        $this->middleware('permission:tambah user', ['only' => ['create', 'store']]);
        $this->middleware('permission:update user', ['only' => ['edit', 'update']]);
        $this->middleware('permission:hapus user', ['only' => ['destroy']]);

        $this->user = new User;
        $this->branch = new Branch;
        $this->department = new Department;
        $this->identityInformationService = new IdentityInformationService;
        $this->jobInformation = new userJobInformation;
        $this->identityInformation = new UserIdentityInformation;
        $this->attendance = new UserAttendance;
        $this->jobInformationService = new JobInformationService;
    }

    public function index(): View
    {
        return view('pages.manage-users.user.index');
    }

    public function usersData(): JsonResponse
    {
        $user = $this->user->getDataWithPagination($this->perPage);

        return response()->json($user);
    }

    public function search(Request $request): JsonResponse
    {
        return response()->json($this->user->searchData($request));
    }

    public function branchData(Request $request): JsonResponse
    {
        return response()->json($this->branch->getData($request));
    }

    public function filterByBranch(Branch $branch): JsonResponse
    {
        return response()->json($this->user->filterBasedOnUserBranch($branch->id, $this->perPage));
    }

    public function create(): View
    {
        $randomAbsentId = $this->random_digits();

        while (User::where('absent_id', $randomAbsentId)->count() > 0) {
            $randomAbsentId = mt_rand();
        }

        return view('pages.manage-users.user.create', compact('randomAbsentId'));
    }

    private function random_digits(): string
    {
        $result = '';

        for ($i = 0; $i < 3; $i++) {
            $result .= random_int(0, 9);
        }

        return $result;
    }

    public function rolesData(): JsonResponse
    {
        $role = Role::all();

        return response()->json($role);
    }

    public function store(UserRequest $request): JsonResponse
    {
        $branch = $this->branch->getSelectedData($request->branch_id);
        $date = Carbon::parse($request->join_date)->format('d-m-y');

        $handlingBranchIfDataNull = $branch['code'] ?? '100';
        $format = $handlingBranchIfDataNull.$date.$request->absent_id;
        $data = $request->validated();
        $data['password'] = Hash::make('MayatamaPekanbaru2024');
        $data['nip'] = str_replace('-', '', $format);
        $user = User::create($data);
        $user->assignRole($request->role);

        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }

    public function edit(User $user): View
    {
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();

        return view('pages.manage-users.user.edit', compact('user', 'userRole', 'roles'));
    }

    public function update(User $user, UserRequest $request): JsonResponse
    {

        $data = $request->validated();
        $branch = $this->branch->getSelectedData($request->branch_id);
        $date = Carbon::parse($request->join_date)->format('d-m-y');
        $data['password'] = Hash::make('MayatamaPekanbaru2024');
        $handlingBranchIfDataNull = $branch['code'] ?? '100';
        $format = $handlingBranchIfDataNull.$date.$request->absent_id;
        $data['nip'] = str_replace('-', '', $format);
        $user->update($data);
        $user->syncRoles($request->role);

        return response()->json([
            'message' => 'data sukses diupdate!',
        ]);
    }

    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return response()->json([
            'message' => 'data sukses dihapus!',
            'data' => $user,
        ]);
    }

    public function getSelectedBranch(User $user): JsonResponse
    {
        $branch = $this->branch->getSelectedData($user->branch_id);

        return response()->json($branch);
    }

    public function detail(User $user): View
    {
        return view('pages.manage-users.user.detail', compact('user'));
    }

    public function identityInformation(User $user): JsonResponse
    {
        return response()->json($this->identityInformation->getRelatedUserIdentityInformation($user->id));
    }

    public function identityInformationUpdate(IdentityInformationRequest $request, User $user): JsonResponse
    {
        $this->identityInformationService->update($request, $user);

        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }

    public function jobInformation(User $user): JsonResponse
    {
        return response()->json($this->jobInformation->getRelatedUserJobInformation($user->id));
    }

    public function getSelectedDepartment(User $user): JsonResponse
    {

        $users = $user->whereHas('jobInformation')->first();

        return response()->json($this->department->getSelectedData($users->jobInformation?->department_id));
    }

    public function jobInformationUpdate(JobInformationRequest $request, User $user): JsonResponse
    {
        $this->jobInformationService->update($request, $user);

        return response()->json([
            'message' => 'Data berhasil disimpan',
        ]);
    }

    public function viewFileJobInformation(User $user): View
    {
        $user = UserJobInformation::where('user_id', $user->id)->first();

        return view('pages.manage-users.user.partials.detail.job-information.view-file', compact('user'));
    }

    public function getDepartmentData(Request $request): JsonResponse
    {
        return response()->json($this->department->getData($request));
    }

    public function getAbsentData(User $user): JsonResponse
    {
        return response()->json($this->attendance->getDataWithPaginationBasedOnUser($user->id, $this->perPage));
    }

    public function show(User $user): JsonResponse
    {
        $users = $user->with('roles')->find($user->id);

        return response()->json($users);
    }
}
