<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Articles - SMK 7 Pekanbaru</title>
    <x-addons />
</head>
<body class="bg-[#f5f5f5]">
    @include('components.navbar', ['navbarCategories' => $navbarCategories])

    <!-- Header Section -->
    <section class="bg-white border-b border-gray-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <h1 class="text-5xl font-bold text-gray-900 mb-3">SMK 7 Blog</h1>
            <p class="text-xl text-gray-600">Articles, news, and updates from SMK 7 Pekanbaru</p>
        </div>
    </section>

    <!-- Articles List -->
    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if($articles->count() > 0)
        <div class="space-y-6">
            @foreach($articles as $article)
            <article class="bg-white border border-gray-200 hover:border-[#002147] transition-colors overflow-hidden">
                <a href="{{ route('articles.show', $article->slug) }}" class="block">
                    <div class="flex flex-col md:flex-row md:h-72">
                        <!-- Article Cover Image -->
                        <div class="md:w-2/5 lg:w-1/3 h-64 md:h-full">
                            @if($article->cover_image)
                            <img src="{{ $article->cover_image_url }}" 
                                 alt="{{ $article->title }}" 
                                 class="w-full h-full object-cover">
                            @else
                            <div class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                <svg class="w-20 h-20 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            @endif
                        </div>

                        <!-- Article Content -->
                        <div class="md:w-3/5 lg:w-2/3 p-6 md:p-8 flex flex-col justify-between">
                            <div class="flex-1">
                                <!-- Title -->
                                <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3 hover:text-[#002147] transition-colors line-clamp-2">
                                    {{ $article->title }}
                                </h2>

                                <!-- Excerpt -->
                                @if($article->excerpt)
                                <p class="text-gray-600 text-base leading-relaxed mb-4 line-clamp-4">
                                    {{ $article->excerpt }}
                                </p>
                                @endif
                            </div>

                            <!-- Meta Info -->
                            <div class="flex items-center justify-between text-sm text-gray-500 pt-4 border-t border-gray-100">
                                <div class="flex items-center gap-4">
                                    <div class="flex items-center">
                                        <div class="w-6 h-6 rounded-full bg-[#002147] flex items-center justify-center text-white text-xs font-medium mr-2">
                                            {{ substr($article->author->name, 0, 1) }}
                                        </div>
                                        <span>{{ $article->author->name }}</span>
                                    </div>
                                    <span>•</span>
                                    <span>{{ $article->published_at->format('d M Y') }}</span>
                                    <span>•</span>
                                    <span>{{ number_format($article->views) }} views</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </article>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-12">
            {{ $articles->links() }}
        </div>
        @else
        <div class="text-center py-16">
            <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">No Articles Yet</h3>
            <p class="text-gray-600">Check back later for new articles and updates!</p>
        </div>
        @endif
    </main>
</body>
</html>
