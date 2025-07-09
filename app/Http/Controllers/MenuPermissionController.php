<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuPermission;
use App\Models\Permission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MenuPermissionController extends Controller
{


    public function store(Request $request, Menu $menu): JsonResponse
    {
        $menu->update([
            'name' => $request->name,
        ]);


        foreach ($request->permissions as $permission) {
            MenuPermission::updateOrCreate([
                'menu_id' => $menu->id,
                'permission_id' => $permission,
            ]);

        }
        return response()->json(['status' => 'success']);
    }


    public function getSelectedPermission(Menu $menu): JsonResponse
    {
        $data = MenuPermission::with('permission')->where('menu_id', $menu->id)->pluck('permission_id')->toArray();
        $permissions = Permission::whereIn('id', $data)->get();

        return response()->json($permissions);
    }
}
