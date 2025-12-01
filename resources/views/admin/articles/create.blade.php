<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                <!-- Context tracking for image uploads -->
                <input type="hidden" name="article_id" value="new" id="article-id-input">

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

                <!-- Image Upload Helper Section -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Upload & Insert Images
                        </h3>
                        <p class="text-sm text-gray-600 mb-4">Upload images and copy the code to paste into the editor above</p>

                        <!-- Upload Button -->
                        <div class="flex gap-2 mb-4 items-center">
                            <button type="button" 
                                    id="upload-image-btn"
                                    class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Upload Image (or Ctrl+V)
                            </button>
                            <input type="file" 
                                   id="image-file-input" 
                                   accept="image/*" 
                                   style="display: none;">
                            
                            <div id="upload-status" class="text-sm text-gray-600 flex items-center gap-2 hidden">
                                <svg class="w-4 h-4 animate-spin text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                <span id="upload-status-text">Uploading...</span>
                            </div>
                        </div>

                        <!-- Generated Markdown Code Block -->
                        <div id="markdown-result" class="hidden">
                            <!-- Uploaded Images List -->
                            <div id="images-container" class="space-y-4 mb-4">
                                <!-- Each image will be added here -->
                            </div>

                            <!-- Instructions -->
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                <p class="text-sm text-blue-900">
                                    <strong>💡 Tip:</strong> Click the copy button on any code block to copy it, then paste into the editor above.
                                </p>
                            </div>
                        </div>

                        <!-- Empty State -->
                        <div id="empty-state" class="text-center py-8 text-gray-500 border-2 border-dashed border-gray-300 rounded-lg">
                            <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-sm">No images uploaded yet. Click the button above or paste an image!</p>
                        </div>
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
        class ImageUploadHelper {
            constructor() {
                this.uploadedImages = [];
                this.isUploading = false;
                this.init();
            }

            init() {
                // Button click handler
                document.getElementById('upload-image-btn').addEventListener('click', () => {
                    document.getElementById('image-file-input').click();
                });

                // File input change handler
                document.getElementById('image-file-input').addEventListener('change', (e) => {
                    if (e.target.files[0]) {
                        this.uploadImage(e.target.files[0]);
                        e.target.value = '';
                    }
                });

                // Paste handler for direct image paste
                document.addEventListener('paste', (e) => {
                    const items = e.clipboardData.items;
                    for (let item of items) {
                        if (item.kind === 'file' && item.type.startsWith('image/')) {
                            e.preventDefault();
                            const file = item.getAsFile();
                            this.uploadImage(file);
                            break;
                        }
                    }
                });
            }

            showStatus(text) {
                const status = document.getElementById('upload-status');
                document.getElementById('upload-status-text').textContent = text;
                status.classList.remove('hidden');
            }

            hideStatus() {
                document.getElementById('upload-status').classList.add('hidden');
            }

            showResults() {
                const result = document.getElementById('markdown-result');
                const emptyState = document.getElementById('empty-state');
                if (this.uploadedImages.length > 0) {
                    result.classList.remove('hidden');
                    emptyState.classList.add('hidden');
                } else {
                    result.classList.add('hidden');
                    emptyState.classList.remove('hidden');
                }
            }

            copyToClipboard(text, button) {
                navigator.clipboard.writeText(text).then(() => {
                    const originalHTML = button.innerHTML;
                    button.innerHTML = `
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Copied!</span>
                    `;
                    button.classList.remove('bg-gray-700', 'hover:bg-gray-600');
                    button.classList.add('bg-green-600');
                    
                    setTimeout(() => {
                        button.innerHTML = originalHTML;
                        button.classList.remove('bg-green-600');
                        button.classList.add('bg-gray-700', 'hover:bg-gray-600');
                    }, 2000);
                });
            }

            addImageCard(markdownCode, htmlCode, imageUrl, filename) {
                const container = document.getElementById('images-container');
                const imageId = 'image-' + Date.now();

                const card = document.createElement('div');
                card.id = imageId;
                card.className = 'bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg border border-gray-200 overflow-hidden';
                
                card.innerHTML = `
                    <!-- Image Preview Header -->
                    <div class="bg-white border-b border-gray-200 p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <img src="${imageUrl}" alt="Preview" class="w-16 h-16 rounded-lg border-2 border-gray-300 object-cover shadow-sm">
                                <div>
                                    <p class="font-semibold text-gray-900 text-sm">${filename}</p>
                                    <p class="text-xs text-gray-500">Uploaded successfully</p>
                                </div>
                            </div>
                            <button type="button" 
                                    class="delete-btn px-3 py-2 text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors flex items-center gap-2 text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Delete
                            </button>
                        </div>
                    </div>

                    <!-- Code Blocks -->
                    <div class="p-4 space-y-3">
                        <!-- Markdown Syntax -->
                        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                            <div class="bg-gray-800 px-4 py-2 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-mono text-gray-400">MARKDOWN</span>
                                    <span class="text-xs text-gray-500">•</span>
                                    <span class="text-xs text-gray-400">For Markdown editors</span>
                                </div>
                                <button type="button" 
                                        class="copy-markdown-btn px-3 py-1 bg-gray-700 hover:bg-gray-600 text-gray-100 rounded text-xs font-medium transition-colors flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                    <span>Copy</span>
                                </button>
                            </div>
                            <div class="bg-gray-900 p-4">
                                <pre class="markdown-code text-sm text-gray-100 font-mono overflow-x-auto whitespace-pre-wrap break-all">${this.escapeHtml(markdownCode)}</pre>
                            </div>
                        </div>

                        <!-- HTML Syntax -->
                        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                            <div class="bg-gray-800 px-4 py-2 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-mono text-gray-400">HTML</span>
                                    <span class="text-xs text-gray-500">•</span>
                                    <span class="text-xs text-gray-400">For HTML/Rich text editors</span>
                                </div>
                                <button type="button" 
                                        class="copy-html-btn px-3 py-1 bg-gray-700 hover:bg-gray-600 text-gray-100 rounded text-xs font-medium transition-colors flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                    <span>Copy</span>
                                </button>
                            </div>
                            <div class="bg-gray-900 p-4">
                                <pre class="html-code text-sm text-gray-100 font-mono overflow-x-auto whitespace-pre-wrap break-all">${this.escapeHtml(htmlCode)}</pre>
                            </div>
                        </div>
                    </div>
                `;

                // Copy markdown button handler
                const copyMarkdownBtn = card.querySelector('.copy-markdown-btn');
                copyMarkdownBtn.addEventListener('click', () => {
                    this.copyToClipboard(markdownCode, copyMarkdownBtn);
                });

                // Copy HTML button handler
                const copyHtmlBtn = card.querySelector('.copy-html-btn');
                copyHtmlBtn.addEventListener('click', () => {
                    this.copyToClipboard(htmlCode, copyHtmlBtn);
                });

                // Delete button handler
                const deleteBtn = card.querySelector('.delete-btn');
                deleteBtn.addEventListener('click', () => {
                    if (confirm('Delete this image from the list?')) {
                        card.remove();
                        this.uploadedImages = this.uploadedImages.filter(img => img.id !== imageId);
                        this.showResults();
                    }
                });

                container.appendChild(card);
                this.uploadedImages.push({ 
                    id: imageId, 
                    markdown: markdownCode, 
                    html: htmlCode,
                    imageUrl 
                });
            }

            escapeHtml(text) {
                const map = {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                };
                return text.replace(/[&<>"']/g, m => map[m]);
            }

            async uploadImage(file) {
                if (this.isUploading) {
                    alert('An upload is already in progress. Please wait...');
                    return;
                }

                this.isUploading = true;
                this.showStatus(`Uploading ${file.name}...`);

                const formData = new FormData();
                formData.append('image', file);
                formData.append('article_id', 'new');

                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
                    const response = await fetch('{{ route("admin.articles.upload-image") }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }
                    });

                    if (!response.ok) {
                        throw new Error('Upload failed with status: ' + response.status);
                    }

                    const data = await response.json();

                    if (data.success) {
                        const markdownCode = data.markdown;
                        const htmlCode = `<img src="${data.url}" alt="${file.name}" />`;
                        
                        this.addImageCard(markdownCode, htmlCode, data.url, file.name);
                        this.showResults();
                        this.hideStatus();
                        
                        console.log('✅ Upload successful!');
                        console.log('📎 URL:', data.url);
                        console.log('📝 Markdown:', markdownCode);
                        console.log('🔖 HTML:', htmlCode);
                    } else {
                        throw new Error(data.message || 'Upload failed');
                    }
                } catch (error) {
                    console.error('❌ Error:', error);
                    alert('Error uploading image: ' + error.message);
                    this.hideStatus();
                } finally {
                    this.isUploading = false;
                }
            }
        }

        // Initialize when DOM is ready
        document.addEventListener('DOMContentLoaded', () => {
            new ImageUploadHelper();
        });
    </script>
</body>
</html>
