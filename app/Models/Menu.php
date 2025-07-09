<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    protected $table = 'menus';
    protected $fillable = [
        'name',
        'parent_id',
        'section_id',
        'link',
        'icon',
    ];


    public function parent(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }


    public function children(): HasMany
    {
        return $this->hasMany(Menu::class, 'parent_id');
    }


    public function section(): BelongsTo
    {
        return $this->belongsTo(MenuSection::class, 'section_id');
    }


    public function permissions(): HasMany
    {
        return $this->hasMany(MenuPermission::class, 'menu_id');
    }

}
