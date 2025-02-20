<?php

namespace App\Http\Controllers\Master\General;

use App\Http\Controllers\Controller;
use App\Http\Requests\SKLRequest;
use App\Models\SKL;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\JsonResponse;

class SKLController extends Controller
{
    private static int $perPage = 10;

    /**
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('view', SKL::class);
        return view('pages.general-master-data.skl.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function data(): JsonResponse
    {
        $this->authorize('view', SKL::class);
        $data = SKL::orderBy('name')->paginate(self::$perPage);
        return response()->json($data);
    }

    /**
     * @throws AuthorizationException
     */
    public function search(Request $request): JsonResponse
    {
        $this->authorize('view', SKL::class);
        $search = $request->input('search');
        $data = SKL::search($search)->query(function ($query) {
            $query->orderBy('name');
        })->paginate(self::$perPage);
        return response()->json($data);
    }


    /**
     * @throws AuthorizationException
     */
    public function store(SKLRequest $request): JsonResponse
    {
        $this->authorize('create', SKL::class);
        SKL::create($request->validated());
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
        $skl->update($request->validated());
        return response()->json(['message' => 'Data berhasil disimpan.']);
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(Request $request, SKL $skl): JsonResponse
    {
        $this->authorize('delete', $skl);
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $skl->whereIn('id', $explodeID)->delete();

        return response()->json([
            'message' => 'data berhasil dihapus',
        ]);
    }
}
