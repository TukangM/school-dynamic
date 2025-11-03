<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryNavbar extends Model
{
    protected $table = 'categories_navbar';
    
    protected $fillable = [
        'display_name',
        'idpath',
        'subcategories',
        'path',
        'order',
        'is_active'
    ];

    protected $casts = [
        'subcategories' => 'boolean',
        'is_active' => 'boolean'
    ];

    public function subItems()
    {
        return $this->hasMany(SubcategoryNavbar::class, 'parent_category_id')->orderBy('order');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}