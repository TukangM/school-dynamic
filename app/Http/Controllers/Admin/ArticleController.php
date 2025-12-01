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

        // Process temp images: move from temp to article folder and update markdown URLs
        $content = $validated['content'];
        $content = $this->processTempImages($content, $baseDir, $disk);

        // Save markdown content to storage
        $disk->put("{$baseDir}/index.md", $content);

        // Handle cover image upload
        $coverImageFileName = null;
        if ($request->hasFile('cover_image')) {
            // User manually uploaded cover (priority)
            $image = $request->file('cover_image');
            $imageName = 'cover.' . $image->getClientOriginalExtension();
            $disk->putFileAs($baseDir, $image, $imageName);
            $coverImageFileName = $imageName; // store filename only
        } else {
            // Auto-extract first valid cover image from markdown (≥1000×1000px)
            $coverImageFileName = $this->findFirstValidCoverImage($content, $disk);
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

        // Process temp images: move from temp to article folder and update markdown URLs
        $disk = Storage::disk('public');
        $baseDir = "articles/{$article->folder_path}";
        $content = $this->processTempImages($validated['content'], $baseDir, $disk);

        // Update markdown content in storage
        $mdPath = "articles/{$article->folder_path}/index.md";
        $disk->put($mdPath, $content);

        // Handle cover image logic
        $coverImageFileName = $article->cover_image; // Keep existing by default
        
        if ($request->hasFile('cover_image')) {
            // User manually uploaded new cover (priority)
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
            $coverImageFileName = $imageName; // store filename only
        } elseif (!$article->cover_image) {
            // If no existing cover, try auto-extract from markdown
            $coverImageFileName = $this->findFirstValidCoverImage($content, $disk, $baseDir);
        }

        // Update article record
        $article->update([
            'title' => $validated['title'],
            'excerpt' => $validated['excerpt'],
            'cover_image' => $coverImageFileName,
            'published_at' => $validated['published_at'] ?? $article->published_at,
            'is_active' => $validated['is_active'] ?? $article->is_active,
        ]);

        return redirect()
            ->route('admin.articles.index')
            ->with('success', 'Article updated successfully!');
    }

    /**
     * Delete cover image for an article
     */
    public function deleteCover(int $id)
    {
        $article = Article::findOrFail($id);
        $disk = Storage::disk('public');
        $baseDir = "articles/{$article->folder_path}";

        // Delete cover file from storage
        if ($article->cover_image) {
            $coverPath = "{$baseDir}/{$article->cover_image}";
            if ($disk->exists($coverPath)) {
                $disk->delete($coverPath);
            }
        }

        // Read markdown content
        $mdPath = "{$baseDir}/index.md";
        $content = $disk->exists($mdPath) ? $disk->get($mdPath) : '';

        // Try to auto-extract new cover from markdown
        $newCoverFileName = $this->findFirstValidCoverImage($content, $disk, $baseDir);

        // Update article
        $article->update(['cover_image' => $newCoverFileName]);

        return response()->json([
            'success' => true,
            'message' => 'Cover image deleted successfully!',
            'new_cover' => $newCoverFileName,
        ]);
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

    /**
     * Process temporary images in markdown content
     * 
     * Moves images from temp-upload folder to article folder
     * and updates markdown URLs accordingly.
     * Also deletes any images in article folder that are not referenced in markdown.
     * 
     * @param string $content Markdown content with image references
     * @param string $targetDir Target article directory path
     * @param \Illuminate\Filesystem\FilesystemAdapter $disk Storage disk instance
     * @return string Updated markdown content with new image URLs
     */
    private function processTempImages(string $content, string $targetDir, $disk): string
    {
        // Track all used images
        $usedImages = [];
        
        // Find all image markdown references: ![...](url)
        $pattern = '/!\[([^\]]*)\]\(([^)]+)\)/';
        
        $content = preg_replace_callback($pattern, function ($matches) use ($targetDir, $disk, &$usedImages) {
            $altText = $matches[1];
            $imagePath = $matches[2];
            
            // Check if this is a temp image
            if (strpos($imagePath, '/articles/temp-upload/') !== false) {
                // Extract filename from URL
                $urlParts = parse_url($imagePath);
                $pathParts = explode('/', $urlParts['path']);
                $filename = end($pathParts);
                
                // Source and destination paths
                $tempPath = "articles/temp-upload/{$filename}";
                $newPath = "{$targetDir}/{$filename}";
                
                // Move image from temp to article folder
                if ($disk->exists($tempPath)) {
                    $content = $disk->get($tempPath);
                    $disk->put($newPath, $content);
                    $disk->delete($tempPath);
                    
                    // Track used images
                    $usedImages[] = $filename;
                    
                    // Return updated markdown with new image path as HTML img tag with inline style
                    $newUrl = asset("storage/{$newPath}");
                    return "<img src=\"{$newUrl}\" alt=\"{$altText}\" style=\"width: 70%; height: auto;\" />";
                }
            } else if (strpos($imagePath, '/articles/') !== false && strpos($imagePath, $targetDir) !== false) {
                // Track non-temp images that are referenced
                $urlParts = parse_url($imagePath);
                $pathParts = explode('/', $urlParts['path']);
                $filename = end($pathParts);
                $usedImages[] = $filename;
            }
            
            // Return original if not a temp image or file doesn't exist
            return $matches[0];
        }, $content);
        
        // Also track images from HTML img tags: <img src="...">
        $htmlPattern = '/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/i';
        if (preg_match_all($htmlPattern, $content, $matches)) {
            foreach ($matches[1] as $imagePath) {
                if (!empty($imagePath) && strpos($imagePath, '/articles/') !== false && strpos($imagePath, $targetDir) !== false) {
                    // Extract filename from HTML img src
                    $urlParts = parse_url($imagePath);
                    $pathParts = explode('/', $urlParts['path']);
                    $filename = end($pathParts);
                    $usedImages[] = $filename;
                }
            }
        }
        
        // Delete unused images in article folder (except cover image)
        if ($disk->exists($targetDir)) {
            $allFiles = $disk->listContents($targetDir);
            foreach ($allFiles as $file) {
                if ($file['type'] === 'file') {
                    $filename = basename($file['path']);
                    // Skip cover image and markdown file
                    if ($filename !== 'cover.jpg' && $filename !== 'cover.png' && 
                        $filename !== 'cover.gif' && $filename !== 'cover.webp' && 
                        $filename !== 'index.md' && !in_array($filename, $usedImages)) {
                        $disk->delete($file['path']);
                    }
                }
            }
        }
        
        return $content;
    }

    /**
     * Extract all image URLs from markdown and HTML content
     * 
     * Supports both markdown format: ![alt](url)
     * And HTML format: <img src="...">
     * 
     * @param string $content Markdown content
     * @return array Array of extracted image URLs
     */
    private function extractImagesFromMarkdown(string $content): array
    {
        $images = [];

        // Extract markdown images: ![alt](url)
        $markdownPattern = '/!\[([^\]]*)\]\(([^)]+)\)/';
        if (preg_match_all($markdownPattern, $content, $matches)) {
            foreach ($matches[2] as $url) {
                if (!empty($url)) {
                    $images[] = $url;
                }
            }
        }

        // Extract HTML images: <img src="...">
        $htmlPattern = '/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/i';
        if (preg_match_all($htmlPattern, $content, $matches)) {
            foreach ($matches[1] as $url) {
                if (!empty($url)) {
                    $images[] = $url;
                }
            }
        }

        return array_unique($images);
    }

    /**
     * Get image dimensions from URL
     * 
     * Validates if image meets minimum 1000×1000px requirement
     * 
     * @param string $imageUrl Image URL
     * @return array|null Array with 'width' and 'height' keys, or null if unable to fetch
     */
    private function getImageDimensions(string $imageUrl): ?array
    {
        try {
            // For local storage URLs, try to get dimensions from filesystem
            if (strpos($imageUrl, '/storage/') !== false) {
                // Extract path from URL
                $urlPath = parse_url($imageUrl, PHP_URL_PATH);
                $relativePath = str_replace('/storage/', '', $urlPath);
                $disk = Storage::disk('public');
                
                if ($disk->exists($relativePath)) {
                    $fullPath = storage_path('app/public/' . $relativePath);
                    if (function_exists('getimagesize')) {
                        $imageSize = @getimagesize($fullPath);
                        if ($imageSize) {
                            return [
                                'width' => $imageSize[0],
                                'height' => $imageSize[1],
                            ];
                        }
                    }
                }
            } else {
                // For external URLs, fetch headers (faster than full download)
                if (function_exists('getimagesizefromstring')) {
                    $context = stream_context_create([
                        'http' => ['timeout' => 5],
                        'https' => ['timeout' => 5],
                    ]);
                    
                    $imageData = @file_get_contents($imageUrl, false, $context, 0, 10000);
                    if ($imageData) {
                        $imageSize = @getimagesizefromstring($imageData);
                        if ($imageSize) {
                            return [
                                'width' => $imageSize[0],
                                'height' => $imageSize[1],
                            ];
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            // Silently fail and return null
        }

        return null;
    }

    /**
     * Find first valid cover image from markdown content
     * 
     * Loops through extracted images and finds first one meeting
     * minimum 1000×1000px requirement.
     * 
     * @param string $content Markdown content
     * @param \Illuminate\Filesystem\FilesystemAdapter $disk Storage disk
     * @param string $baseDir Base article directory for relative paths
     * @return string|null Filename (cover.jpg) if valid image found, null otherwise
     */
    private function findFirstValidCoverImage(string $content, $disk, string $baseDir = ''): ?string
    {
        $images = $this->extractImagesFromMarkdown($content);

        foreach ($images as $imageUrl) {
            // Skip if it's a cover image itself
            if (strpos($imageUrl, '/cover.') !== false || strpos($imageUrl, '%2Fcover.') !== false) {
                continue;
            }

            // Get image dimensions
            $dimensions = $this->getImageDimensions($imageUrl);

            // Check if dimensions are valid (≥1000×1000px)
            if ($dimensions && $dimensions['width'] >= 1000 && $dimensions['height'] >= 1000) {
                // Try to copy/download image as cover.jpg
                try {
                    if (strpos($imageUrl, '/storage/') !== false) {
                        // Local storage image
                        $urlPath = parse_url($imageUrl, PHP_URL_PATH);
                        $relativePath = str_replace('/storage/', '', $urlPath);
                        
                        if ($disk->exists($relativePath)) {
                            // Copy local image to cover.jpg
                            $imageContent = $disk->get($relativePath);
                            $coverPath = "{$baseDir}/cover.jpg";
                            $disk->put($coverPath, $imageContent);
                            return 'cover.jpg';
                        }
                    } else {
                        // External URL - download and save
                        $context = stream_context_create([
                            'http' => ['timeout' => 10],
                            'https' => ['timeout' => 10],
                        ]);
                        
                        $imageContent = @file_get_contents($imageUrl, false, $context);
                        if ($imageContent) {
                            $coverPath = "{$baseDir}/cover.jpg";
                            $disk->put($coverPath, $imageContent);
                            return 'cover.jpg';
                        }
                    }
                } catch (\Exception $e) {
                    // Failed to copy this image, try next one
                    continue;
                }
            }
        }

        // No valid image found
        return null;
    }
}
