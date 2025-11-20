<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\CategoryHome;
use App\Models\CategoryNavbar;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_articles' => Article::count(),
            'active_articles' => Article::where('is_active', true)->count(),
            'total_home_categories' => CategoryHome::count(),
            'total_navbar_categories' => CategoryNavbar::count(),
            'total_users' => User::count(),
            'total_views' => Article::sum('views'),
        ];

        $recent_articles = Article::with('author')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return view('admin.index', compact('stats', 'recent_articles'));
    }
}
