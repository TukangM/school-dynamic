<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoryHome;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryHomeController extends Controller
{
    public function create()
    {
        return view('admin.categories.home.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'display_name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:categories_home,slug',
            'description' => 'nullable|string',
            'max_articles' => 'required|integer|min:2|max:12',
            'is_active' => 'boolean',
        ]);

        // Auto-generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['display_name']);
        }

        // Set order as last
        $validated['order'] = CategoryHome::max('order') + 1;
        
        // Generate idpath (for compatibility)
        $validated['idpath'] = Str::slug($validated['display_name']);

        CategoryHome::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully!');
    }

    public function edit(CategoryHome $category)
    {
        $category->load('articles');
        
        return view('admin.categories.home.edit', compact('category'));
    }

    public function update(Request $request, CategoryHome $category)
    {
        $validated = $request->validate([
            'display_name' => 'required|string|max:255',
            'slug' => 'required|string|unique:categories_home,slug,' . $category->id,
            'description' => 'nullable|string',
            'max_articles' => 'required|integer|min:2|max:12',
            'is_active' => 'boolean',
        ]);

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully!');
    }

    public function destroy(CategoryHome $category)
    {
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully!');
    }

    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'categories' => 'required|array',
            'categories.*.id' => 'required|exists:categories_home,id',
            'categories.*.order' => 'required|integer|min:0',
        ]);

        foreach ($validated['categories'] as $categoryData) {
            CategoryHome::where('id', $categoryData['id'])
                ->update(['order' => $categoryData['order']]);
        }

        return response()->json(['message' => 'Order updated successfully!']);
    }
}
