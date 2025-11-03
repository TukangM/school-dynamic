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
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6">
                    {{ session('success') }}
                </div>
            @endif

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
                $existingSubitems = !isset($category->custom_html) ? ($category->subItems ? $category->subItems->count() : 0) : 0;
                $hasSubcategoryFlag = !isset($category->custom_html) ? (bool)old('subcategories', $category->subcategories) : false;
                $shouldShowSubcategoriesColumn = !isset($category->custom_html) && ($hasSubcategoryFlag || $existingSubitems > 0);
            @endphp

            <div id="main-grid" class="relative grid grid-cols-1 lg:grid-cols-10 gap-6">
                <!-- Left Column: Main Form (70% or 100% animated) -->
                <div id="category-form-column" class="transition-all duration-300 ease-in-out {{ $shouldShowSubcategoriesColumn ? 'lg:col-span-7' : 'lg:col-span-10' }}">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="mb-6">
                            <h2 class="text-2xl font-bold text-gray-900">
                                Edit {{ isset($category->custom_html) ? 'Home' : 'Navbar' }} Category
                            </h2>
                            <p class="text-sm text-gray-600 mt-1">
                                Update category details and settings
                            </p>
                        </div>

                        <form action="{{ isset($category->custom_html) ? route('admin.categories.update-home', $category->id) : route('admin.categories.update-navbar', $category->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="space-y-6">
                                <!-- Display Name -->
                                <div>
                                    <label for="display_name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Display Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           name="display_name" 
                                           id="display_name" 
                                           value="{{ old('display_name', $category->display_name) }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#002147] focus:border-transparent"
                                           required>
                                </div>

                                @if(!isset($category->custom_html))
                                <!-- Has Subcategories (Navbar only) -->
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" 
                                               name="subcategories" 
                                               id="subcategories" 
                                               value="1"
                                               {{ old('subcategories', $category->subcategories) ? 'checked' : '' }}
                                               onchange="toggleSubcategoriesSection()"
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
                                           value="{{ old('path', $category->path) }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#002147] focus:border-transparent transition-colors"
                                           placeholder="/about or /profil">
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ isset($category->custom_html) ? 'Optional link for this section' : 'Disabled when has subcategories' }}
                                    </p>
                                </div>

                                @if(isset($category->custom_html))
                                <!-- Custom HTML (Home only) -->
                                <div>
                                    <label for="custom_html" class="block text-sm font-medium text-gray-700 mb-2">
                                        Custom HTML Content
                                    </label>
                                    <textarea name="custom_html" 
                                              id="custom_html" 
                                              rows="8"
                                              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#002147] focus:border-transparent font-mono text-sm">{{ old('custom_html', $category->custom_html) }}</textarea>
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
                                           value="{{ old('order', $category->order) }}"
                                           min="0"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#002147] focus:border-transparent">
                                    <p class="text-xs text-gray-500 mt-1">Lower numbers appear first</p>
                                </div>

                                <!-- Active Status -->
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" 
                                               name="is_active" 
                                               id="is_active" 
                                               value="1"
                                               {{ old('is_active', $category->is_active) ? 'checked' : '' }}
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
                                    Update Category
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Column: Subcategories Management (Always 30%, hidden behind form when inactive) -->
                @if(!isset($category->custom_html))
                <div
                    id="subcategories-column"
                    class="lg:col-span-3 transition-all duration-300 ease-in-out {{ $shouldShowSubcategoriesColumn ? 'opacity-100 z-10' : 'opacity-0 -z-10' }}"
                >
                    <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-6 lg:sticky lg:top-6">
                        <div class="mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Subcategories</h3>
                            <p class="text-sm text-gray-500 mt-1">Manage dropdown items</p>
                        </div>

                        <!-- Add Form -->
                        <div id="add-form-container" class="mb-6" @if(!$hasSubcategoryFlag) style="display: none;" @endif>
                            <form action="{{ route('admin.subcategories.store') }}" method="POST" id="add-subcategory-form">
                                @csrf
                                <input type="hidden" name="parent_category_id" value="{{ $category->id }}">
                                
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Name *</label>
                                        <input type="text" 
                                               name="display_name" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-[#002147] focus:border-transparent"
                                               placeholder="Submenu name"
                                               required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Path *</label>
                                        <input type="text" 
                                               name="path" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-[#002147] focus:border-transparent"
                                               placeholder="/path"
                                               required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Order</label>
                                        <input type="number" 
                                               name="order" 
                                               value="0"
                                               min="0"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-[#002147] focus:border-transparent">
                                    </div>
                                    <button type="submit" 
                                            class="w-full px-4 py-2 bg-[#002147] text-white rounded-md text-sm font-medium hover:bg-[#003166] transition-colors duration-200">
                                        + Add Subcategory
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Subcategories List -->
                        <div id="subcategories-list" data-has-items="{{ $existingSubitems > 0 ? 'true' : 'false' }}">
                            @if($category->subItems && $category->subItems->count() > 0)
                            <div class="border-t border-gray-200 pt-4">
                                <h4 class="text-xs font-medium text-gray-700 mb-3 uppercase tracking-wider">Existing Items</h4>
                                <div class="space-y-2">
                                    @foreach($category->subItems->sortBy('order') as $subItem)
                                    <div class="bg-gray-50 border border-gray-200 rounded-md p-3" id="subitem-{{ $subItem->id }}" data-subitem="true">
                                        <!-- View Mode -->
                                        <div class="view-mode">
                                            <div class="flex items-start justify-between mb-2">
                                                <div class="flex-1">
                                                    <p class="text-sm font-medium text-gray-900">{{ $subItem->display_name }}</p>
                                                    <p class="text-xs text-gray-500 mt-1">{{ $subItem->path }}</p>
                                                </div>
                                                @if($subItem->is_active)
                                                    <span class="text-xs text-green-600 bg-green-50 px-2 py-0.5 rounded">Active</span>
                                                @else
                                                    <span class="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded">Inactive</span>
                                                @endif
                                            </div>
                                            <div class="flex items-center justify-between text-xs text-gray-500">
                                                <span>Order: {{ $subItem->order }}</span>
                                                <div class="flex items-center space-x-2">
                                                    <button type="button" 
                                                            onclick="toggleEditMode({{ $subItem->id }})"
                                                            class="text-blue-600 hover:text-blue-800 font-medium">
                                                        Edit
                                                    </button>
                                                    <form action="{{ route('admin.subcategories.destroy', $subItem->id) }}" 
                                                          method="POST" 
                                                          class="inline"
                                                          onsubmit="return confirm('Delete this subcategory?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-800 font-medium">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Edit Mode (Hidden by default) -->
                                        <div class="edit-mode hidden">
                                            <form action="{{ route('admin.subcategories.update', $subItem->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="space-y-3">
                                                    <div>
                                                        <input type="text" 
                                                               name="display_name" 
                                                               value="{{ $subItem->display_name }}"
                                                               class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-[#002147] focus:border-transparent"
                                                               placeholder="Name"
                                                               required>
                                                    </div>
                                                    <div>
                                                        <input type="text" 
                                                               name="path" 
                                                               value="{{ $subItem->path }}"
                                                               class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-[#002147] focus:border-transparent"
                                                               placeholder="Path"
                                                               required>
                                                    </div>
                                                    <div class="grid grid-cols-2 gap-2">
                                                        <input type="number" 
                                                               name="order" 
                                                               value="{{ $subItem->order }}"
                                                               min="0"
                                                               class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-[#002147] focus:border-transparent"
                                                               placeholder="Order">
                                                        <div class="flex items-center">
                                                            <input type="checkbox" 
                                                                   name="is_active" 
                                                                   value="1"
                                                                   {{ $subItem->is_active ? 'checked' : '' }}
                                                                   class="w-3 h-3 text-[#002147] border-gray-300 rounded">
                                                            <label class="ml-1 text-xs text-gray-700">Active</label>
                                                        </div>
                                                    </div>
                                                    <div class="flex items-center justify-end space-x-2 pt-2">
                                                        <button type="button" 
                                                                onclick="toggleEditMode({{ $subItem->id }})"
                                                                class="px-2 py-1 border border-gray-300 rounded text-xs font-medium text-gray-700 hover:bg-gray-100">
                                                            Cancel
                                                        </button>
                                                        <button type="submit" 
                                                                class="px-2 py-1 bg-[#002147] text-white rounded text-xs font-medium hover:bg-[#003166]">
                                                            Save
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @else
                            <div class="border-t border-gray-200 pt-4">
                                <p class="text-sm text-gray-500 text-center italic">No subcategories yet</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function toggleSubcategoriesSection() {
            const checkbox = document.getElementById('subcategories');
            const pathField = document.getElementById('path');
            const subcategoriesColumn = document.getElementById('subcategories-column');
            const addFormContainer = document.getElementById('add-form-container');
            const subcategoriesList = document.getElementById('subcategories-list');
            const hasExistingItems = subcategoriesList && subcategoriesList.querySelector('[data-subitem="true"]');

            if (!subcategoriesColumn) {
                return;
            }

            const shouldShowColumn = (checkbox && checkbox.checked) || Boolean(hasExistingItems);

            if (shouldShowColumn) {
                // Show subcategories column (bring to front)
                subcategoriesColumn.classList.remove('opacity-0', '-z-10');
                subcategoriesColumn.classList.add('opacity-100', 'z-10');
            } else {
                // Hide subcategories column (send to back)
                subcategoriesColumn.classList.remove('opacity-100', 'z-10');
                subcategoriesColumn.classList.add('opacity-0', '-z-10');
            }

            if (addFormContainer) {
                addFormContainer.style.display = checkbox && checkbox.checked ? 'block' : 'none';
            }

            if (pathField) {
                if (checkbox && checkbox.checked) {
                    pathField.disabled = true;
                    pathField.value = '';
                    pathField.classList.add('bg-gray-100', 'cursor-not-allowed', 'text-gray-500');
                } else {
                    pathField.disabled = false;
                    pathField.classList.remove('bg-gray-100', 'cursor-not-allowed', 'text-gray-500');
                }
            }
        }

        function toggleEditMode(itemId) {
            const itemDiv = document.getElementById(`subitem-${itemId}`);
            const viewMode = itemDiv.querySelector('.view-mode');
            const editMode = itemDiv.querySelector('.edit-mode');
            
            if (viewMode.style.display === 'none') {
                viewMode.style.display = 'block';
                editMode.classList.add('hidden');
            } else {
                viewMode.style.display = 'none';
                editMode.classList.remove('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            toggleSubcategoriesSection();
        });
    </script>
</body>
</html>
