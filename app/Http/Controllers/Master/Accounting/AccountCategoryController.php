<?php

namespace App\Http\Controllers\Master\Accounting;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Accounting\AccountCategoryRequest;
use App\Models\AccountCategory;
use App\Support\Master\Accounting\AccountCategories\Service\AccountCategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

#[AllowDynamicProperties] class AccountCategoryController extends Controller
{


    public function __construct()
    {
        $this->accountCategoryService = new AccountCategoryService();
    }

    public function index(): View
    {
        return view('pages.master.accounting.account-categories.index');
    }


    public function data(): JsonResponse
    {
        return response()->json($this->accountCategoryService->data());
    }


    public function store(AccountCategoryRequest $request): JsonResponse
    {
        AccountCategory::create([
            'name' => $request->name,
        ]);
    }


    public function edit()
    {

    }


    public function update()
    {

    }

    public function destroy()
    {

    }
}
