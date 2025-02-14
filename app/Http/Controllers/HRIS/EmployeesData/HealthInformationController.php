<?php

namespace App\Http\Controllers\HRIS\EmployeesData;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\HealthInformationRequest;
use App\Models\HealthInformation;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class HealthInformationController extends Controller
{
    private HealthInformation $healthInformation;

    public function __construct()
    {
        $this->healthInformation = new HealthInformation();
    }

    public function getRelatedUserHealthInformation(User $user): JsonResponse
    {
        $health = HealthInformation::with('user')
            ->where('user_id', $user->id)
            ->first();

        return response()->json([
            'disease' => json_decode($health?->disease),
        ]);
    }

    public function updateOrCreate(HealthInformationRequest $request, User $user): JsonResponse
    {
        HealthInformation::updateOrCreate([
            'user_id' => $user->id,
        ], [
            'disease' => json_encode($request['data']),
        ]);

        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }
}
