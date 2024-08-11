<?php

namespace App\Http\Controllers\ManageUser;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\FamilyInformationRequest;
use App\Models\FamilyInformation;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class FamilyInformationController extends Controller
{

    private FamilyInformation $familyInformation;

    public function __construct()
    {
        $this->familyInformation = new FamilyInformation();
    }

    public function getRelatedFamilyInformation(User $user): JsonResponse
    {
        return response()->json($this->familyInformation->getRelatedUserFamilyInformation($user->id));
    }

    public function updateOrCreate(FamilyInformationRequest $request, User $user): JsonResponse
    {
        $currentJobInfoId = FamilyInformation::where('user_id', $user->id)->first();
        FamilyInformation::updateOrCreate([
            'user_id' => $user->id,
        ], [
            'partner_name' => $request->partner_name,
            'family_dependents' => $request->family_dependents,
        ]);

        return response()->json([
            'message' => 'data berhasil disimpan'
        ]);
    }
}
