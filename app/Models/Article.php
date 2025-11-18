<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Article extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'folder_path',
        'excerpt',
        'cover_image',
        'author_id',
        'published_at',
        'is_active',
        'views',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Get the author of the article
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Scope for published articles
     */
    public function scopePublished($query)
    {
        return $query->where('is_active', true)
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    /**
     * Scope for latest articles
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('published_at', 'desc');
    }

    /**
     * Get the full markdown file path
     */
    public function getMarkdownPathAttribute(): string
    {
        // Path on disk for editing/reading in backend
        return storage_path("app/public/articles/{$this->folder_path}/index.md");
    }

    /**
     * Get the cover image URL
     */
    public function getCoverImageUrlAttribute(): ?string
    {
        if (!$this->cover_image) {
            return null;
        }
        $val = $this->cover_image;
        // If absolute URL
        if (str_starts_with($val, 'http://') || str_starts_with($val, 'https://')) {
            return $val;
        }
        // If legacy path like /articles/folder/cover.jpg -> map to /storage/articles/...
        if (str_starts_with($val, '/articles/')) {
            $mapped = preg_replace('#^/articles/#', 'storage/articles/', $val);
            return asset($mapped);
        }
        // If already storage path
        if (str_starts_with($val, '/storage/')) {
            return asset(ltrim($val, '/'));
        }
        // Otherwise treat as filename stored relative to folder
        return asset("storage/articles/{$this->folder_path}/{$val}");
    }

    /**
     * Get the article URL
     */
    public function getUrlAttribute(): string
    {
        return route('articles.show', $this->slug);
    }

    /**
     * Generate slug from title
     */
    public static function generateSlug(string $title): string
    {
        $datePrefix = date('Y-m-d');
        $slug = Str::slug($title);
        return "{$slug}_{$datePrefix}";
    }

    /**
     * Increment view count
     */
    public function incrementViews(): void
    {
        $this->increment('views');
    }

    /**
     * Home categories (many-to-many)
     */
    public function homeCategories()
    {
        return $this->belongsToMany(CategoryHome::class, 'category_article', 'article_id', 'category_id')
                    ->withPivot('order')
                    ->withTimestamps();
    }
}
