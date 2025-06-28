<?php

namespace App\Http\Controllers\Master\Common;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\SKLRequest;
use App\Models\Master\Common\SKL;
use App\Support\Master\Common\SKL\Service\SKLService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\JsonResponse;

#[AllowDynamicProperties] class SKLController extends Controller
{


    public function __construct()
    {
        $this->sklService = new SKLService();
    }

    /**
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('view', SKL::class);
        return view('pages.master.common.skl.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function data(): JsonResponse
    {
        $this->authorize('view', SKL::class);
        return response()->json($this->sklService->data());
    }

    /**
     * @throws AuthorizationException
     */
    public function search(Request $request): JsonResponse
    {
        $this->authorize('view', SKL::class);
        return response()->json($this->sklService->search($request));
    }


    /**
     * @throws AuthorizationException
     */
    public function store(SKLRequest $request): JsonResponse
    {
        $this->authorize('create', SKL::class);
        $this->sklService->store($request);
        return response()->json(['message' => 'Data berhasil disimpan.']);
    }

    /**
     * @throws AuthorizationException
     */
    public function edit(SKL $skl): JsonResponse
    {
        $this->authorize('update', $skl);
        return response()->json($skl);
    }


    /**
     * @throws AuthorizationException
     */
    public function update(SKLRequest $request, SKL $skl): JsonResponse
    {
        $this->authorize('update', $skl);
        return response()->json(['message' => 'Data berhasil disimpan.']);
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(Request $request, SKL $skl): JsonResponse
    {
        $this->authorize('delete', $skl);
        $this->sklService->destroy($request, $skl);
        return response()->json([
            'message' => 'data berhasil dihapus',
        ]);
    }


    public function getSKL(Request $request): array
    {
        $search = $request->input('search');
        $skl = SKL::search($search)->query(function ($query) {
            $query->orderby('name', 'asc');
        })->get();

        return $skl->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->name,
            ];
        })->toArray();
    }


    public function selectedSKL(SKL $skl): array
    {
        return [
            'id' => $skl->id,
            'text' => $skl->name,
        ];
    }

    public function archives(): View
    {
        $this->authorize('viewArchives', SKL::class);
        return view('pages.master.common.skl.archives');
    }


    public function archivedData(): JsonResponse
    {
        $this->authorize('viewArchives', SKL::class);
        return response()->json($this->sklService->archivedData());
    }


    public function searchArchivedData(Request $request): JsonResponse
    {
        $this->authorize('viewArchives', SKL::class);
        return response()->json($this->sklService->archivedSearch($request));
    }


    public function restore(Request $request, SKL $skl): JsonResponse
    {
        $this->authorize('viewArchives', $skl);
        $this->sklService->restore($request, $skl);
        return response()->json(['message' => 'Data berhasil disimpan.']);
    }


    public function forceDelete(Request $request, SKL $skl): JsonResponse
    {

        $this->authorize('viewArchives', $skl);
        $this->sklService->forceDelete($request, $skl);
        return response()->json(['message' => 'Data berhasil dihapus secara permanen.']);
    }
}
