<?php

namespace App\Support\Utility\Menu\Repository;

use App\Models\Menu;

class MenuRepository
{
    public function __construct()
    {
        $this->menu = new Menu();
    }


    public function data()
    {
        return $this->menu->with('children')->whereNull('parent_id');
    }
}
