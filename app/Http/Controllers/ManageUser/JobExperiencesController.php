<?php

namespace App\Http\Controllers\ManageUser;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\JobExperienceRequest;
use App\Models\JobExperience;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class JobExperiencesController extends Controller
{
    private JobExperience $jobExperience;

    public function __construct()
    {
        $this->jobExperience = new JobExperience();
    }

    public function getRelatedUserJobExperience(User $user): JsonResponse
    {
        return response()->json($this->jobExperience->getRelatedJobExperiences($user->id));
    }


    public function store(JobExperienceRequest $request, User $user): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = $user->id;
        JobExperience::create($data);

        return response()->json([
            'message' => 'data berhasil disimpan'
        ]);
    }


    public function edit(JobExperience $jobExperience): JsonResponse
    {
        return response()->json($jobExperience);
    }

    public function update(JobExperience $jobExperience, JobExperienceRequest $request): JsonResponse
    {
        $data = $request->validated();
        $jobExperience->update($data);

        return response()->json([
            'message' => 'data berhasil disimpan'
        ]);
    }


    public function destroy(JobExperience $jobExperience): JsonResponse
    {
        $jobExperience->delete();
        return response()->json([
            'message' => 'data berhasil dihapus'
        ]);
    }
}
