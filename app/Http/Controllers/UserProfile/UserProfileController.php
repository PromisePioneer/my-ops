<?php

namespace App\Http\Controllers\UserProfile;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\Utilities\UserProfile\UpdatePasswordRequest;
use App\Http\Requests\Utilities\UserProfile\UserProfileRequest;
use App\Models\IdentityInformation;
use App\Models\JobInformation;
use App\Models\SP;
use App\Models\User;
use App\Support\HelperService\HandleFileUploadService;
use App\Support\User\SP\SPService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

#[AllowDynamicProperties] class UserProfileController extends Controller
{
    public function __construct()
    {
        $this->identityInformation = new IdentityInformation();
        $this->jobInformation = new JobInformation();
        $this->handleFileUpload = new HandleFileUploadService();
        $this->sp = new SP();
        $this->SPService = new SPService();
    }

    public function index(): View
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
            'profile_pic' => $this->handleFileUpload->upload($request, 'profile_pic', 'profile_pic', $user?->profile_pic),
        ]);

        return response()->json(['message' => 'foto profile telah di update']);
    }


    public function identityInformation(Request $request): JsonResponse
    {
        $identityInformation = IdentityInformation::where('user_id', $request->user()->id)->first();
        return response()->json($identityInformation);
    }


    public function jobInformation(Request $request): JsonResponse
    {
        return response()->json($this->jobInformation->getRelatedUserJobInformation($request->user()->id));
    }

    public function spPage(): View
    {
        return view('pages.utilities.user-profile.sp.index');
    }

    public function spData(Request $request): JsonResponse
    {
        return response()->json($this->SPService->getOwnSP($request));
    }

}
