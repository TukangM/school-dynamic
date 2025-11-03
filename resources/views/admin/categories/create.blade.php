<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category - Admin Dashboard</title>
    <x-addons />
</head>
<body class="bg-gray-50">
    @include('admin.navbar')

    <div class="pt-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @php
                $showSubcategoriesPreview = request('type') !== 'home' && old('subcategories', false);
            @endphp

            <div id="main-grid" class="relative grid grid-cols-1 lg:grid-cols-10 gap-6">
                <!-- Left Column: Main Form (70% or 100% animated) -->
                <div id="category-form-column" class="transition-all duration-300 ease-in-out {{ $showSubcategoriesPreview ? 'lg:col-span-7' : 'lg:col-span-10' }}">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="mb-6">
                            <h2 class="text-2xl font-bold text-gray-900">
                                Add {{ request('type') === 'home' ? 'Home' : 'Navbar' }} Category
                            </h2>
                            <p class="text-sm text-gray-600 mt-1">
                                {{ request('type') === 'home' ? 'Create a new category for homepage sections' : 'Create a new category for the navigation menu' }}
                            </p>
                        </div>

                        <form action="{{ request('type') === 'home' ? route('admin.categories.store-home') : route('admin.categories.store-navbar') }}" method="POST">
                            @csrf
                            
                            <div class="space-y-6">
                                <!-- Display Name -->
                                <div>
                                    <label for="display_name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Display Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           name="display_name" 
                                           id="display_name" 
                                           value="{{ old('display_name') }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#002147] focus:border-transparent"
                                           placeholder="e.g., {{ request('type') === 'home' ? 'Berita Terbaru, Gallery' : 'Profil, Akademik' }}"
                                           required>
                                    <p class="text-xs text-gray-500 mt-1">The name that will be displayed</p>
                                </div>

                                @if(request('type') !== 'home')
                                <!-- Has Subcategories (Navbar only) -->
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" 
                                               name="subcategories" 
                                               id="subcategories" 
                                               value="1"
                                               {{ old('subcategories') ? 'checked' : '' }}
                                               onchange="toggleSubcategoriesPreview()"
                                               class="w-4 h-4 text-[#002147] border-gray-300 rounded focus:ring-[#002147]">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="subcategories" class="font-medium text-gray-700">Has Subcategories</label>
                                        <p class="text-gray-500">Enable dropdown submenu for this category</p>
                                    </div>
                                </div>
                                @endif

                                <!-- Path -->
                                <div>
                                    <label for="path" class="block text-sm font-medium text-gray-700 mb-2">
                                        Path (URL)
                                    </label>
                                    <input type="text" 
                                           name="path" 
                                           id="path" 
                                           value="{{ old('path') }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#002147] focus:border-transparent transition-colors"
                                           placeholder="/about or /profil">
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ request('type') === 'home' ? 'Optional link for this section' : 'Disabled when has subcategories' }}
                                    </p>
                                </div>

                                @if(request('type') === 'home')
                                <!-- Custom HTML (Home only) -->
                                <div>
                                    <label for="custom_html" class="block text-sm font-medium text-gray-700 mb-2">
                                        Custom HTML Content
                                    </label>
                                    <textarea name="custom_html" 
                                              id="custom_html" 
                                              rows="8"
                                              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#002147] focus:border-transparent font-mono text-sm"
                                              placeholder="<div class='grid grid-cols-3 gap-4'>&#10;    <!-- Your content here -->&#10;</div>">{{ old('custom_html') }}</textarea>
                                    <p class="text-xs text-gray-500 mt-1">HTML/Blade code to display in this section</p>
                                </div>
                                @endif

                                <!-- Order -->
                                <div>
                                    <label for="order" class="block text-sm font-medium text-gray-700 mb-2">
                                        Display Order
                                    </label>
                                    <input type="number" 
                                           name="order" 
                                           id="order" 
                                           value="{{ old('order', 0) }}"
                                           min="0"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#002147] focus:border-transparent">
                                    <p class="text-xs text-gray-500 mt-1">Lower numbers appear first (e.g., 1, 2, 3...)</p>
                                </div>

                                <!-- Active Status -->
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" 
                                               name="is_active" 
                                               id="is_active" 
                                               value="1"
                                               checked
                                               class="w-4 h-4 text-[#002147] border-gray-300 rounded focus:ring-[#002147]">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="is_active" class="font-medium text-gray-700">Active</label>
                                        <p class="text-gray-500">Uncheck to hide this category</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="flex items-center justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                                <a href="{{ route('admin.categories.index') }}" 
                                   class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                                    Cancel
                                </a>
                                <button type="submit" 
                                        class="px-4 py-2 bg-[#002147] text-white rounded-md text-sm font-medium hover:bg-[#003166] transition-colors duration-200">
                                    Create Category
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Column: Subcategories Preview (Always 30%, hidden behind form when inactive) -->
                @if(request('type') !== 'home')
                <div
                    id="subcategories-preview"
                    class="lg:col-span-3 transition-all duration-300 ease-in-out {{ $showSubcategoriesPreview ? 'opacity-100 z-10' : 'opacity-0 -z-10' }}"
                >
                    <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-6 lg:sticky lg:top-6">
                        <div class="mb-4">
                            <h3 class="text-lg font-medium text-gray-900">📋 Subcategories</h3>
                            <p class="text-sm text-gray-500 mt-1">Coming after create</p>
                        </div>

                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <p class="text-sm text-gray-700 mb-3">
                                ✓ Path field is disabled<br>
                                ✓ Subcategories enabled
                            </p>
                            <p class="text-xs text-gray-600 italic">
                                After creating this category, you can manage subcategories from the edit page.
                            </p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function toggleSubcategoriesPreview() {
            const checkbox = document.getElementById('subcategories');
            const pathField = document.getElementById('path');
            const previewColumn = document.getElementById('subcategories-preview');
            const categoryFormColumn = document.getElementById('category-form-column');
            
            if (!checkbox || !previewColumn || !categoryFormColumn) {
                return;
            }

            if (checkbox.checked) {
                // Show preview column (bring to front)
                previewColumn.classList.remove('opacity-0', '-z-10');
                previewColumn.classList.add('opacity-100', 'z-10');
                
                // Form to 70%
                categoryFormColumn.classList.remove('lg:col-span-10');
                categoryFormColumn.classList.add('lg:col-span-7');
                
                pathField.disabled = true;
                pathField.value = '';
                pathField.classList.add('bg-gray-100', 'cursor-not-allowed', 'text-gray-500');
            } else {
                // Hide preview column (send to back)
                previewColumn.classList.remove('opacity-100', 'z-10');
                previewColumn.classList.add('opacity-0', '-z-10');
                
                // Form to 100%
                categoryFormColumn.classList.remove('lg:col-span-7');
                categoryFormColumn.classList.add('lg:col-span-10');
                
                pathField.disabled = false;
                pathField.classList.remove('bg-gray-100', 'cursor-not-allowed', 'text-gray-500');
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            const checkbox = document.getElementById('subcategories');
            if (checkbox) {
                toggleSubcategoriesPreview();
            }
        });
    </script>
</body>
</html>
