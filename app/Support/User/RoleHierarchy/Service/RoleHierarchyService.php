<?php

namespace App\Support\User\RoleHierarchy\Service;

use App\Models\RoleHierarchy;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class RoleHierarchyService
{
    private static int $perPage = 10;

    public function data()
    {
        $data = RoleHierarchy::with('role', 'children')->get();
        return self::formattedData($data);
    }


    private static function formattedData($data)
    {
        return $data->filter(function ($item) {
            return $item->children->isNotEmpty();
        })->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->role->name,
                'parent_id' => $item->parent_id,
                'children' => $item->children->map(function ($child) {
                    return [
                        'id' => $child->id,
                        'name' => $child->role->name,
                        'parent_id' => $child->parent_id,
                    ];
                }),
            ];
        })->values();
    }


    public function search()
    {

    }
}
