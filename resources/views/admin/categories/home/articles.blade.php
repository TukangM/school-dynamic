<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Manage Articles - {{ $category->display_name }} - Admin Dashboard</title>
    <x-addons />
</head>
<body class="bg-gray-50">
    @include('admin.navbar')

    <div class="pt-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header -->
            <div class="mb-6">
                <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 mb-4">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to Categories
                </a>
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $category->display_name }}</h1>
                        <p class="text-gray-600 mt-1">Manage articles in this category</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-sm text-gray-600">Display on homepage:</span>
                        <input type="number" 
                               id="max-articles-input"
                               value="{{ $category->max_articles }}"
                               min="2"
                               max="12"
                               class="w-20 px-3 py-2 border border-gray-300 rounded-lg text-center"
                               onchange="updateMaxArticles(this.value)">
                        <span class="text-sm text-gray-600">articles (min 2)</span>
                    </div>
                </div>
            </div>

            @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
                {{ session('error') }}
            </div>
            @endif

            <!-- Current Articles -->
            <div class="bg-white rounded-lg shadow-md mb-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900">Articles in Category ({{ $assignedArticles->count() }})</h2>
                    <p class="text-sm text-gray-600 mt-1">Drag to reorder • Click remove to unassign</p>
                </div>

                @if($assignedArticles->count() > 0)
                <div id="assigned-articles" class="divide-y divide-gray-200">
                    @foreach($assignedArticles as $article)
                    <div class="article-item p-4 hover:bg-gray-50 transition-colors" data-id="{{ $article->id }}" data-order="{{ $article->pivot->order }}">
                        <div class="flex items-center gap-4">
                            <!-- Drag Handle -->
                            <div class="drag-handle cursor-move text-gray-400 hover:text-gray-600 flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                                </svg>
                            </div>

                            <!-- Article Image -->
                            <div class="flex-shrink-0 w-16 h-16">
                                @if($article->cover_image_url)
                                <img src="{{ $article->cover_image_url }}" 
                                     alt="{{ $article->title }}"
                                     class="w-16 h-16 object-cover rounded-lg">
                                @else
                                <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                @endif
                            </div>

                            <!-- Article Info -->
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-900 mb-1 line-clamp-1">{{ $article->title }}</h3>
                                <p class="text-sm text-gray-600 line-clamp-2 mb-2">{{ $article->excerpt }}</p>
                                <div class="flex items-center gap-3 text-xs text-gray-500">
                                    <span>{{ optional($article->author)->name ?? 'No author' }}</span>
                                    <span>•</span>
                                    <span>{{ $article->views }} views</span>
                                    <span>•</span>
                                    <span>{{ $article->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>

                            <!-- Remove Button -->
                            <button onclick="removeArticle({{ $article->id }})"
                                    class="flex-shrink-0 px-4 py-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">No Articles Assigned</h3>
                    <p class="text-gray-600">Add articles from the list below</p>
                </div>
                @endif
            </div>

            <!-- Available Articles -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Available Articles</h2>
                            <p class="text-sm text-gray-600 mt-1">Click to add to category</p>
                        </div>
                        <form method="GET" class="w-full sm:w-auto">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Search articles..."
                                   class="w-full sm:w-64 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </form>
                    </div>
                </div>

                @if($availableArticles->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($availableArticles as $article)
                    <div class="p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-4">
                            <!-- Article Image -->
                            <div class="flex-shrink-0 w-16 h-16">
                                @if($article->cover_image_url)
                                <img src="{{ $article->cover_image_url }}" 
                                     alt="{{ $article->title }}"
                                     class="w-16 h-16 object-cover rounded-lg">
                                @else
                                <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                @endif
                            </div>

                            <!-- Article Info -->
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-900 mb-1 line-clamp-1">{{ $article->title }}</h3>
                                <p class="text-sm text-gray-600 line-clamp-2 mb-2">{{ $article->excerpt }}</p>
                                <div class="flex items-center gap-3 text-xs text-gray-500">
                                    <span>{{ optional($article->author)->name ?? 'No author' }}</span>
                                    <span>•</span>
                                    <span>{{ $article->views }} views</span>
                                    <span>•</span>
                                    <span>{{ $article->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>

                            <!-- Add Button -->
                            <button onclick="addArticle({{ $article->id }})"
                                    class="flex-shrink-0 inline-flex items-center justify-center px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($availableArticles->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $availableArticles->links() }}
                </div>
                @endif
                @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">No Available Articles</h3>
                    <p class="text-gray-600">All articles are assigned or try different search</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        const assignedList = document.getElementById('assigned-articles');
        const categoryId = {{ $category->id }};
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Drag & drop for assigned articles
        if (assignedList) {
            const sortable = new Sortable(assignedList, {
                handle: '.drag-handle',
                animation: 150,
                ghostClass: 'bg-blue-50',
                onEnd: function(evt) {
                    // Get new order
                    const articles = Array.from(assignedList.querySelectorAll('.article-item')).map((item, index) => ({
                        id: parseInt(item.dataset.id),
                        order: index
                    }));
                    
                    // Send to server
                    fetch(`/admin/categories/home/${categoryId}/articles/reorder`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({ articles })
                    })
                    .then(response => response.json())
                    .then(data => {
                        showNotification(data.message, 'success');
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('Failed to update order', 'error');
                    });
                }
            });
        }

        // Add article to category
        function addArticle(articleId) {
            console.log('Adding article:', articleId);
            console.log('Category ID:', categoryId);
            console.log('URL:', `/admin/categories/home/${categoryId}/articles/add`);
            
            fetch(`/admin/categories/home/${categoryId}/articles/add`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ article_id: articleId })
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    showNotification(data.message, 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to add article', 'error');
            });
        }

        // Remove article from category
        function removeArticle(articleId) {
            if (!confirm('Remove this article from the category?')) return;
            
            fetch(`/admin/categories/home/${categoryId}/articles/${articleId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to remove article', 'error');
            });
        }

        // Update max articles
        function updateMaxArticles(value) {
            fetch(`/admin/categories/home/${categoryId}/articles/max`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ max_articles: parseInt(value) })
            })
            .then(response => response.json())
            .then(data => {
                showNotification(data.message, 'success');
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to update max articles', 'error');
            });
        }

        // Show notification
        function showNotification(message, type) {
            const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50`;
            notification.textContent = message;
            document.body.appendChild(notification);
            setTimeout(() => notification.remove(), 3000);
        }
    </script>
</body>
</html>
