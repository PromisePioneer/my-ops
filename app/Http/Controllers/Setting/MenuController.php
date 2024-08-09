<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(): View
    {
        return view('pages.setting.menu.index');
    }

    public function data(): JsonResponse
    {
        $menu = Menu::orderBy('name')->paginate(10);

        return response()->json($menu);
    }

    public function store(): JsonResponse {}

    public function edit(): JsonResponse {}

    public function destroy(): JsonResponse {}
}
