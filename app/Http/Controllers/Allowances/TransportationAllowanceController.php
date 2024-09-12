<?php

namespace App\Http\Controllers\Allowances;

use App\Http\Controllers\Controller;
use App\Http\Requests\Allowances\TransportationAllowanceRequest;
use App\Models\User;
use App\Models\UserHasTransportationAllowance;
use App\Service\HandleFileUploadService;
use App\Service\TransportationAllowanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TransportationAllowanceController extends Controller
{

    private User $user;
    private TransportationAllowanceService $transportationAllowanceService;
    private HandleFileUploadService $handleFileUploadService;

    public function __construct()
    {
        $this->user = new User();
        $this->transportationAllowanceService = new TransportationAllowanceService();
        $this->handleFileUploadService = new HandleFileUploadService();
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
        foreach ($request->user_id as $userId) {
            UserHasTransportationAllowance::create([
                'user_id' => $userId,
                'date' => $request->date,
                'transportation_type' => $request->transportation_type,
                'spk_image' => $this->handleFileUploadService->upload(
                    $request,
                    'documents/user/spk-image',
                    'spk_image',
                ),
                'amount' => $request->transportation_type === 'Dibawah 15 Km' ? 10000 : 12500,
            ]);
        }
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
