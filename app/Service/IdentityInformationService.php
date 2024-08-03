<?php

namespace App\Service;

use App\Http\Requests\User\IdentityInformationRequest;
use App\Models\User;
use App\Models\UserIdentityInformation;

class IdentityInformationService
{
    private HandleFileUploadService $handleFileUploadService;

    public function __construct()
    {
        $this->handleFileUploadService = new HandleFileUploadService();
    }
    public function update(IdentityInformationRequest $request, User $user): void
    {
        $userIdentityInfoId = UserIdentityInformation::where('user_id', $user->id)->first();
        UserIdentityInformation::updateOrCreate([
            'user_id' => $user->id,
        ], [
            'nik' => $request->nik,
            'date_of_birth' => $request->date_of_birth,
            'place_of_birth' => $request->place_of_birth,
            'gender' => $request->gender,
            'home_address' => $request->home_address,
            'ktp_attachment' => $this->handleFileUploadService->upload($request, 'documents/user/ktp', 'ktp_attachment', $userIdentityInfoId ? $userIdentityInfoId->sk_file : 'null'),
            'married_status' => $request->married_status
        ]);
    }
}
