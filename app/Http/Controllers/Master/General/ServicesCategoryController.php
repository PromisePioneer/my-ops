<?php

namespace App\Http\Controllers\Master\General;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\ServiceCategory\ServicesCategoryRequest;
use App\Models\ServiceCategory;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
        $services = ServiceCategory::orderBy('name')
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
        $search = $request->input('search');

        $servicesCategory = ServiceCategory::when(!empty($search), function ($query) use ($search) {
            $query->where('name', 'like', '%'.$search.'%');
        })->paginate(self::$perPage);

        return response()->json($servicesCategory);
    }

    /**
     * @throws AuthorizationException
     */
    public function store(ServicesCategoryRequest $request): JsonResponse
    {
        $this->authorize('create', ServiceCategory::class);
        ServiceCategory::create($request->validated());

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
    public function destroy(Request $request, ServiceCategory $serviceCategory): JsonResponse
    {
        $this->authorize('delete', $serviceCategory);
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $serviceCategory->whereIn('id', $explodeID)->delete();

        return response()->json([
            'message' => 'data berhasil dihapus',
        ], 200);
    }
}
