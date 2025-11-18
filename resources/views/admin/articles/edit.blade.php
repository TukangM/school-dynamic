<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Article - {{ $article->title }} - Admin Dashboard</title>
    <x-addons />
    @vite(['resources/react/ArticleEditor.jsx'])
</head>
<body class="bg-gray-50">
    <!-- Include Admin Navbar -->
    @include('admin.navbar')

    <!-- Main Content -->
    <div class="pt-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header Section -->
            <div class="mb-6">
                <div class="flex items-center gap-4 mb-2">
                    <a href="{{ route('admin.articles.index') }}" 
                       class="text-gray-600 hover:text-gray-900 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Edit Article</h1>
                        <p class="text-gray-600 mt-1">{{ $article->title }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 mt-3 ml-10">
                    <a href="{{ $article->url }}" 
                       target="_blank"
                       class="text-sm text-gray-600 hover:text-gray-900 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        View Article
                    </a>
                    <span class="text-gray-400">•</span>
                    <span class="text-sm text-gray-600">{{ number_format($article->views) }} views</span>
                    <span class="text-gray-400">•</span>
                    <span class="text-sm text-gray-600">Last updated {{ $article->updated_at->diffForHumans() }}</span>
                </div>
            </div>

            <form action="{{ route('admin.articles.update', $article->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Basic Info Card -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-6 h-6 text-[#002147]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Basic Information
                    </h2>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Title -->
                        <div class="lg:col-span-2">
                            <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                                Article Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="title" 
                                   id="title" 
                                   required
                                   value="{{ old('title', $article->title) }}"
                                   placeholder="Enter article title..."
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#002147] focus:border-transparent text-lg @error('title') border-red-500 @enderror">
                            @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Excerpt -->
                        <div class="lg:col-span-2">
                            <label for="excerpt" class="block text-sm font-semibold text-gray-700 mb-2">
                                Excerpt
                                <span class="text-gray-400 font-normal">(Optional)</span>
                            </label>
                            <textarea name="excerpt" 
                                      id="excerpt" 
                                      rows="3"
                                      placeholder="Brief summary of the article (shown in listings)..."
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#002147] focus:border-transparent resize-none @error('excerpt') border-red-500 @enderror">{{ old('excerpt', $article->excerpt) }}</textarea>
                            @error('excerpt')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Cover Image -->
                        <div>
                            <label for="cover_image" class="block text-sm font-semibold text-gray-700 mb-2">
                                Cover Image
                                <span class="text-gray-400 font-normal">(Optional)</span>
                            </label>
                            
                            @if($article->cover_image)
                            <div class="mb-4">
                                <img src="{{ $article->cover_image_url }}" 
                                     alt="{{ $article->title }}"
                                     id="currentImage"
                                     class="w-full h-48 object-cover rounded-lg border border-gray-200">
                                <p class="text-xs text-gray-500 mt-2">Current cover image</p>
                            </div>
                            @endif

                            <div class="mt-2">
                                <input type="file" 
                                       name="cover_image" 
                                       id="cover_image" 
                                       accept="image/*"
                                       onchange="previewImage(this)"
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#002147] file:text-white hover:file:bg-[#003166] cursor-pointer @error('cover_image') border-red-500 @enderror">
                                <p class="text-xs text-gray-500 mt-1">Upload a new image to replace the current one</p>
                                @error('cover_image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div id="imagePreview" class="mt-4 hidden">
                                <img src="" alt="New Preview" class="w-full h-48 object-cover rounded-lg border border-gray-200">
                                <p class="text-xs text-gray-500 mt-2">New cover image preview</p>
                            </div>
                        </div>

                        <!-- Published Date -->
                        <div>
                            <label for="published_at" class="block text-sm font-semibold text-gray-700 mb-2">
                                Publish Date
                                <span class="text-gray-400 font-normal">(Optional)</span>
                            </label>
                            <input type="datetime-local" 
                                   name="published_at" 
                                   id="published_at" 
                                   value="{{ old('published_at', $article->published_at ? $article->published_at->format('Y-m-d\TH:i') : '') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#002147] focus:border-transparent @error('published_at') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-500">Leave empty to publish immediately</p>
                            @error('published_at')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Active Status -->
                        <div class="lg:col-span-2">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" 
                                       name="is_active" 
                                       id="is_active" 
                                       value="1"
                                       {{ old('is_active', $article->is_active) ? 'checked' : '' }}
                                       class="w-5 h-5 text-[#002147] border-gray-300 rounded focus:ring-2 focus:ring-[#002147]">
                                <div>
                                    <span class="text-sm font-semibold text-gray-700 group-hover:text-gray-900">Make this article active</span>
                                    <p class="text-xs text-gray-500">Active articles will be visible to the public</p>
                                </div>
                            </label>
                        </div>

                        <!-- Article Metadata -->
                        <div class="lg:col-span-2 pt-4 border-t border-gray-200">
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-500">Created:</span>
                                    <p class="font-medium text-gray-900">{{ $article->created_at->format('M d, Y') }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-500">Author:</span>
                                    <p class="font-medium text-gray-900">{{ $article->author->name }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-500">Views:</span>
                                    <p class="font-medium text-gray-900">{{ number_format($article->views) }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-500">Slug:</span>
                                    <p class="font-medium text-gray-900 text-xs break-all">{{ $article->slug }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Content Editor Card -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-6 h-6 text-[#002147]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Article Content <span class="text-red-500">*</span>
                    </h2>

                    <div class="prose max-w-none">
                        <p class="text-sm text-gray-600 mb-4">
                            Edit your article content using Markdown. The editor supports live preview, code syntax highlighting, and more.
                        </p>
                        
                        <!-- React MD Editor Mount Point -->
                        <div id="article-editor-root" 
                             data-initial-content="{{ old('content', $content) }}"
                             data-field-name="content">
                        </div>

                        @error('content')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                

                <!-- Actions -->
                <div class="flex items-center justify-between gap-4 bg-white rounded-lg shadow-md p-6">
                    <div class="flex gap-3">
                        <a href="{{ route('admin.articles.index') }}" 
                           class="px-6 py-3 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors font-semibold">
                            Cancel
                        </a>
                    </div>
                    <div class="flex gap-3">
                        <button type="submit" 
                                name="action" 
                                value="draft"
                                class="px-6 py-3 text-gray-700 bg-white border-2 border-gray-300 rounded-lg hover:bg-gray-50 transition-colors font-semibold">
                            Save as Draft
                        </button>
                        <button type="submit" 
                                name="action" 
                                value="publish"
                                class="px-8 py-3 bg-gradient-to-r from-[#002147] to-[#003166] text-white rounded-lg hover:shadow-lg transition-all font-semibold flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Update & Publish
                        </button>
                    </div>
                </div>
            </form>

            <!-- Delete Form (OUTSIDE main form to avoid conflicts) -->
            <div class="mt-6">
                <form action="{{ route('admin.articles.destroy', $article->id) }}" 
                      method="POST" 
                      onsubmit="return confirm('Are you sure you want to delete this article? This will also delete all associated files.');"
                      class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-6 py-3 text-red-600 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition-colors font-semibold flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Delete Article
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            const previewImg = preview.querySelector('img');
            const currentImage = document.getElementById('currentImage');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.classList.remove('hidden');
                    if (currentImage) {
                        currentImage.style.opacity = '0.5';
                    }
                };
                
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.classList.add('hidden');
                if (currentImage) {
                    currentImage.style.opacity = '1';
                }
            }
        }
    </script>
</body>
</html>
