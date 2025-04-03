<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccountCategory extends Model
{
    protected $table = 'account_categories';
    protected $fillable = ['name', 'parent_id'];


    public function parent(): BelongsTo
    {
        return $this->belongsTo(AccountCategory::class, 'parent_id');
    }


    public function children(): HasMany
    {
        return $this->hasMany(AccountCategory::class, 'parent_id');
    }


    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class, 'category_id');
    }
}
