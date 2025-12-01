<?php

namespace App\Http\Controllers\Admin;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;

class ArticleImageController extends Controller
{
    /**
     * Upload image temporarily for article editor or directly to article folder
     * 
     * Context-aware upload:
     * - If article_id = 'new': Upload to temp folder (for new articles)
     * - If article_id = numeric: Upload directly to article folder (for edits)
     */
    public function uploadTemp(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:15360',
            'article_id' => 'nullable|string'
        ]);

        $file = $request->file('image');
        $filename = Str::random(12) . '.' . $file->getClientOriginalExtension();
        $articleId = $request->input('article_id', 'new');
        
        // Determine upload path based on context
        if ($articleId === 'new') {
            // New article: upload to temp folder
            $path = Storage::disk('public')->putFileAs('articles/temp-upload', $file, $filename);
            $url = asset("storage/{$path}");
        } else {
            // Existing article: upload directly to article folder
            $article = Article::find($articleId);
            if (!$article) {
                return response()->json([
                    'success' => false,
                    'message' => 'Article not found'
                ], 404);
            }
            
            $folderPath = "articles/{$article->folder_path}";
            $path = Storage::disk('public')->putFileAs($folderPath, $file, $filename);
            $url = asset("storage/{$path}");
        }
        
        return response()->json([
            'success' => true,
            'url' => $url,
            'filename' => $filename,
            'markdown' => "![image]({$url})",
            'path' => $path,
            'context' => $articleId
        ]);
    }

    /**
     * Cleanup temporary uploads (called after article save)
     */
    public function cleanupTemp(Request $request)
    {
        $tempDir = Storage::disk('public')->directories('articles/temp-upload');
        
        if (!empty($tempDir)) {
            foreach ($tempDir as $file) {
                Storage::disk('public')->delete($file);
            }
        }

        return response()->json(['success' => true]);
    }
}
