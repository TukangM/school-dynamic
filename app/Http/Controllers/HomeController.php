<?php

namespace App\Http\Controllers;

use App\Models\CategoryHome;
use App\Models\CategoryNavbar;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Load navbar categories
        $navbarCategories = CategoryNavbar::active()
            ->ordered()
            ->with(['subItems' => function($query) {
                $query->where('is_active', 1)->orderBy('order');
            }])
            ->get();
        
        // Load home categories dengan artikel
        $categories = CategoryHome::with(['articles' => function($query) {
            $query->where('is_active', true)
                  ->orderBy('category_article.order');
        }])
        ->where('is_active', true)
        ->orderBy('order')
        ->get()
        ->map(function($category) {
            // Transform untuk view dengan logic pagination trigger
            $allArticles = $category->articles;
            $totalArticles = $allArticles->count();
            $maxArticles = $category->max_articles ?? 4;
            
            // Add custom properties ke model
            $category->display_name = $category->display_name;
            $category->articles_count = $totalArticles;
            $category->articles = $allArticles->take($maxArticles); // Limit untuk homepage
            $category->has_more = $totalArticles > $maxArticles; // Trigger button "View All"
            
            return $category;
        });
        
        return view('pages.index', compact('categories', 'navbarCategories'));
    }
    
    public function showCategory($slug)
    {
        $navbarCategories = CategoryNavbar::active()
            ->ordered()
            ->with(['subItems' => function($query) {
                $query->where('is_active', 1)->orderBy('order');
            }])
            ->get();
            
        $category = CategoryHome::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();
        
        // Load ALL articles dengan pagination
        $articles = $category->articles()
            ->where('is_active', true)
            ->orderBy('category_article.order')
            ->paginate(12);
        
        return view('pages.category', compact('category', 'articles', 'navbarCategories'));
    }
}
