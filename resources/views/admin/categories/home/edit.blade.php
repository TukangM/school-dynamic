<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category - Admin Dashboard</title>
    <x-addons />
</head>
<body class="bg-gray-50">
    @include('admin.navbar')

    <div class="pt-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header -->
            <div class="mb-6">
                <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 mb-4">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to Categories
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Edit Category</h1>
                <p class="text-gray-600 mt-1">Update category settings</p>
            </div>

            <!-- Form -->
            <form action="{{ route('admin.categories.home.update', $category->id) }}" method="POST" class="bg-white rounded-lg shadow-md p-6">
                @csrf
                @method('PUT')

                <!-- Display Name -->
                <div class="mb-6">
                    <label for="display_name" class="block text-sm font-semibold text-gray-700 mb-2">
                        Display Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="display_name" 
                           name="display_name" 
                           value="{{ old('display_name', $category->display_name) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., News, Events, Discover"
                           required
                           oninput="generateSlug()">
                    @error('display_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Slug -->
                <div class="mb-6">
                    <label for="slug" class="block text-sm font-semibold text-gray-700 mb-2">
                        URL Slug <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="slug" 
                           name="slug" 
                           value="{{ old('slug', $category->slug) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono text-sm"
                           required>
                    <p class="text-xs text-gray-500 mt-1">Used in URL: /category/<span id="slug-preview" class="font-semibold">{{ $category->slug }}</span></p>
                    @error('slug')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Brief description of this category (optional)">{{ old('description', $category->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Max Articles -->
                <div class="mb-6">
                    <label for="max_articles" class="block text-sm font-semibold text-gray-700 mb-2">
                        Max Articles on Homepage <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           id="max_articles" 
                           name="max_articles" 
                           value="{{ old('max_articles', $category->max_articles) }}"
                           min="2"
                           max="12"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           required>
                    <p class="text-xs text-gray-500 mt-1">Number of articles to display in this category section (minimum 2, max 12)</p>
                    @error('max_articles')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Is Active -->
                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="is_active" 
                               value="1"
                               {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                               class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-3 text-sm font-semibold text-gray-700">Active (show on homepage)</span>
                    </label>
                    @error('is_active')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t">
                    <a href="{{ route('admin.categories.index') }}" 
                       class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-[#002147] text-white rounded-lg hover:bg-[#003166] transition-colors shadow-sm">
                        Update Category
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function generateSlug() {
            const displayName = document.getElementById('display_name').value;
            const slug = displayName
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
            
            document.getElementById('slug').value = slug;
            document.getElementById('slug-preview').textContent = slug || 'your-slug';
        }

        // Allow manual slug editing
        document.getElementById('slug').addEventListener('input', function() {
            document.getElementById('slug-preview').textContent = this.value || 'your-slug';
        });
    </script>
</body>
</html>
