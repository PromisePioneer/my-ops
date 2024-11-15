<?php

namespace App\Http\Controllers;

use App\Http\Requests\BaaRequest;
use App\Models\BAA;
use App\Models\Fab;
use App\Service\BAAService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BAAController extends Controller
{


    private BAAService $baaService;
    private Fab $fab;

    public function __construct()
    {
        $this->baaService = new BAAService();
        $this->fab = new Fab();
    }

    public function index(): View
    {
        return view('pages.transaction.baa.index');
    }


    public function data(): JsonResponse
    {
        return response()->json($this->baaService->data());
    }

    public function search(Request $request): JsonResponse
    {
        return response()->json($this->baaService->search($request));
    }


    public function getFabData(Request $request): JsonResponse
    {
        return response()->json($this->fab->getData($request));
    }


    public function create(): View
    {
        return view('pages.transaction.baa.create');
    }


    public function store(BaaRequest $request): JsonResponse
    {
        $this->baaService->store($request);
        return response()->json(['message' => 'data berhasil disimpan']);
    }

    public function update(BaaRequest $request, Baa $baa): JsonResponse
    {
        $this->baaService->update($request, $baa);
        return response()->json(['message' => 'data berhasil disimpan']);
    }


    public function destroy(Request $request, BAA $baa): JsonResponse
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $baa->whereIn('id', $explodeID)->delete();

        return response()->json([
            'message' => 'data berhasil dihapus',
        ], 200);
    }
}
