<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuSection extends Model
{
    protected $table = 'menu_sections';
    protected $fillable = [
        'name'
    ];


    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class, 'section_id');
    }
}
