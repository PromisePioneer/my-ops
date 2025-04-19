<?php

namespace App\Http\Controllers\HRIS\EmployeesData;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\IdentityInformationRequest;
use App\Models\IdentityInformation;
use App\Models\User;
use App\Support\User\IdentityInformationService;
use Illuminate\Http\JsonResponse;

class IdentityInformationController extends Controller
{
    private IdentityInformation $identityInformation;

    private IdentityInformationService $identityInformationService;

    public function __construct()
    {
        $this->identityInformation = new IdentityInformation();
        $this->identityInformationService = new IdentityInformationService();
    }

    public function index(User $user): JsonResponse
    {
        $identity = IdentityInformation::where('user_id', $user->id)->first();
        return response()->json($identity);
    }

    public function update(IdentityInformationRequest $request, User $user): JsonResponse
    {
        $this->identityInformationService->update($request, $user);

        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }
}
