<?php

namespace App\Http\Controllers\Master\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\ServiceCategory\ServicesCategoryRequest;
use App\Models\Master\Common\ServiceCategory;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceCategoryManagerController extends Controller
{
    private static int $perPage = 10;

    /**
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('view', ServiceCategory::class);
        return view('pages.master.common.services-categories.index');
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
        $servicesCategory = ServiceCategory::search($search)->paginate(self::$perPage);
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


    public function getServiceCategories(Request $request)
    {
        $search = $request->input('search');
        $serviceCategories = ServiceCategory::search($search)->get();

        return $serviceCategories->map(function ($serviceCategory) {
            return [
                'id' => $serviceCategory->id,
                'text' => $serviceCategory->name
            ];
        });
    }


    public function selectedServiceCategory(ServiceCategory $serviceCategory): array
    {
        return [
            'id' => $serviceCategory->id,
            'name' => $serviceCategory->name
        ];
    }
}
