<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Http\Requests\StockMutationRequest;
use App\Models\ItemCollection;
use App\Models\StockMutation;
use App\Support\Inventory\StockManagement\StockMutation\Service\StockMutationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Spatie\Browsershot\Browsershot;
use Throwable;
use function App\Helper\formatDate;

#[AllowDynamicProperties] class StockMutationController extends Controller
{
    public function __construct()
    {
        $this->stockMutationService = new StockMutationService();
    }


    public function index(): View
    {
        return view('pages.inventory.stock-mutations.index');
    }

    public function data(): JsonResponse
    {
        return response()->json($this->stockMutationService->data());
    }


    public function search(Request $request)
    {
        return response()->json($this->stockMutationService->search($request));
    }


    public function filter(Request $request): JsonResponse
    {
        return response()->json($this->stockMutationService->filter($request));
    }

    public function create(?ItemCollection $itemCollection): View
    {
        return view('pages.inventory.stock-mutations.form', compact('itemCollection'));
    }


    /**
     * @throws Throwable
     */
    public function store(StockMutationRequest $request): JsonResponse
    {
        $this->stockMutationService->store($request);
        return response()->json(['message' => 'stock  berhasil di mutasi']);
    }


    public function show(StockMutation $stockMutation): JsonResponse
    {
        $stockMutation->load('stockMutationItems', 'stockMutationItems.stock.item', 'sender', 'receiver');
        $stockMutation->date = formatDate($stockMutation->date);
        return response()->json($stockMutation);
    }


    public function destroy(Request $request, StockMutation $stockMutation): JsonResponse
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $stockMutation->whereIn('id', $explodeID)->delete();
        return response()->json(['message' => 'data berhasil dihapus']);
    }


    /**
     * @throws Throwable
     */
    public function sendItem(StockMutation $stockMutation): JsonResponse
    {
        $this->stockMutationService->sendItem($stockMutation);
        return response()->json(['message' => 'item berhasil dikirim']);
    }


    /**
     * @throws Throwable
     */
    public function cancelDelivery(StockMutation $stockMutation): JsonResponse
    {
        $this->stockMutationService->cancelDelivery($stockMutation);
        return response()->json(['message' => 'pengiriman item berhasil dibatalkan']);
    }


    public function receiveItem(StockMutation $stockMutation): JsonResponse
    {
        $this->stockMutationService->receiveItem($stockMutation);
        return response()->json(['message' => 'item berhasil diterima']);
    }


    public function bastDocument(StockMutation $stockMutation): Response
    {

        $stockMutation->load('stockMutationItems', 'stockMutationItems.stock.item', 'sender', 'receiver', 'newBranch.parent');

        $view = view('pages.inventory.stock-mutations.bast-document', compact('stockMutation'));


        $pdf = Browsershot::html($view)
            ->setChromePath('C:\Users\Javanicus\scoop\apps\chromium\current\chrome.exe')
//            ->setChromePath('/usr/bin/chromium')
            ->noSandbox()
            ->waitUntilNetworkIdle()
            ->ignoreHttpsErrors()
            ->format('A4')
            ->setEnvironmentOptions([
                'CHROME_CONFIG_HOME' => storage_path('app/chrome/.config')
            ])->pdf();


        return new Response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="example.pdf',
        ]);
    }


}
