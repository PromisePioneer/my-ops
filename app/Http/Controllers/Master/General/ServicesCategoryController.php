<?php

namespace App\Http\Controllers\Master\General;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\ServiceCategory\ServicesCategoryRequest;
use App\Models\ServiceCategory;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use RuntimeException;

class ServicesCategoryController extends Controller
{
    private static int $perPage = 10;

    /**
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('view', ServiceCategory::class);

        return view('pages.general-master-data.services-categories.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function data(): JsonResponse
    {
        $this->authorize('view', ServiceCategory::class);
        $services = ServiceCategory::orderBy('capacity', 'ASC')
            ->paginate(self::$perPage)
            ->onEachSide(1);

        return response()->json($services);
    }

    /**
     * @throws AuthorizationException
     */
    public function search(Request $request): JsonResponse
    {
        $this->authorize('view', ServiceCategory::class);
        $servicesCategory = ServiceCategory::where('name', 'like', '%'.$request->search.'%')
            ->orWhere('capacity', 'like', '%'.$request->search.'%')
            ->orderBy('capacity', 'ASC')
            ->limit(25)
            ->get();

        return response()->json($servicesCategory);
    }

    /**
     * @throws AuthorizationException
     */
    public function store(ServicesCategoryRequest $request): JsonResponse
    {
        $this->authorize('create', ServiceCategory::class);
        $services = ServiceCategory::create($request->validated());

        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function show(ServiceCategory $serviceCategory): JsonResponse
    {
        $this->authorize('update', ServiceCategory::class);

        return response()->json($serviceCategory);
    }

    /**
     * @throws AuthorizationException
     */
    public function update(ServicesCategoryRequest $request, ServiceCategory $serviceCategory): JsonResponse
    {
        $this->authorize('update', ServiceCategory::class);
        $serviceCategory->update($request->validated());

        return response()->json([
            'message' => 'data berhasil di update',
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(Request $request): JsonResponse
    {
        $this->authorize('delete', ServiceCategory::class);
        $cabangId = $request->only('data');

        $convertToString = implode(',', $cabangId['data']);
        $integerIDs = array_map('intval', explode(',', $convertToString));

        foreach ($integerIDs as $id) {
            $users = DB::table('users')->whereIn('id', $integerIDs)->get();
            foreach ($users as $user) {
                if ($user->id === $id) {
                    throw new RuntimeException('Tidak dapat menghapus branch yang memiliki user');
                }
            }
        }
        $services = ServiceCategory::whereIn('id', $integerIDs)->delete();
        return response()->json([
            'message' => 'data berhasil di hapus',
            'data' => $services,
        ]);
    }
}
