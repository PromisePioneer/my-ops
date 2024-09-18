<?php

namespace App\Http\Controllers\ManageUser;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\EducationRequest;
use App\Models\Education;
use App\Models\User;
use App\Service\HelperService\HandleFileUploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Throwable;

class EducationController extends Controller
{
    private Education $education;

    private HandleFileUploadService $handleUploadService;

    public function __construct()
    {
        $this->education = new Education();
        $this->handleUploadService = new HandleFileUploadService();
    }

    /**
     * @throws Throwable
     */
    public function update(EducationRequest $request, User $user): JsonResponse
    {
        $currentUserEducation = $this->education->getRelatedUserEducation($user->id);
        Education::updateOrCreate([
            'user_id' => $user->id,
        ], [
            'level' => $request->level,
            'institution' => $request->institution,
            'major' => $request->major,
            'graduation_year' => $request->graduation_year,
            'gpa' => $request->gpa,
            'certificate_of_graduation' => $this->handleUploadService->upload(
                $request,
                'documents/certificate_of_graduation',
                'certificate_of_graduation',
                $currentUserEducation?->certificate_of_graduation
            ),
        ]);

        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }

    public function getRelatedUserEducation(User $user): JsonResponse
    {
        return response()->json($this->education->getRelatedUserEducation($user->id));
    }

    public function viewFile(User $user): View
    {
        $user = Education::where('user_id', $user->id)->first();

        return view('pages.manage-users.user.partials.education-and-experiences.education.view-file', compact('user'));
    }
}
