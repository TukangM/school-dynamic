<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $article->title }} - SMK 7 Pekanbaru</title>
    <x-addons />
    <style>
        zero-md {
            display: block;
            max-width: 100%;
        }
    </style>
</head>
<body class="bg-gray-50">
    @include('components.navbar', ['navbarCategories' => $navbarCategories])

    <!-- Article Container - Matches Navbar Width -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Back Button -->
        <a href="{{ route('articles.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-[#002147] transition-colors mb-6 group">
            <svg class="w-4 h-4 mr-2 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            <span class="font-medium">Back to Articles</span>
        </a>

        <!-- Article Headline Section -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-6">
            <div class="px-6 sm:px-8 lg:px-12 py-8">
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-6 leading-tight">
                    {{ $article->title }}
                </h1>
                
                <!-- Meta Information -->
                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-6">
                    <div class="flex items-center gap-2">
                        <div class="w-10 h-10 rounded-full bg-[#002147] flex items-center justify-center text-white font-semibold">
                            {{ substr($article->author->name, 0, 1) }}
                        </div>
                        <span class="font-medium text-gray-900">{{ $article->author->name }}</span>
                    </div>
                    <span class="text-gray-300">|</span>
                    <time datetime="{{ $article->published_at->toDateString() }}">
                        {{ $article->published_at->format('d M Y') }}
                    </time>
                    <span class="text-gray-300">|</span>
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        {{ number_format($article->views) }} views
                    </span>
                </div>

                <!-- Excerpt -->
                @if($article->excerpt)
                <p class="text-lg text-gray-700 leading-relaxed border-l-4 border-[#002147] pl-4 italic mb-6">
                    {{ $article->excerpt }}
                </p>
                @endif

                <!-- Cover Image -->
                @if($article->cover_image)
                <figure>
                    <img src="{{ $article->cover_image_url }}" 
                         alt="{{ $article->title }}" 
                         class="w-[70%] h-auto max-h-[500px] sm:max-h-[550px] lg:max-h-[600px] object-contain">
                </figure>
                @endif
            </div>
        </div>

        <!-- Article Content Box -->
        <article class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
            
            <!-- Article Content -->
            <div class="px-6 sm:px-8 lg:px-12 py-8">
                <div class="prose prose-lg max-w-none">
                    <zero-md src="{{ $markdownPath }}">
                        <template>
                            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/github-markdown-css@5.8.1/github-markdown-light.css">
                        </template>
                    </zero-md>
                </div>

                <!-- Share Section -->
                <div class="mt-12 pt-8 border-t border-gray-200">
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="text-sm font-semibold text-gray-900 mr-2">Share this article:</span>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('articles.show', $article->slug)) }}" 
                           target="_blank" 
                           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                            Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('articles.show', $article->slug)) }}&text={{ urlencode($article->title) }}" 
                           target="_blank" 
                           class="inline-flex items-center gap-2 px-4 py-2 bg-sky-500 hover:bg-sky-600 text-white text-sm font-medium rounded-md transition-colors duration-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                            Twitter
                        </a>
                        <a href="https://wa.me/?text={{ urlencode($article->title . ' ' . route('articles.show', $article->slug)) }}" 
                           target="_blank" 
                           class="inline-flex items-center gap-2 px-4 py-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium rounded-md transition-colors duration-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                            </svg>
                            WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </article>

        <!-- Related Articles -->
        @if($relatedArticles->count() > 0)
        <section class="mt-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-8">Related Articles</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($relatedArticles as $related)
                <a href="{{ route('articles.show', $related->slug) }}" 
                   class="group bg-white border border-gray-200 rounded-lg overflow-hidden hover:border-[#002147] hover:shadow-md transition-all duration-200">
                    @if($related->cover_image)
                    <div class="aspect-video w-full overflow-hidden">
                        <img src="{{ $related->cover_image_url }}" 
                             alt="{{ $related->title }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                    </div>
                    @endif
                    <div class="p-5">
                        <h3 class="font-semibold text-gray-900 line-clamp-2 group-hover:text-[#002147] transition-colors mb-3 leading-snug">
                            {{ $related->title }}
                        </h3>
                        <div class="flex items-center gap-2 text-xs text-gray-500">
                            <time datetime="{{ $related->published_at->toDateString() }}">
                                {{ $related->published_at->format('d M Y') }}
                            </time>
                            <span>•</span>
                            <span>{{ number_format($related->views) }} views</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </section>
        @endif
    </div>
    @include('components.footer')
</body>
</html>
