<?php

namespace App\Http\Controllers\Master\Accounting;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Accounting\AccountCategory\AccountCategoryRequest;
use App\Models\AccountCategory;
use App\Support\Master\Accounting\AccountCategories\Service\AccountCategoryService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

#[AllowDynamicProperties] class AccountCategoryController extends Controller
{
    public function __construct()
    {
        $this->accountCategoryService = new AccountCategoryService();
    }

    /**
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('view', AccountCategory::class);
        return view('pages.master.accounting.account-categories.index');
    }


    /**
     * @throws AuthorizationException
     */
    public function data(): JsonResponse
    {
        $this->authorize('view', AccountCategory::class);
        return response()->json($this->accountCategoryService->data());
    }


    /**
     * @throws AuthorizationException
     */
    public function store(AccountCategoryRequest $request): JsonResponse
    {
        $this->authorize('create', AccountCategory::class);
        AccountCategory::updateOrCreate([
            'name' => $request->name,
        ]);

        return response()->json([
            'message' => 'Data berhasil disimpan'
        ]);
    }


    public function storeChild(AccountCategoryRequest $request, AccountCategory $accountCategory): JsonResponse
    {
        $this->authorize('create', AccountCategory::class);
        AccountCategory::create([
            'name' => $request->name,
            'parent_id' => $accountCategory->id
        ]);

        return response()->json([
            'message' => 'data berhasil disimpan'
        ]);
    }


    public function edit(AccountCategory $accountCategory): JsonResponse
    {
        $this->authorize('update', $accountCategory);
        $data = AccountCategory::where('id', $accountCategory->id)->with('parent')->first();
        return response()->json($data);
    }


    public function update(AccountCategoryRequest $request, AccountCategory $accountCategory): JsonResponse
    {
        $this->authorize('update', $accountCategory);
        $accountCategory->update([
            'name' => $request->name
        ]);
        return response()->json([
            'message' => 'Data berhasil disimpan'
        ]);
    }


    public function updateChild(AccountCategoryRequest $request, AccountCategory $accountCategory): JsonResponse
    {
        $this->authorize('update', $accountCategory);
        $accountCategory->update([
            'name' => $request->name
        ]);

        return response()->json([
            'message' => 'data berhasil disimpan'
        ]);
    }

    public function destroy(AccountCategory $accountCategory, Request $request): JsonResponse
    {
        $this->authorize('delete', $accountCategory);
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $accountCategory->whereIn('id', $explodeID)->delete();

        return response()->json([
            'message' => 'data berhasil dihapus',
        ]);
    }
}
