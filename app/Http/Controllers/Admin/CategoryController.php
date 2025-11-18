<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoryNavbar;
use App\Models\CategoryHome;
use App\Models\SubcategoryNavbar;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $navbarCategories = CategoryNavbar::with('subItems')->orderBy('order')->get();
        $homeCategories = CategoryHome::with('articles')->orderBy('order')->get();
        
        return view('admin.categories.index', compact('navbarCategories', 'homeCategories'));
    }

    public function createNavbar()
    {
        return view('admin.categories.create-navbar');
    }

    public function storeNavbar(Request $request)
    {
        $validated = $request->validate([
            'display_name' => 'required|string|max:255',
            'subcategories' => 'boolean',
            'path' => 'nullable|string',
            'order' => 'integer|min:0',
            'is_active' => 'boolean'
        ]);

        $validated['idpath'] = date('Y-m-d') . '-' . Str::slug($validated['display_name']);
        $validated['subcategories'] = $request->has('subcategories') ? 1 : 0;
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        CategoryNavbar::create($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Navbar category created successfully!');
    }

    public function createHome()
    {
        return view('admin.categories.create-home');
    }

    public function storeHome(Request $request)
    {
        $validated = $request->validate([
            'display_name' => 'required|string|max:255',
            'custom_html' => 'nullable|string',
            'path' => 'nullable|string',
            'order' => 'integer|min:0'
        ]);

        $validated['idpath'] = date('Y-m-d') . '-' . Str::slug($validated['display_name']);

        CategoryHome::create($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Home category created successfully!');
    }

    public function editNavbar($id)
    {
        $category = CategoryNavbar::with('subItems')->findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function updateNavbar(Request $request, $id)
    {
        $category = CategoryNavbar::findOrFail($id);
        
        $validated = $request->validate([
            'display_name' => 'required|string|max:255',
            'subcategories' => 'boolean',
            'path' => 'nullable|string',
            'order' => 'integer|min:0',
            'is_active' => 'boolean'
        ]);

        $validated['subcategories'] = $request->has('subcategories') ? 1 : 0;
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        $category->update($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Navbar category updated successfully!');
    }

    public function editHome($id)
    {
        $category = CategoryHome::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function updateHome(Request $request, $id)
    {
        $category = CategoryHome::findOrFail($id);
        
        $validated = $request->validate([
            'display_name' => 'required|string|max:255',
            'custom_html' => 'nullable|string',
            'path' => 'nullable|string',
            'order' => 'integer|min:0',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        $category->update($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Home category updated successfully!');
    }

    public function destroyNavbar($id)
    {
        CategoryNavbar::findOrFail($id)->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Navbar category deleted successfully!');
    }

    public function destroyHome($id)
    {
        CategoryHome::findOrFail($id)->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Home category deleted successfully!');
    }

    // Subcategories Management
    public function storeSubcategory(Request $request)
    {
        $validated = $request->validate([
            'parent_category_id' => 'required|exists:categories_navbar,id',
            'display_name' => 'required|string|max:255',
            'path' => 'required|string',
            'order' => 'integer|min:0'
        ]);

        $validated['idpath'] = date('Y-m-d') . '-' . Str::slug($validated['display_name']);
        $validated['is_active'] = 1;

        SubcategoryNavbar::create($validated);

        return redirect()->route('admin.categories.edit-navbar', $validated['parent_category_id'])->with('success', 'Subcategory added successfully!');
    }

    public function updateSubcategory(Request $request, $id)
    {
        $subcategory = SubcategoryNavbar::findOrFail($id);
        
        $validated = $request->validate([
            'display_name' => 'required|string|max:255',
            'path' => 'required|string',
            'order' => 'integer|min:0',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        $subcategory->update($validated);

        return redirect()->route('admin.categories.edit-navbar', $subcategory->parent_category_id)->with('success', 'Subcategory updated successfully!');
    }

    public function destroySubcategory($id)
    {
        $subcategory = SubcategoryNavbar::findOrFail($id);
        $categoryId = $subcategory->parent_category_id;
        $subcategory->delete();

        return redirect()->route('admin.categories.edit-navbar', $categoryId)->with('success', 'Subcategory deleted successfully!');
    }
}