<?php

namespace App\Http\Controllers\Master\Accounting\Asset;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\AssetRequest;
use App\Models\Account;
use App\Models\Asset;
use App\Support\Master\Accounting\Assets\Service\AssetDepreciationService;
use App\Support\Master\Accounting\Assets\Service\AssetService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

#[AllowDynamicProperties] class AssetController extends Controller
{

    public function __construct()
    {
        $this->assetService = new AssetService();
        $this->account = new Account();
        $this->assetDepreciationService = new AssetDepreciationService();
    }


    /**
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('view', Asset::class);
        return view('pages.master.accounting.assets.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function search(Request $request): JsonResponse
    {
        $this->authorize('view', Asset::class);
        return response()->json($this->assetService->search($request));
    }


    /**
     * @throws AuthorizationException
     */
    public function filter(Request $request): JsonResponse
    {
        $this->authorize('filterByBranch', Asset::class);
        $this->authorize('view', Asset::class);
        return response()->json($this->assetService->filter($request));
    }

    public function create(): View
    {
        return view('pages.master.accounting.assets.form');
    }

    /**
     * @throws AuthorizationException
     */
    public function getDebitAccount(Request $request): JsonResponse
    {
        $this->authorize('create', Asset::class);
        $this->authorize('update', Asset::class);
        return response()->json($this->account->getAssetAccount($request));
    }


    /**
     * @throws AuthorizationException
     */
    public function getCreditAccount(Request $request): JsonResponse
    {
        $this->authorize('create', Asset::class);
        $this->authorize('update', Asset::class);
        return response()->json($this->account->getKasAccount($request));
    }

    /**
     * @throws AuthorizationException
     */
    public function selectedAccount(Asset $asset): JsonResponse
    {
        $this->authorize('update', $asset);
        return response()->json($this->account->getSelectedAccount($asset->account_id));
    }

    /**
     * @throws Throwable
     */
    public function store(AssetRequest $request): JsonResponse
    {
        $this->authorize('create', Asset::class);
        $this->assetService->store($request);
        return response()->json(['message' => 'Data berhasil ditambahkan.']);
    }

    /**
     * @throws AuthorizationException
     */
    public function edit(Asset $asset): View
    {
        $asset->load('item', 'branch');
        return view('pages.master.accounting.assets.form', compact('asset'));
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(Request $request, Asset $asset): JsonResponse
    {
        $this->authorize('delete', $asset);
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $asset->whereIn('id', $explodeID)->delete();

        return response()->json(['message' => 'Data berhasil dihapus.']);
    }

    /**
     * @throws Throwable
     */
    public function confirm(Asset $asset): JsonResponse
    {
        $this->authorize('confirm', $asset);
        $this->assetService->confirm($asset);
        return response()->json(['message' => 'Data berhasil dikonfirmasi.']);
    }

    /**
     * @throws AuthorizationException
     */
    public function update(AssetRequest $request, Asset $asset): JsonResponse
    {
        $this->authorize('update', $asset);
        $this->assetService->update($request, $asset);
        return response()->json(['message' => 'Data berhasil diubah.']);
    }

    /**
     * @throws AuthorizationException
     */
    public function detail(Asset $asset): View
    {
        $this->authorize('viewDetail', $asset);
        return view('pages.master.accounting.assets.detail', compact('asset'));
    }

    /**
     * @throws AuthorizationException
     */
    public function data(): JsonResponse
    {
        $this->authorize('view', Asset::class);
        return response()->json($this->assetService->data());
    }


    public function depreciationData(Asset $asset): JsonResponse
    {
        return response()->json($this->assetDepreciationService->data($asset));
    }
}
