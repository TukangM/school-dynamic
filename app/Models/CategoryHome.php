<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryHome extends Model
{
    protected $table = 'categories_home';
    
    protected $fillable = [
        'display_name',
        'idpath',
        'slug',
        'description',
        'custom_html',
        'path',
        'order',
        'max_articles',
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

    /**
     * Articles dalam kategori ini (many-to-many)
     */
    public function articles()
    {
        return $this->belongsToMany(Article::class, 'category_article', 'category_id', 'article_id')
                    ->withPivot('order')
                    ->withTimestamps()
                    ->orderBy('category_article.order');
    }
}