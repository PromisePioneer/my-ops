<?php

namespace App\Http\Controllers\Area;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserHasAreaRequest;
use App\Models\Area;
use App\Models\Branch;
use App\Models\User;
use App\Models\UserHasArea;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AreaDetailController extends Controller
{

    public function data(Area $area): JsonResponse
    {
        $area = UserHasArea::with('user', 'user.roles')->whereHas('area', function ($query) use ($area) {
            $query->where('area_id', $area->id);
        })->paginate(10);
        return response()->json($area);
    }


    public function search(Request $request, Area $area): JsonResponse
    {
        $search = $request->search;
        $area = UserHasArea::with('user', 'user.roles')
            ->whereHas('area', function ($query) use ($request, $area) {
                $query->where('area_id', $area->id);
            })->when(!empty($search), function ($query) use ($search) {
                $query->whereHas('user', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                });
            })->paginate(10);

        return response()->json($area);
    }

    public function getUser(Request $request, Area $area): array
    {
        $search = $request->search;

        $branch = Branch::where('id', $area->branch_id)->first();

        $query = User::with('roles')->whereDoesntHave('userHasArea')
            ->whereHas('roles', function ($query) use ($area) {
                $query->whereIn('name', ['Head Engineer', 'Engineer']);
            })
            ->where('branch_id', $area->branch_id)
            ->where('active', 1)
            ->orderBy('name')
            ->select('id', 'name', 'nip');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('nip', 'like', '%' . $search . '%');
            });
        }

        $users = $query->get();

        return $users->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => '(' . $item->nip . ')' . ' ' . '(' . $item->roles->pluck('name')->implode(', ') . ')' . ' ' . $item->name,
            ];
        })->toArray();
    }


    public function assignUser(UserHasAreaRequest $request, Area $area): JsonResponse
    {
        UserHasArea::updateOrCreate([
            'user_id' => $request->user_id
        ], [
            'area_id' => $area->id,
        ]);
        return response()->json(['message' => 'Data berhasil disimpan / diubah.']);
    }


    public function destroy(Request $request, UserHasArea $userHasArea): JsonResponse
    {

        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $userHasArea->whereIn('id', $explodeID)->delete();

        return response()->json([
            'message' => 'data berhasil dihapus',
        ], 200);
    }
}
