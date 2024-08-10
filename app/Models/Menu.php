<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menus';

    protected $fillable = [
        'parent_id',
        'nama_menu',
        'link_menu',
        'deskripsi_menu',
        'icon_menu',
        'level_menu',
        'no_urut',
        'class_active',
    ];
}
