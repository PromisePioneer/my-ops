<?php

namespace App\Http\Controllers\Operational\Pole;

use App\Http\Controllers\Controller;
use App\Http\Requests\PoleRequest;
use App\Models\Pole;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PoleController extends Controller
{
    public function index(): View
    {
        return view('pages.operational.poles.index');
    }

    public function data(): JsonResponse
    {
        $poles = Pole::paginate(10);
        return response()->json($poles);
    }

    public function search(): JsonResponse
    {
    }

    public function store(PoleRequest $request): JsonResponse
    {
        Pole::create($request->validated());

        return response()->json(['message' => 'Data berhasil disimpan.']);
    }


    public function edit(Pole $pole): JsonResponse
    {
        return response()->json($pole);
    }

    public function update(PoleRequest $request, Pole $pole): JsonResponse
    {
        $pole->update($request->validated());
        return response()->json(['message' => 'Data berhasil disimpan.']);
    }

    public function destroy(Request $request, Pole $pole): JsonResponse
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $pole->whereIn('id', $explodeID)->delete();

        return response()->json(['message' => 'data berhasil dihapus']);
    }
}
