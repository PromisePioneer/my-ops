<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuPermission extends Model
{
    protected $table = 'menu_permissions';
    protected $fillable = [
        'menu_id',
        'permission_id',
    ];


    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }


    public function permission(): BelongsTo
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }
}
