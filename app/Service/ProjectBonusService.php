<?php

namespace App\Service;

use App\Http\Requests\ProjectBonusRequest;
use App\Models\ProjectBonus;
use App\Models\User;
use App\Models\UserHasProjectBonus;
use App\Service\HelperService\HandleFileUploadService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Throwable;

class ProjectBonusService
{
    private static int $perPage = 10;

    private ProjectBonus $projectBonus;
    private HandleFileUploadService $handleFileUploadService;

    public function __construct()
    {
        $this->projectBonus = new ProjectBonus();
        $this->handleFileUploadService = new HandleFileUploadService();
    }

    public function data(): LengthAwarePaginator
    {
        return ProjectBonus::with('userHasProjectBonus', 'userHasProjectBonus.user')
            ->orderBy('id', 'desc')
            ->paginate(self::$perPage);
    }

    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $query = $this->projectBonus->with('user')
            ->orderBy('', 'desc');

        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->whereHas('user', function ($query) use ($search) {
                    $query->where('name', 'like', '%'.$search.'%');
                });
            });
        }

        $data = $query->paginate(self::$perPage);
        return self::formattedData($data);
    }

    public function formattedData(LengthAwarePaginator $projectBonus): LengthAwarePaginator
    {
        $data = $projectBonus->getCollection()->map(function ($projectBonus) {
            return [
                'id' => $projectBonus->id,
                'date_active' => $projectBonus->date_active,
                'customer_name' => $projectBonus->customer_name,
                'bast' => $projectBonus->bast,
                'baa' => $projectBonus->baa,
                'description' => $projectBonus->work_description,
            ];
        });

        $projectBonus->setCollection($data);
        return $projectBonus;
    }

    public function store(ProjectBonusRequest $request): void
    {
        DB::transaction(function () use ($request) {
            $data = $request->validated();
            $data['baa'] = $this->handleFileUploadService->upload($request, 'documents/project-bonus/baa', 'baa');
            $data['bast'] = $this->handleFileUploadService->upload($request, 'documents/project-bonus/bast', 'bast');
            $projectBonus = ProjectBonus::create($data);
            $this->userHasBonusProjectUpdateOrCreate($request, $projectBonus);
        });
    }

    public function userHasBonusProjectUpdateOrCreate($request, $projectBonus): void
    {
        foreach ($request['data'] as $key => $value) {
            $value['project_bonus_id'] = $projectBonus->id;
            UserHasProjectBonus::create($value);
        }
    }


    public function getUserhasProjectBonus(ProjectBonus $projectBonus): Collection
    {
        return UserHasProjectBonus::with('user')->where('project_bonus_id', $projectBonus->id)->get();
    }

    public function getSelectedProjectBonus(User $projectBonus)
    {
        return UserHasProjectBonus::with('user')
            ->where('user_id', $projectBonus->id)
            ->first();
    }


    /**
     * @throws Throwable
     */
    public function update(ProjectBonusRequest $request, ProjectBonus $projectBonus): void
    {
        DB::transaction(function () use ($request, $projectBonus) {
            $data = $request->validated();
            $data['baa'] = $this->handleFileUploadService->upload(
                $request,
                'documents/project-bonus/baa',
                'baa',
                $projectBonus->baa
            );
            $data['bast'] = $this->handleFileUploadService->upload(
                $request,
                'documents/project-bonus/bast',
                'bast',
                $projectBonus->bast
            );
            $projectBonus->update($data);
            $this->userHasBonusProjectUpdateOrCreate($request, $projectBonus);
        });
    }

    public function destroy(Request $request, ProjectBonus $projectBonus)
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        return $projectBonus->whereIn('id', $explodeID)->delete();
    }
}