<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Articles - Admin Dashboard</title>
    <x-addons />
</head>
<body class="bg-gray-50">
    <!-- Include Admin Navbar -->
    @include('admin.navbar')

    <!-- Main Content -->
    <div class="pt-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Article Management</h1>
                    <p class="text-gray-600 mt-1">Manage your blog articles and content</p>
                </div>
                <a href="{{ route('admin.articles.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-[#002147] text-white font-semibold rounded-lg hover:bg-[#003166] transition-colors shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    New Article
                </a>
            </div>

            <!-- Search & Filter -->
            <div class="bg-white rounded-lg shadow-md p-4 mb-6">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" 
                               id="searchInput"
                               placeholder="Search articles by title or author..."
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#002147] focus:border-transparent">
                    </div>
                    <div class="flex gap-2">
                        <select id="statusFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#002147]">
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        <button onclick="clearFilters()" class="px-4 py-2 text-gray-600 hover:text-gray-800">
                            Clear
                        </button>
                    </div>
                </div>
            </div>

            <!-- Articles Table -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-200 sticky top-0 z-10">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider w-2/5">
                                    Article
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider w-48">
                                    Author
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider w-32">
                                    Published
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider w-24">
                                    Views
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider w-28">
                                    Status
                                </th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider w-40">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200" id="articlesTableBody">
                            @forelse($articles as $article)
                            <tr class="hover:bg-gray-50 transition-colors article-row" 
                                data-title="{{ strtolower($article->title) }}"
                                data-author="{{ strtolower($article->author->name) }}"
                                data-status="{{ $article->is_active ? 'active' : 'inactive' }}">
                                <td class="px-6 py-4">
                                    <div class="flex items-start gap-4 max-w-xl">
                                        @if($article->cover_image)
                                        <div class="flex-shrink-0">
                                            <img src="{{ $article->cover_image_url }}" 
                                                 alt="{{ $article->title }}"
                                                 class="w-16 h-16 rounded-lg object-cover">
                                        </div>
                                        @else
                                        <div class="flex-shrink-0 w-16 h-16 rounded-lg bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-xl">
                                            {{ substr($article->title, 0, 1) }}
                                        </div>
                                        @endif
                                        <div class="min-w-0 flex-1">
                                            <div class="font-semibold text-gray-900 line-clamp-2 mb-1 break-words">
                                                {{ $article->title }}
                                            </div>
                                            @if($article->excerpt)
                                            <div class="text-xs text-gray-500 line-clamp-2 break-words">
                                                {{ $article->excerpt }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 w-48">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <div class="w-8 h-8 flex-shrink-0 rounded-full bg-[#002147] text-white flex items-center justify-center text-sm font-semibold">
                                            {{ substr($article->author->name, 0, 1) }}
                                        </div>
                                        <span class="text-sm text-gray-700 truncate">{{ $article->author->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($article->published_at)
                                    <div class="text-sm text-gray-700">
                                        {{ $article->published_at->format('M d, Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $article->published_at->format('H:i') }}
                                    </div>
                                    @else
                                    <span class="text-sm text-gray-400">Not published</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-1 text-gray-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <span class="text-sm font-medium">{{ number_format($article->views) }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($article->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3"/>
                                        </svg>
                                        Active
                                    </span>
                                    @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3"/>
                                        </svg>
                                        Inactive
                                    </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ $article->url }}" 
                                           target="_blank"
                                           class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 p-2 hover:bg-blue-50 rounded-lg transition-colors"
                                           title="View Article">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            <span class="hidden md:inline">View</span>
                                        </a>
                                        <a href="{{ route('admin.articles.edit', $article->id) }}" 
                                           class="inline-flex items-center gap-1 text-yellow-600 hover:text-yellow-800 p-2 hover:bg-yellow-50 rounded-lg transition-colors"
                                           title="Edit Article">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            <span class="hidden md:inline">Edit</span>
                                        </a>
                                        <form action="{{ route('admin.articles.destroy', $article->id) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Are you sure you want to delete this article? This will also delete all associated files.');"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center gap-1 text-red-600 hover:text-red-800 p-2 hover:bg-red-50 rounded-lg transition-colors"
                                                    title="Delete Article">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                <span class="hidden md:inline">Delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center gap-4">
                                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <div>
                                            <p class="text-gray-600 font-medium">No articles found</p>
                                            <p class="text-gray-400 text-sm mt-1">Create your first article to get started</p>
                                        </div>
                                        <a href="{{ route('admin.articles.create') }}" 
                                           class="mt-2 bg-[#002147] text-white px-6 py-2 rounded-lg hover:bg-[#003166] transition-colors">
                                            Create Article
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($articles->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $articles->links() }}
                </div>
                @endif
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Total Articles</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $articles->total() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Active Articles</p>
                            <p class="text-2xl font-bold text-green-600 mt-1">{{ $articles->where('is_active', true)->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Total Views</p>
                            <p class="text-2xl font-bold text-purple-600 mt-1">{{ number_format($articles->sum('views')) }}</p>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Avg. Views</p>
                            <p class="text-2xl font-bold text-orange-600 mt-1">{{ $articles->count() > 0 ? number_format($articles->sum('views') / $articles->count()) : 0 }}</p>
                        </div>
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Search functionality
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const tableBody = document.getElementById('articlesTableBody');
        const rows = tableBody.querySelectorAll('.article-row');

        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase();
            const statusValue = statusFilter.value;

            rows.forEach(row => {
                const title = row.dataset.title;
                const author = row.dataset.author;
                const status = row.dataset.status;

                const matchesSearch = title.includes(searchTerm) || author.includes(searchTerm);
                const matchesStatus = !statusValue || status === statusValue;

                row.style.display = matchesSearch && matchesStatus ? '' : 'none';
            });
        }

        searchInput.addEventListener('input', filterTable);
        statusFilter.addEventListener('change', filterTable);

        function clearFilters() {
            searchInput.value = '';
            statusFilter.value = '';
            filterTable();
        }
    </script>
</body>
</html>
