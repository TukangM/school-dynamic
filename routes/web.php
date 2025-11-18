<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Models\CategoryNavbar;

Route::get('/', function () {
    $navbarCategories = CategoryNavbar::active()
        ->ordered()
        ->with(['subItems' => function($query) {
            $query->where('is_active', 1)->orderBy('order');
        }])
        ->get();
    
    return view('pages.index', compact('navbarCategories'));
});

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
    Route::get('/', function () {
        return view('admin.index');
    })->name('dashboard');
    
    Route::get('/dashboard', function () {
        return redirect()->route('admin.dashboard');
    });
    
    // Categories
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    
    // Navbar Categories
    Route::get('/categories/navbar/create', function() {
        return view('admin.categories.create', ['type' => 'navbar']);
    })->name('categories.create-navbar');
    Route::post('/categories/navbar', [CategoryController::class, 'storeNavbar'])->name('categories.store-navbar');
    Route::get('/categories/navbar/{id}/edit', [CategoryController::class, 'editNavbar'])->name('categories.edit-navbar');
    Route::put('/categories/navbar/{id}', [CategoryController::class, 'updateNavbar'])->name('categories.update-navbar');
    Route::delete('/categories/navbar/{id}', [CategoryController::class, 'destroyNavbar'])->name('categories.destroy-navbar');
    
    // Subcategories Management
    Route::post('/subcategories', [CategoryController::class, 'storeSubcategory'])->name('subcategories.store');
    Route::put('/subcategories/{id}', [CategoryController::class, 'updateSubcategory'])->name('subcategories.update');
    Route::delete('/subcategories/{id}', [CategoryController::class, 'destroySubcategory'])->name('subcategories.destroy');
    
    // Home Categories
    Route::get('/categories/home/create', function() {
        return view('admin.categories.create', ['type' => 'home']);
    })->name('categories.create-home');
    Route::post('/categories/home', [CategoryController::class, 'storeHome'])->name('categories.store-home');
    Route::get('/categories/home/{id}/edit', [CategoryController::class, 'editHome'])->name('categories.edit-home');
    Route::put('/categories/home/{id}', [CategoryController::class, 'updateHome'])->name('categories.update-home');
    Route::delete('/categories/home/{id}', [CategoryController::class, 'destroyHome'])->name('categories.destroy-home');
    
    // Articles Management
    Route::get('/articles', [AdminArticleController::class, 'index'])->name('articles.index');
    Route::get('/articles/create', [AdminArticleController::class, 'create'])->name('articles.create');
    Route::post('/articles', [AdminArticleController::class, 'store'])->name('articles.store');
    Route::get('/articles/{id}/edit', [AdminArticleController::class, 'edit'])->name('articles.edit');
    Route::put('/articles/{id}', [AdminArticleController::class, 'update'])->name('articles.update');
    Route::delete('/articles/{id}', [AdminArticleController::class, 'destroy'])->name('articles.destroy');
    
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
