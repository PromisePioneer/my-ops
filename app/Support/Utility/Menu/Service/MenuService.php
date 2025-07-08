<?php

namespace App\Support\Utility\Menu\Service;

use AllowDynamicProperties;
use App\Support\Utility\Menu\Repository\MenuRepository;
use Illuminate\Pagination\LengthAwarePaginator;

#[AllowDynamicProperties] class MenuService
{
    private static int $perPage = 10;


    public function __construct()
    {
        $this->menuRepository = new MenuRepository();
    }


    public function data()
    {
        $query = $this->menuRepository->data()->get();
        return self::formattedData($query);
    }


    public function formattedData($menus)
    {
        return $menus->map(function ($menu) {
            return [
                'id' => $menu->id,
                'name' => $menu->name,
                'link' => $menu->link,
                'icon' => $menu->icon,
                'children' => $menu->children->map(function ($child) {
                    return [
                        'id' => $child->id,
                        'name' => $child->name,
                        'link' => $child->link,
                    ];
                }),
            ];
        });
    }
}
