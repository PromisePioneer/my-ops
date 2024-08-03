<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\UserPlacement\UserPlacementRequest;
use App\Models\UserPlacement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserPlacementController extends Controller
{
    public function index(): View
    {
        return view('pages.master.user-placement.index');
    }

    public function data(): JsonResponse
    {
        $placement = UserPlacement::paginate(10);
        return response()->json($placement);
    }

    public function search(Request $request): JsonResponse
    {
        $search = $request->input('search');
        $searchQuery = UserPlacement::where('name', 'like', '%' . $search . '%')->orWhere('code', 'like', '%' . $search . '%')->get();
        return response()->json($searchQuery);
    }

    public function store(UserPlacementRequest $request): JsonResponse
    {
        UserPlacement::create($request->validated());
        return response()->json([
            'message' => 'data berhasil disimpan'
        ]);
    }

    public function edit(UserPlacement $userPlacement): JsonResponse
    {
        return response()->json($userPlacement);
    }

    public function update(UserPlacementRequest $request, UserPlacement $userPlacement): JsonResponse
    {
        $userPlacement->update($request->validated());
        return response()->json([
            'message' => 'data berhasil disimpan'
        ]);
    }

    public function destroy(UserPlacement $userPlacement): JsonResponse
    {
        $userPlacement->delete();
        return response()->json([
            'message' => 'data berhasil dihapus'
        ]);
    }
}
