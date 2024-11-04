<?php

namespace App\Http\Controllers\Master\General;

use App\Http\Controllers\Controller;
use App\Http\Requests\OfferingLetterSKLRequest;
use App\Models\OfferingLetter;
use App\Models\SKL;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\JsonResponse;

class OfferingLetterSKLController extends Controller
{
    private static int $perPage = 10;

    public function index(): View
    {
        return view('pages.general-master-data.skl.index');
    }

    public function data(): JsonResponse
    {
        $data = SKL::orderByDesc('id')->paginate(self::$perPage);
        return response()->json($data);
    }

    public function search(Request $request): JsonResponse
    {
        $search = $request->input('search');
        $data = SKL::when(!empty($search), function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        })->paginate(self::$perPage);
        return response()->json($data);
    }


    public function store(OfferingLetterSKLRequest $request): JsonResponse
    {
        SKL::create($request->validated());
        return response()->json(['message' => 'Data berhasil disimpan.']);
    }

    public function edit(SKL $offeringLetterSKL): JsonResponse
    {
        return response()->json($offeringLetterSKL);
    }

    public function update(OfferingLetterSKLRequest $request, SKL $offeringLetterSKL): JsonResponse
    {
        $offeringLetterSKL->update($request->validated());
        return response()->json(['message' => 'Data berhasil disimpan.']);
    }

    public function destroy(Request $request, SKL $offeringLetterSKL): JsonResponse
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $offeringLetterSKL->whereIn('id', $explodeID)->delete();

        return response()->json([
            'message' => 'data berhasil dihapus',
        ]);
    }
}
