<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryHome extends Model
{
    protected $table = 'categories_home';
    
    protected $fillable = [
        'display_name',
        'idpath',
        'custom_html',
        'path',
        'order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}