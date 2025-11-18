<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Display a listing of published articles
     */
    public function index()
    {
        $articles = Article::published()
            ->latest()
            ->with('author')
            ->paginate(12);

        $navbarCategories = \App\Models\CategoryNavbar::active()
            ->ordered()
            ->with(['subItems' => function($query) {
                $query->where('is_active', 1)->orderBy('order');
            }])
            ->get();

        return view('articles.index', compact('articles', 'navbarCategories'));
    }

    /**
     * Display the specified article
     */
    public function show(string $slug)
    {
        $article = Article::where('slug', $slug)
            ->published()
            ->with('author')
            ->firstOrFail();

        // Increment views
        $article->incrementViews();

        // Public URL for markdown file served from storage symlink
        $markdownPath = asset("storage/articles/{$article->folder_path}/index.md");

        // Get related articles
        $relatedArticles = Article::published()
            ->where('id', '!=', $article->id)
            ->latest()
            ->take(3)
            ->get();

        $navbarCategories = \App\Models\CategoryNavbar::active()
            ->ordered()
            ->with(['subItems' => function($query) {
                $query->where('is_active', 1)->orderBy('order');
            }])
            ->get();

        return view('articles.show', compact('article', 'markdownPath', 'relatedArticles', 'navbarCategories'));
    }
}
