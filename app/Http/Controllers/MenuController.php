<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Models\Menu;
use App\Support\Utility\Menu\Service\MenuService;
use Illuminate\Http\Request;

#[AllowDynamicProperties] class MenuController extends Controller
{
    public function __construct()
    {
        $this->menuService = new MenuService();
    }

    public function index()
    {
        return view('pages.utilities.menu.index');
    }

    public function data()
    {
        return response()->json($this->menuService->data());
    }
}
