<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Models\CategoryNavbar;

// Homepage with dynamic categories
Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Category page
Route::get('/category/{slug}', [\App\Http\Controllers\HomeController::class, 'showCategory'])->name('category.show');

Route::get('/navbar-preview', function () {
    $navbarCategories = CategoryNavbar::active()
        ->ordered()
        ->with(['subItems' => function($query) {
            $query->where('is_active', 1)->orderBy('order');
        }])
        ->get();
    
    return view('components.navbar', compact('navbarCategories'));
});

Route::get('/test-preview', function () {
    return view('test');
});

// Public Articles Routes (URLs under /articles). We serve files from /storage to avoid public/articles folder conflicts.
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{slug}', [ArticleController::class, 'show'])->name('articles.show');

// Auth Routes
Route::get('/login', function () {
    if (Auth::check()) {
        return redirect()->route('admin.dashboard');
    }
    return view('admin.auth');
})->name('login');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/logout', function () {
    return redirect('/');
});

// Admin Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/dashboard', function () {
        return redirect()->route('admin.dashboard');
    });
    
    // Categories
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    
    // Navbar Categories
    Route::prefix('categories/navbar')->name('categories.navbar.')->group(function() {
        Route::get('/create', function() {
            return view('admin.categories.create', ['type' => 'navbar']);
        })->name('create');
        Route::post('/', [CategoryController::class, 'storeNavbar'])->name('store');
        Route::get('/{id}/edit', [CategoryController::class, 'editNavbar'])->name('edit');
        Route::put('/{id}', [CategoryController::class, 'updateNavbar'])->name('update');
        Route::delete('/{id}', [CategoryController::class, 'destroyNavbar'])->name('destroy');
    });
    
    // Subcategories Management
    Route::post('/subcategories', [CategoryController::class, 'storeSubcategory'])->name('subcategories.store');
    Route::put('/subcategories/{id}', [CategoryController::class, 'updateSubcategory'])->name('subcategories.update');
    Route::delete('/subcategories/{id}', [CategoryController::class, 'destroySubcategory'])->name('subcategories.destroy');
    
    // Home Categories Management (NEW)
    Route::prefix('categories/home')->name('categories.home.')->group(function() {
        Route::get('/create', [\App\Http\Controllers\Admin\CategoryHomeController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\CategoryHomeController::class, 'store'])->name('store');
        Route::get('/{category}/edit', [\App\Http\Controllers\Admin\CategoryHomeController::class, 'edit'])->name('edit');
        Route::put('/{category}', [\App\Http\Controllers\Admin\CategoryHomeController::class, 'update'])->name('update');
        Route::delete('/{category}', [\App\Http\Controllers\Admin\CategoryHomeController::class, 'destroy'])->name('destroy');
        Route::post('/reorder', [\App\Http\Controllers\Admin\CategoryHomeController::class, 'reorder'])->name('reorder');
        
        // Article Management within Category
        Route::get('/{category}/articles', [\App\Http\Controllers\Admin\CategoryArticleController::class, 'index'])->name('articles.index');
        Route::post('/{category}/articles/add', [\App\Http\Controllers\Admin\CategoryArticleController::class, 'add'])->name('articles.add');
        Route::delete('/{category}/articles/{article}', [\App\Http\Controllers\Admin\CategoryArticleController::class, 'remove'])->name('articles.remove');
        Route::post('/{category}/articles/reorder', [\App\Http\Controllers\Admin\CategoryArticleController::class, 'reorder'])->name('articles.reorder');
        Route::post('/{category}/articles/max', [\App\Http\Controllers\Admin\CategoryArticleController::class, 'updateMaxArticles'])->name('articles.max');
    });
    
    // Articles Management
    Route::get('/articles', [AdminArticleController::class, 'index'])->name('articles.index');
    Route::get('/articles/create', [AdminArticleController::class, 'create'])->name('articles.create');
    Route::post('/articles', [AdminArticleController::class, 'store'])->name('articles.store');
    Route::get('/articles/{id}/edit', [AdminArticleController::class, 'edit'])->name('articles.edit');
    Route::put('/articles/{id}', [AdminArticleController::class, 'update'])->name('articles.update');
    Route::delete('/articles/{id}', [AdminArticleController::class, 'destroy'])->name('articles.destroy');
    Route::post('/articles/{id}/delete-cover', [AdminArticleController::class, 'deleteCover'])->name('articles.delete-cover');
    
    // Article Image Upload
    Route::post('/articles/upload-image', [\App\Http\Controllers\Admin\ArticleImageController::class, 'uploadTemp'])->name('articles.upload-image');
    Route::post('/articles/cleanup-temp', [\App\Http\Controllers\Admin\ArticleImageController::class, 'cleanupTemp'])->name('articles.cleanup-temp');
    
    // Users (admin only, placeholder routes for future)
    Route::get('/users', function () {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }
        return 'Users Management - Coming Soon';
    })->name('users.index');
});

// Redirect /dashboard to /admin
Route::get('/dashboard', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    return redirect()->route('admin.dashboard');
})->name('dashboard');
