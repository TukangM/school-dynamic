<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda - SMK 7 Pekanbaru</title>
</head>
<body class="bg-gray-50">
    @include('components.navbar')

    <!-- Hero Section with Carousel -->
    <section class="relative h-[300px] md:h-[400px] lg:h-[500px] mb-8">
        <div class="relative h-full">
            <img src="assets/image/halaman.png" alt="Hero image" class="w-full h-full object-cover">
            <div class="absolute bottom-0 left-0 right-0 p-4 md:p-6 lg:p-8 bg-gradient-to-t from-black/70 to-transparent">
                <div class="max-w-6xl mx-auto">
                    <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold text-white mb-2">Welcome to SMK 7 Pekanbaru</h1>
                    <p class="text-sm md:text-base text-white/90">Discover your potential with us</p>
                </div>
            </div>
        </div>
    </section>

    <main class="max-w-6xl mx-auto px-4">
        <!-- Dynamic Category Sections -->
        @foreach($categories as $category)
        @if($category->is_active && $category->articles->count() > 0)
        <section class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold">{{ strtoupper($category->display_name) }}</h2>
                    @if($category->description)
                    <p class="text-sm text-gray-600 mt-1">{{ $category->description }}</p>
                    @endif
                </div>
                @if($category->has_more)
                <a href="{{ route('category.show', $category->slug) }}" 
                   class="text-blue-600 hover:text-blue-800 hover:underline text-sm font-medium">
                    View All
                </a>
                @endif
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($category->articles as $article)
                <a href="{{ route('articles.show', $article->slug) }}" class="group bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                    <!-- Article Image -->
                    <div class="relative h-48 overflow-hidden">
                        @if($article->cover_image_url)
                        <img src="{{ $article->cover_image_url }}" 
                             alt="{{ $article->title }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                        <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        @endif
                    </div>

                    <!-- Article Content -->
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">
                            {{ $article->title }}
                        </h3>
                        <p class="text-xs text-gray-500 mb-2">
                            {{ $article->created_at->format('d M Y') }}
                        </p>
                        <p class="text-sm text-gray-600 line-clamp-3">
                            {{ $article->excerpt }}
                        </p>
                    </div>
                </a>
                @endforeach
            </div>
        </section>
        @endif
        @endforeach
    </main>
    @include('components.footer')
</body>
</html>