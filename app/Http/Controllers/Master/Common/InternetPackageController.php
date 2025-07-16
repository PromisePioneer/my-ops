<?php

namespace App\Http\Controllers\Master\Common;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\InternetPackageRequest;
use App\Models\InternetPackage;
use App\Models\Master\Common\Branch;
use App\Support\Master\Common\BroadbandPacket\InternetPackageService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

#[AllowDynamicProperties] class InternetPackageController extends Controller
{
    public function __construct()
    {
        $this->branch = new Branch();
        $this->internetPackage = new InternetPackage();
        $this->internetPackageService = new InternetPackageService();
    }

    /**
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('view', InternetPackage::class);
        return view('pages.master.common.internet-packages.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function data(Request $request): JsonResponse
    {
        $this->authorize('view', InternetPackage::class);
        return response()->json($this->internetPackageService->data($request));
    }

    /**
     * @throws AuthorizationException
     */
    public function search(Request $request): JsonResponse
    {
        $this->authorize('view', InternetPackage::class);
        return response()->json($this->internetPackageService->search($request));
    }


    /**
     * @throws AuthorizationException
     */
    public function store(InternetPackageRequest $request): JsonResponse
    {
        $this->authorize('create', InternetPackage::class);
        $this->internetPackage->query()->create([
            'company_id' => $request->session()->get('company_session'),
            'name' => $request->name,
            'capacity' => $request->capacity,
            'price' => $request->price,
        ]);
        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }


    /**
     * @throws AuthorizationException
     */
    public function edit(InternetPackage $internetPackage): JsonResponse
    {
        $this->authorize('update', $internetPackage);
        return response()->json($internetPackage);
    }


    /**
     * @throws AuthorizationException
     */
    public function update(InternetPackageRequest $request, InternetPackage $internetPackage): JsonResponse
    {
        $this->authorize('update', $internetPackage);
        $internetPackage->update([
            'company_id' => $request->session()->get('company_session'),
            'name' => $request->name,
            'capacity' => $request->capacity,
            'price' => $request->price,
        ]);
        return response()->json([
            'message' => 'data berhasil disimpan',
        ]);
    }


    /**
     * @throws AuthorizationException
     */
    public function destroy(Request $request, InternetPackage $internetPackage): JsonResponse
    {
        $this->authorize('delete', $internetPackage);
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $internetPackage->whereIn('id', $explodeID)->delete();

        return response()->json([
            'message' => 'data berhasil dihapus',
        ]);
    }

}
