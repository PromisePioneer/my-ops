<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BranchHasDefaultWorkTimeController extends Controller
{
    public function __construct()
    {

    }


    public function index(): View
    {
        return view('adms.branch-has-default-work-time.index');
    }

    public function data(Request $request): JsonResponse
    {

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
