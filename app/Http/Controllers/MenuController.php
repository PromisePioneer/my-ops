<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Http\Requests\MenuRequest;
use App\Models\Menu;
use App\Service\MenuService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

#[AllowDynamicProperties] class MenuController extends Controller
{


    public function __construct()
    {
        $this->menuService = new MenuService();
    }

    private static int $perPage = 10;

    public function index(): View
    {
        return view('pages.utilities.menus.index');
    }

    public function data(): JsonResponse
    {
        return response()->json($this->menuService->data());
    }


    public function search(Request $request): JsonResponse
    {
        $search = $request->input('search');
        $menus = Menu::with('children')
            ->when(!empty($search), function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })->paginate(self::$perPage);

        return response()->json($menus);
    }


    public function store(MenuRequest $request)
    {

    }


    public function update()
    {

    }

    public function destroy()
    {

    }
}
