<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\JointClosureArea;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class JointClosureAreaController extends Controller
{
    public function index(): View
    {
        return view('pages.master.joint-closures-area.index');
    }

    public function data(): JsonResponse
    {
        return response()->json(JointClosureArea::with('branch')->paginate(10));
    }


    public function search(): JsonResponse
    {
    }


    public function store(): JsonResponse
    {
        return response()->json();
    }


    public function edit(): JsonResponse
    {
    }

    public function update(): JsonResponse
    {
    }

    public function destroy(): JsonResponse
    {
    }
}
