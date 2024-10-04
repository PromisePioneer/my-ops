<?php

namespace App\Http\Controllers\UserProfile\Utilities;

use App\Http\Controllers\Controller;
use App\Http\Requests\Utilities\CompanyProfile\CompanyProfileRequest;
use App\Models\CompanyProfile;
use Illuminate\Http\JsonResponse;

class CompanyProfileController extends Controller
{
    public function index()
    {
        $companyProfile = CompanyProfile::where('id', 1)->first();

        return view('pages.utilities.company-profile.index', compact('companyProfile'));
    }

    public function update(CompanyProfileRequest $request, CompanyProfile $companyProfile): JsonResponse
    {
        $companyProfile->update($request->validated());

        return response()->json([
            'message' => 'data berhasil disimpan',
        ], 200);
    }
}
