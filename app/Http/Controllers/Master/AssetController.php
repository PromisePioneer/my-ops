<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssetRequest;
use App\Models\Account;
use App\Models\Asset;
use App\Models\Branch;
use App\Service\Assets\AssetDepreciationService;
use App\Service\Master\AssetService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class AssetController extends Controller
{

    private AssetService $assetService;
    private Branch $branch;
    private Account $account;
    private AssetDepreciationService $assetDepreciationService;

    public function __construct()
    {
        $this->assetService = new AssetService();
        $this->branch = new Branch();
        $this->account = new Account();
        $this->assetDepreciationService = new AssetDepreciationService();
    }


    public function index(): View
    {
        return view('pages.master.assets.index');
    }

    public function search(Request $request): JsonResponse
    {
        return response()->json($this->assetService->search($request));
    }

    public function getBranchData(Request $request): JsonResponse
    {
        return response()->json($this->branch->getData($request));
    }

    public function getAccountData(Request $request): JsonResponse
    {
        return response()->json($this->account->getAssetAccount($request));
    }

    public function selectedBranch(Asset $asset): JsonResponse
    {
        return response()->json($this->branch->getSelectedData($asset->branch_id));
    }

    public function selectedAccount(Asset $asset): JsonResponse
    {
        return response()->json($this->account->getSelectedAccount($asset->account_id));
    }

    /**
     * @throws Throwable
     */
    public function store(AssetRequest $request): JsonResponse
    {
        $this->assetService->store($request);
        return response()->json(['message' => 'Data berhasil ditambahkan.']);
    }

    public function edit(Asset $asset): JsonResponse
    {
        return response()->json($asset);
    }

    public function destroy(Request $request, Asset $asset): JsonResponse
    {
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
        $this->assetService->confirm($asset);
        return response()->json(['message' => 'Data berhasil dikonfirmasi.']);
    }

    public function update(AssetRequest $request, Asset $asset): JsonResponse
    {
        $this->assetService->update($request, $asset);
        return response()->json(['message' => 'Data berhasil diubah.']);
    }

    public function detail(Asset $asset): View
    {
        return view('pages.master.assets.detail', compact('asset'));
    }

    public function getDetailData(Asset $asset): JsonResponse
    {
        return response()->json($this->assetDepreciationService->data($asset));
    }

    public function data(Request $request): JsonResponse
    {
        return response()->json($this->assetService->data($request));
    }
}
