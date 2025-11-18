<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    /**
     * Display a listing of articles in admin
     */
    public function index()
    {
        $articles = Article::with('author')
            ->latest('created_at')
            ->paginate(15);

        return view('admin.articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new article
     */
    public function create()
    {
        return view('admin.articles.create');
    }

    /**
     * Store a newly created article
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:15360', // 15MB = 15360 KB
            'content' => 'required|string',
            'published_at' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        // Generate slug and folder path
        $datePrefix = date('Y-m-d');
        $slug = Str::slug($validated['title']);
        $folderPath = "{$slug}_{$datePrefix}";

        // Create article folder on public storage
        $disk = Storage::disk('public');
        $baseDir = "articles/{$folderPath}";
        if (!$disk->exists($baseDir)) {
            $disk->makeDirectory($baseDir);
        }

        // Save markdown content to storage
        $disk->put("{$baseDir}/index.md", $validated['content']);

        // Handle cover image upload
        $coverImageFileName = null;
        if ($request->hasFile('cover_image')) {
            $image = $request->file('cover_image');
            $imageName = 'cover.' . $image->getClientOriginalExtension();
            $disk->putFileAs($baseDir, $image, $imageName);
            $coverImageFileName = $imageName; // store filename only
        }

        // Create article record
        $article = Article::create([
            'title' => $validated['title'],
            'slug' => $folderPath, // Using folder path as slug for URL
            'folder_path' => $folderPath,
            'excerpt' => $validated['excerpt'],
            'cover_image' => $coverImageFileName,
            'author_id' => Auth::id(),
            'published_at' => $validated['published_at'] ?? now(),
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return redirect()
            ->route('admin.articles.index')
            ->with('success', 'Article created successfully!');
    }

    /**
     * Show the form for editing the specified article
     */
    public function edit(int $id)
    {
        $article = Article::findOrFail($id);

    // Read markdown content from storage
    $disk = Storage::disk('public');
    $mdPath = "articles/{$article->folder_path}/index.md";
    $content = $disk->exists($mdPath) ? $disk->get($mdPath) : '';

        return view('admin.articles.edit', compact('article', 'content'));
    }

    /**
     * Update the specified article
     */
    public function update(Request $request, int $id)
    {
        $article = Article::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:15360', // 15MB
            'content' => 'required|string',
            'published_at' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

    // Update markdown content in storage
    $disk = Storage::disk('public');
    $mdPath = "articles/{$article->folder_path}/index.md";
    $disk->put($mdPath, $validated['content']);

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $disk = Storage::disk('public');
            // Delete old cover image if exists
            if ($article->cover_image) {
                $old = "articles/{$article->folder_path}/{$article->cover_image}";
                if ($disk->exists($old)) {
                    $disk->delete($old);
                }
            }

            $image = $request->file('cover_image');
            $imageName = 'cover.' . $image->getClientOriginalExtension();
            $disk->putFileAs("articles/{$article->folder_path}", $image, $imageName);
            $validated['cover_image'] = $imageName; // store filename only
        }

        // Update article record
        $article->update([
            'title' => $validated['title'],
            'excerpt' => $validated['excerpt'],
            'cover_image' => $validated['cover_image'] ?? $article->cover_image,
            'published_at' => $validated['published_at'] ?? $article->published_at,
            'is_active' => $validated['is_active'] ?? $article->is_active,
        ]);

        return redirect()
            ->route('admin.articles.index')
            ->with('success', 'Article updated successfully!');
    }

    /**
     * Remove the specified article
     */
    public function destroy(int $id)
    {
        $article = Article::findOrFail($id);

        // Delete article folder and all contents from storage
        $disk = Storage::disk('public');
        $baseDir = "articles/{$article->folder_path}";
        if ($disk->exists($baseDir)) {
            $disk->deleteDirectory($baseDir);
        }

        // Delete article record
        $article->delete();

        return redirect()
            ->route('admin.articles.index')
            ->with('success', 'Article deleted successfully!');
    }
}
