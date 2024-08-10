<?php

namespace App\Http\Controllers\ManageUser;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\IdentityInformationRequest;
use App\Models\User;
use App\Models\UserIdentityInformation;
use App\Service\IdentityInformationService;
use Illuminate\Http\JsonResponse;

class IdentityInformationController extends Controller
{
    private UserIdentityInformation $identityInformation;
    private IdentityInformationService $identityInformationService;

    public function __construct()
    {
        $this->identityInformation = new UserIdentityInformation();
        $this->identityInformationService = new IdentityInformationService();
    }

    public function index(User $user): JsonResponse
    {
        return response()->json($this->identityInformation->getRelatedUserIdentityInformation($user->id));
    }

    public function update(IdentityInformationRequest $request, User $user): JsonResponse
    {
        $this->identityInformationService->update($request, $user);

        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }
}
