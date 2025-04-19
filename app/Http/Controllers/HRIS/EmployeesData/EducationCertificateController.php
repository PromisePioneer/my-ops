<?php

namespace App\Http\Controllers\HRIS\EmployeesData;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\EducationCertificateRequest;
use App\Models\EducationCertificate;
use App\Models\User;
use App\Support\HelperService\HandleFileUploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class EducationCertificateController extends Controller
{
    public readonly int $perPage;

    private EducationCertificate $educationCertificate;

    private HandleFileUploadService $handleUploadService;

    public function __construct()
    {
        $this->perPage = 10;
        $this->educationCertificate = new EducationCertificate();
        $this->handleUploadService = new HandleFileUploadService();
    }

    public function getEducationCertificate(Request $request): JsonResponse
    {
        $edu = EducationCertificate::with('user')
            ->where('user_id', $request->user()->id)
            ->get();

        return response()->json($edu);
    }

    public function store(EducationCertificateRequest $request, User $user): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = $user->id;
        $data['file'] = $this->handleUploadService->upload($request, 'documents/certificates/', 'file') ?? null;
        EducationCertificate::create($data);

        return response()->json([
            'message' => 'Data berhasil disimpan',
        ]);
    }

    public function edit(EducationCertificate $educationCertificate): JsonResponse
    {
        return response()->json($educationCertificate);
    }

    public function update(
        EducationCertificateRequest $request,
        EducationCertificate $educationCertificate
    ): JsonResponse {
        $data = $request->validated();
        $educationCertificate->update($data);

        return response()->json([
            'message' => 'Data berhasil disimpan',
        ]);
    }

    public function destroy(EducationCertificate $educationCertificate): JsonResponse
    {
        Storage::disk('public')->delete($educationCertificate->file);
        $educationCertificate->delete();

        return response()->json([
            'message' => 'Data berhasil dihapus',
        ]);
    }

    public function viewFile(EducationCertificate $educationCertificate): View
    {
        return view(
            'pages.manage-users.user.partials.education-and-experiences.education-certificates.view-file',
            compact('educationCertificate')
        );
    }
}
