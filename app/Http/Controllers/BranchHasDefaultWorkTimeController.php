<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Support\Attendances\WorkTime\Service\BranchHasDefaultWorkTimeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

#[AllowDynamicProperties] class BranchHasDefaultWorkTimeController extends Controller
{
    public function __construct()
    {
        $this->branchHasDefaultWorkTimeService = new BranchHasDefaultWorkTimeService();
    }


    public function index(): View
    {
        return view('pages.adms.work-time.branch.index');
    }

    public function data(Request $request): JsonResponse
    {
        return response()->json($this->branchHasDefaultWorkTimeService->data($request));
    }


    public function search()
    {

    }

    public function store()
    {

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
