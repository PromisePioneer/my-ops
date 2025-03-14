<?php

namespace App\Http\Controllers\Accounting\Asset;

use App\Http\Controllers\Controller;
use App\Support\Assets\AssetDepreciationService;
use Illuminate\Http\JsonResponse;

class AssetDepreciationController extends Controller
{
    private AssetDepreciationService $assetDepreciationService;

    public function __construct()
    {
        $this->assetDepreciationService = new AssetDepreciationService();
    }


    public function data(): JsonResponse
    {
        return response()->json($this->assetDepreciationService->data());
    }
}
