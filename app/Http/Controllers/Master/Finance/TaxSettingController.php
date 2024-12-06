<?php

namespace App\Http\Controllers\Master\Finance;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaxSettingRequest;
use App\Models\TaxSetting;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaxSettingController extends Controller
{
    /**
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('view', TaxSetting::class);
        return view('pages.finance-master-data.tax-settings.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function data(): JsonResponse
    {
        $this->authorize('view', TaxSetting::class);
        return response()->json(TaxSetting::paginate(10));
    }

    /**
     * @throws AuthorizationException
     */
    public function search(Request $request): JsonResponse
    {
        $this->authorize('view', TaxSetting::class);
        $query = TaxSetting::query();
        $search = $request->input('search');


        if (!empty($search)) {
            $query->where('name', 'like', '%'.$search.'%')
                ->orWhere('percentage', 'like', '%'.$search.'%');
        }

        $data = $query->paginate(10);
        return response()->json($data);
    }

    /**
     * @throws AuthorizationException
     */
    public function store(TaxSettingRequest $request): JsonResponse
    {
        $this->authorize('create', TaxSetting::class);
        TaxSetting::create($request->validated());
        return response()->json(['message' => 'Data berhasil disimpan']);
    }

    /**
     * @throws AuthorizationException
     */
    public function edit(TaxSetting $taxSetting)
    {
        $this->authorize('update', $taxSetting);
        return response()->json($taxSetting);
    }

    /**
     * @throws AuthorizationException
     */
    public function update(TaxSettingRequest $request, TaxSetting $taxSetting): JsonResponse
    {
        $this->authorize('update', $taxSetting);
        $taxSetting->update($request->validated());
        return response()->json(['message' => 'Data berhasil disimpan']);
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(Request $request, TaxSetting $taxSetting): JsonResponse
    {
        $this->authorize('delete', $taxSetting);
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $taxSetting->whereIn('id', $explodeID)->delete();

        return response()->json(['message' => 'Data berhasil dihapus']);
    }
}
