<?php

namespace App\Http\Controllers\Master\Accounting;

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
        return view('pages.master.accounting.tax-settings.index');
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
        $search = $request->input('search');
        $query = TaxSetting::search($search)->query(function ($query) {
            $query->orderBy('name');
        })->paginate(10);
        return response()->json($query);
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
    public function edit(TaxSetting $taxSetting): JsonResponse
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
