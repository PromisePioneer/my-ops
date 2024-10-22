<?php

namespace App\Http\Controllers\Master\Finance;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaxSettingRequest;
use App\Models\TaxSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaxSettingController extends Controller
{
    public function index(): View
    {
        return view('pages.finance-master-data.tax-settings.index');
    }

    public function data(): JsonResponse
    {
        return response()->json(TaxSetting::paginate(10));
    }

    public function search(Request $request): JsonResponse
    {
        $query = TaxSetting::query();
        $search = $request->input('search');


        if (!empty($search)) {
            $query->where('name', 'like', '%'.$search.'%')
                ->orWhere('percentage', 'like', '%'.$search.'%');
        }

        $data = $query->paginate(10);
        return response()->json($data);
    }

    public function store(TaxSettingRequest $request): JsonResponse
    {
        TaxSetting::create($request->validated());
        return response()->json(['message' => 'Data berhasil disimpan']);
    }


    public function edit(TaxSetting $taxSetting)
    {
        return response()->json($taxSetting);
    }


    public function update(TaxSettingRequest $request, TaxSetting $taxSetting): JsonResponse
    {
        $taxSetting->update($request->validated());
        return response()->json(['message' => 'Data berhasil disimpan']);
    }

    public function destroy(Request $request, TaxSetting $taxSetting): JsonResponse
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $taxSetting->whereIn('id', $explodeID)->delete();

        return response()->json(['message' => 'Data berhasil dihapus']);
    }
}
