<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Models\Menu;
use App\Support\Utility\Menu\Service\MenuService;
use Illuminate\Http\JsonResponse;
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


    public function edit(Menu $menu): JsonResponse
    {
        $menu->load('permissions');
        return response()->json($menu);
    }


    public function move(Menu $menu, int $oldParentId, int $newParentId)
    {
        $menu->update([
            'parent_id' => $newParentId,
        ]);
    }


    public function reorder(Request $request)
    {
        foreach ($request->get('children') as $key => $value) {
            Menu::where('id', $value['id'])->update(['order' => $key]);
        }
    }
}
