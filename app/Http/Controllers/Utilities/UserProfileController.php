<?php

namespace App\Http\Controllers\Utilities;

use App\Http\Controllers\Controller;
use App\Http\Requests\Utilities\UserProfile\UpdatePasswordRequest;
use App\Http\Requests\Utilities\UserProfile\UserProfileRequest;
use App\Models\User;
use App\Models\UserIdentityInformation;
use App\Models\UserJobInformation;
use App\Service\HandleFileUploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserProfileController extends Controller
{
    private UserIdentityInformation $identityInformation;

    private UserJobInformation $jobInformation;

    private HandleFileUploadService $handleFileUpload;

    public function __construct()
    {
        $this->identityInformation = new UserIdentityInformation;
        $this->jobInformation = new UserJobInformation;
        $this->handleFileUpload = new HandleFileUploadService;
    }

    public function index()
    {
        return view('pages.utilities.user-profile.index');
    }

    public function notificationDetail(): View
    {
        return view('pages.utilities.user-profile.notification-detail');
    }

    public function changeProfile(): View
    {
        return view('pages.utilities.user-profile.change-profile');
    }

    public function updatePassword(UpdatePasswordRequest $request, User $user): JsonResponse
    {
        $user->update([
            'password' => Hash::make($request->get('password')),
        ]);

        return response()->json(['message' => 'Password telah di update.']);
    }

    public function updateProfilePic(UserProfileRequest $request, User $user): JsonResponse
    {
        $user->update([
            'profile_pic' => $this->handleFileUpload->upload($request, 'profile_pic', $user->profile_pic),
        ]);

        return response()->json(['message' => 'foto profile telah di update']);
    }

    public function identityInformationPage(): View
    {
        return view('pages.utilities.user-profile.identity-information.index');
    }

    public function identityInformation(Request $request): JsonResponse
    {
        return response()->json($this->identityInformation->getRelatedUserIdentityInformation($request->user()->id));
    }

    public function jobInformationPage(): View
    {
        return view('pages.utilities.user-profile.job-information.index');
    }

    public function jobInformation(Request $request): JsonResponse
    {
        return response()->json($this->jobInformation->getRelatedUserJobInformation($request->user()->id));
    }
}
