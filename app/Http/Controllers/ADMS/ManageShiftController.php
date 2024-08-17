<?php

namespace App\Http\Controllers\ADMS;

use App\Http\Controllers\Controller;
use App\Http\Requests\ADMS\ManageShiftRequest;
use App\Http\Requests\ADMS\UserShiftRequest;
use App\Models\ManageShift;
use App\Models\User;
use App\Models\UserShift;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ManageShiftController extends Controller
{
    public readonly int $perPage;
    private ManageShift $manageShift;
    private User $user;
    private UserShift $userShift;

    public function __construct()
    {
        $this->manageShift = new ManageShift();
        $this->user = new User();
        $this->userShift = new UserShift();
        $this->perPage = 10;
    }

    /**
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('view', ManageShift::class);
        return view('pages.adms.manage-shift.index');
    }

    public function data(Request $request): JsonResponse
    {
        $this->authorize('view', ManageShift::class);
        return response()->json($this->manageShift->getDataWithPagination($request->user()->branch_id, $this->perPage));
    }

    public function search()
    {
    }

    public function getUserData(Request $request): JsonResponse
    {
        return response()->json($this->user->getUserBasedOnBranch($request));
    }

    public function store(ManageShiftRequest $request): JsonResponse
    {
        $this->authorize('create', ManageShift::class);
        $data = $request->validated();
        $data['branch_id'] = $request->user()->branch_id ?? null;
        ManageShift::create($request->validated());
        return response()->json([
            'message' => 'Data berhasil disimpan'
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function edit(ManageShift $manageShift): JsonResponse
    {
        $this->authorize('update', $manageShift);
        return response()->json($manageShift);
    }

    /**
     * @throws AuthorizationException
     */
    public function update(ManageShiftRequest $request, ManageShift $manageShift): JsonResponse
    {
        $this->authorize('update', $manageShift);
        $manageShift->update($request->validated());
        return response()->json([
            'message' => 'Data berhasil disimpan'
        ]);
    }

    public function assignShift(UserShiftRequest $request, ManageShift $manageShift): JsonResponse
    {
        foreach ($request['user_id'] as $userId) {
            UserShift::updateOrCreate([
                'user_id' => $userId,
            ], [
                'shift_id' => $manageShift->id
            ]);
        }

        return response()->json([
            'message' => 'Data berhasil disimpan'
        ]);
    }


    public function getSelectedUserShift(ManageShift $manageShift): JsonResponse
    {
        return response()->json($this->userShift->getSelectedUserShift($manageShift->id));
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(
        ManageShift $manageShift
    ): JsonResponse {
        $this->authorize('destroy', $manageShift);
        $manageShift->delete();
        return response()->json([
            'message' => 'Data berhasil dihapus'
        ]);
    }
}
