<?php

namespace App\Http\Controllers\Master\Accounting;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Accounting\AccountCategory\AccountCategoryRequest;
use App\Models\AccountCategory;
use App\Support\Master\Accounting\AccountCategories\Repositories\AccountCategoryRepository;
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
        $this->accountCategory = new AccountCategory();
        $this->accountCategoryRepository = new AccountCategoryRepository();
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


    public function search(Request $request): JsonResponse
    {
        return response()->json($this->accountCategoryService->search($request));
    }


    /**
     * @throws AuthorizationException
     */
    public function store(AccountCategoryRequest $request): JsonResponse
    {
        $this->authorize('create', AccountCategory::class);
        $this->accountCategory->updateOrCreate([
            'name' => $request->name,
        ]);

        return response()->json([
            'message' => 'Data berhasil disimpan'
        ]);
    }


    public function storeChild(AccountCategoryRequest $request, AccountCategory $accountCategory): JsonResponse
    {
        $this->authorize('create', AccountCategory::class);
        $this->accountCategory->updateOrCreate([
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
        $accountCategory->load('parent');
        return response()->json($accountCategory);
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


    public function getAccountCategories(Request $request): JsonResponse
    {
       return response()->json($this->accountCategoryService->getAllAccountCategories($request));
    }


    public function selectedAccountCategory(AccountCategory $accountCategory): JsonResponse
    {
      return response()->json($this->accountCategoryService->selectedAccountCategory($accountCategory));
    }
}
