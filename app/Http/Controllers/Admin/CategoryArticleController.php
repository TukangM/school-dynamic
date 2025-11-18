<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoryHome;
use App\Models\Article;
use Illuminate\Http\Request;

class CategoryArticleController extends Controller
{
    public function index(Request $request, CategoryHome $category)
    {
        // Get assigned articles with pivot order
        $assignedArticles = $category->articles()
            ->with('author')
            ->orderBy('category_article.order')
            ->get();
        
        // Get articles NOT in this category (for search)
        $search = $request->get('search');
        $availableArticles = Article::with('author')
        ->whereDoesntHave('homeCategories', function($query) use ($category) {
            $query->where('categories_home.id', $category->id);
        })
        ->where('is_active', true)
        ->when($search, function($query) use ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
            });
        })
        ->orderBy('published_at', 'desc')
        ->paginate(10);

        return view('admin.categories.home.articles', compact('category', 'assignedArticles', 'availableArticles'));
    }

    public function add(Request $request, CategoryHome $category)
    {
        $validated = $request->validate([
            'article_id' => 'required|exists:articles,id',
        ]);

        // Check if already exists
        if ($category->articles()->where('article_id', $validated['article_id'])->exists()) {
            return response()->json(['success' => false, 'message' => 'Article already in this category!'], 400);
        }

        // Get max order
        $maxOrder = $category->articles()->max('category_article.order') ?? -1;

        // Attach with next order
        $category->articles()->attach($validated['article_id'], [
            'order' => $maxOrder + 1
        ]);

        return response()->json(['success' => true, 'message' => 'Article added to category!']);
    }

    public function remove(CategoryHome $category, Article $article)
    {
        $category->articles()->detach($article->id);

        return response()->json(['success' => true, 'message' => 'Article removed from category!']);
    }

    public function reorder(Request $request, CategoryHome $category)
    {
        $validated = $request->validate([
            'articles' => 'required|array',
            'articles.*.id' => 'required|exists:articles,id',
            'articles.*.order' => 'required|integer|min:0',
        ]);

        foreach ($validated['articles'] as $articleData) {
            $category->articles()->updateExistingPivot($articleData['id'], [
                'order' => $articleData['order']
            ]);
        }

        return response()->json(['message' => 'Article order updated successfully!']);
    }

    public function updateMaxArticles(Request $request, CategoryHome $category)
    {
        $validated = $request->validate([
            'max_articles' => 'required|integer|min:2|max:12',
        ]);

        $category->update($validated);

        return response()->json(['success' => true, 'message' => 'Max articles updated!']);
    }
}
