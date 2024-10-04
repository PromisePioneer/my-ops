<?php

namespace App\Http\Controllers\HRIS\EmployeesData;

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
        $family = $this->familyInformation->getRelatedUserFamilyInformation($user->id);

        return response()->json([
            'partner_name' => $this->familyInformation->getRelatedUserFamilyInformation($user->id),
            'child' => json_decode($family?->child),
        ]);
    }

    public function updateOrCreate(FamilyInformationRequest $request, User $user): JsonResponse
    {
        FamilyInformation::updateOrCreate([
            'user_id' => $user->id,
        ], [
            'partner_name' => $request->partner_name,
            'child' => json_encode($request['data']),
        ]);

        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }
}
