<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Article - Admin Dashboard</title>
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
            <div class="flex items-center gap-4 mb-6">
                <a href="{{ route('admin.articles.index') }}" 
                   class="text-gray-600 hover:text-gray-900 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Create New Article</h1>
                    <p class="text-gray-600 mt-1">Write and publish your article content</p>
                </div>
            </div>

            <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

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
                                   value="{{ old('title') }}"
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
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#002147] focus:border-transparent resize-none @error('excerpt') border-red-500 @enderror">{{ old('excerpt') }}</textarea>
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
                            <div class="mt-2">
                                <input type="file" 
                                       name="cover_image" 
                                       id="cover_image" 
                                       accept="image/*"
                                       onchange="previewImage(this)"
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#002147] file:text-white hover:file:bg-[#003166] cursor-pointer @error('cover_image') border-red-500 @enderror">
                                @error('cover_image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div id="imagePreview" class="mt-4 hidden">
                                <img src="" alt="Preview" class="w-full h-48 object-cover rounded-lg border border-gray-200">
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
                                   value="{{ old('published_at') }}"
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
                                       {{ old('is_active', true) ? 'checked' : '' }}
                                       class="w-5 h-5 text-[#002147] border-gray-300 rounded focus:ring-2 focus:ring-[#002147]">
                                <div>
                                    <span class="text-sm font-semibold text-gray-700 group-hover:text-gray-900">Make this article active</span>
                                    <p class="text-xs text-gray-500">Active articles will be visible to the public</p>
                                </div>
                            </label>
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
                            Write your article content using Markdown. The editor supports live preview, code syntax highlighting, and more.
                        </p>
                        
                        <!-- React MD Editor Mount Point -->
                        <div id="article-editor-root" 
                             data-initial-content="{{ old('content', '# Your Article Title...') }}"
                             data-field-name="content">
                        </div>

                        @error('content')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-between gap-4 bg-white rounded-lg shadow-md p-6">
                    <a href="{{ route('admin.articles.index') }}" 
                       class="px-6 py-3 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors font-semibold">
                        Cancel
                    </a>
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
                            Publish Article
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            const previewImg = preview.querySelector('img');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.classList.remove('hidden');
                };
                
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
