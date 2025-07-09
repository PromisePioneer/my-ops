<?php

namespace App\Support\Utility\Menu\Repository;

use AllowDynamicProperties;
use App\Models\Menu;
use Illuminate\Database\Eloquent\Builder;

#[AllowDynamicProperties] class MenuRepository
{
    public function __construct()
    {
        $this->menu = new Menu();
    }


    public function data(): Builder
    {
        return $this->menu->with(['children' => function ($query) {
            $query->orderBy('order');
        }])->whereNull('parent_id');
    }
}
