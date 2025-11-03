<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubcategoryNavbar extends Model
{
    protected $table = 'subcategories_navbar';
    
    protected $fillable = [
        'parent_category_id',
        'display_name',
        'idpath',
        'path',
        'order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function parentCategory()
    {
        return $this->belongsTo(CategoryNavbar::class, 'parent_category_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}