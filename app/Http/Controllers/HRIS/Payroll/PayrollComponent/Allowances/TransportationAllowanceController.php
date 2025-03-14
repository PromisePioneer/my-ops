<?php

namespace App\Http\Controllers\HRIS\Payroll\PayrollComponent\Allowances;

use App\Http\Controllers\Controller;
use App\Http\Requests\Allowances\TransportationAllowanceRequest;
use App\Models\User;
use App\Models\UserHasTransportationAllowance;
use App\Support\UserAllowance\TransportationAllowanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TransportationAllowanceController extends Controller
{

    private User $user;
    private TransportationAllowanceService $transportationAllowanceService;

    public function __construct()
    {
        $this->user = new User();
        $this->transportationAllowanceService = new TransportationAllowanceService();
    }

    public function index(): View
    {
        return view('pages.payroll.allowances.transportation.index');
    }


    public function data(): JsonResponse
    {
        return response()->json($this->transportationAllowanceService->data());
    }

    public function getUserData(Request $request): JsonResponse
    {
        return response()->json($this->user->getUser($request));
    }

    public function getSelectedUser(UserHasTransportationAllowance $userHasTransportationAllowance): JsonResponse
    {
        return response()->json($this->user->getSelectedData($userHasTransportationAllowance->user_id));
    }

    public function store(TransportationAllowanceRequest $request): JsonResponse
    {
        $this->transportationAllowanceService->store($request);
        return response()->json([
            'message' => 'Data sukses disimpan.',
        ]);
    }


    public function edit(UserHasTransportationAllowance $userHasTransportationAllowance): JsonResponse
    {
        return response()->json($userHasTransportationAllowance);
    }

    public function update(
        TransportationAllowanceRequest $request,
        UserHasTransportationAllowance $userHasTransportationAllowance
    ): JsonResponse {
        $data = $request->validated();
        $userHasTransportationAllowance->update($data);

        return response()->json([
            'message' => 'Data sukses disimpan.',
        ]);
    }

    public function destroy(
        Request $request,
        UserHasTransportationAllowance $userHasTransportationAllowance
    ): JsonResponse {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $images = $userHasTransportationAllowance->whereIn('id', $explodeID)->get();

        foreach ($images as $image) {
            Storage::disk('public')->delete($image->spk_image);
        }

        $userHasTransportationAllowance->whereIn('id', $explodeID)->delete();

        return response()->json([
            'message' => 'Data sukses dihapus.',
        ]);
    }
}
