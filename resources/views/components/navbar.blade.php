<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar Preview</title>
    <x-addons />
</head>
<body>
    <nav class="bg-[#002147] text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo & Brand -->
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <a href="/" class="flex items-center space-x-3">
                            <img class="h-8 w-6" src="assets/image/skansev.png" alt="SMK 7 Pekanbaru Logo">
                            <span class="font-bold text-xl">SMK 7 Pekanbaru</span>
                        </a>
                    </div>
                    
                    <!-- Desktop Menu -->
                    <div class="hidden md:block ml-10">
                        <div class="flex items-baseline space-x-4">
                            @forelse($navbarCategories ?? [] as $category)
                                @if($category->subcategories && $category->subItems->count() > 0)
                                    <!-- Category with Subcategories (Dropdown) -->
                                    <div class="relative group">
                                        <button class="px-3 py-2 text-sm font-medium hover:bg-[#003166] rounded-md transition-colors duration-200 flex items-center space-x-1">
                                            <span>{{ $category->display_name }}</span>
                                            <svg class="w-4 h-4 transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </button>
                                        
                                        <!-- Dropdown Menu -->
                                        <div class="absolute left-0 mt-2 w-56 rounded-xl shadow-2xl overflow-hidden backdrop-blur-xl bg-white/90 border border-white/20 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                            <div class="py-2">
                                                @foreach($category->subItems as $subcategory)
                                                    <a href="{{ $subcategory->path ?? '#' }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-white/80 transition-all duration-150">
                                                        <svg class="w-3 h-3 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                        </svg>
                                                        {{ $subcategory->display_name }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <!-- Regular Category Link -->
                                    <a href="{{ $category->path ?? '#' }}" class="px-3 py-2 text-sm font-medium hover:bg-[#003166] rounded-md transition-colors duration-200">
                                        {{ $category->display_name }}
                                    </a>
                                @endif
                            @empty
                                <!-- Fallback if no categories -->
                                <a href="/" class="px-3 py-2 text-sm font-medium hover:bg-[#003166] rounded-md transition-colors duration-200">Beranda</a>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Right Side Items -->
                <div class="hidden md:flex items-center space-x-4">
                    <!-- Search -->
                    <div class="relative">
                        <input type="text" 
                               class="bg-[#003166] text-white px-4 py-1 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-white/50 w-64"
                               placeholder="Cari...">
                        <button class="absolute right-3 top-1/2 transform -translate-y-1/2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Login/User Section (hidden by default, shown when logged in or xxxa pressed) -->
                    <div id="auth-section" class="{{ Auth::check() ? '' : 'hidden' }}">
                        @auth
                            <div class="relative z-50">
                                <button id="user-dropdown-btn" class="flex items-center space-x-2 text-white hover:bg-[#003166] px-4 py-2 rounded-md transition-all duration-200">
                                    <div class="h-8 w-8 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-white font-semibold">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                    <span>{{ Auth::user()->name }}</span>
                                    <svg class="w-4 h-4 transition-transform duration-200" id="dropdown-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div id="user-dropdown" class="hidden absolute right-0 mt-2 w-56 rounded-xl shadow-2xl overflow-hidden backdrop-blur-xl bg-white/90 border border-white/20" style="backdrop-filter: blur(20px);">
                                    <div class="py-2">
                                        <div class="px-4 py-3 border-b border-gray-200/50">
                                            <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                                            <p class="text-xs text-gray-500 truncate mt-0.5">{{ Auth::user()->email }}</p>
                                            <p class="text-xs text-gray-400 mt-1">{{ ucfirst(Auth::user()->role) }}</p>
                                        </div>
                                        <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-white/80 transition-all duration-150">
                                            <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                            </svg>
                                            Dashboard
                                        </a>
                                        <a href="/" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-white/80 transition-all duration-150">
                                            <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                            </svg>
                                            View Site
                                        </a>
                                        <div class="border-t border-gray-200/50 mt-1"></div>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="flex items-center w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50/80 transition-all duration-150">
                                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                                </svg>
                                                Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="bg-white text-[#002147] px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-100 transition-colors duration-200">
                                Login
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Mobile menu button -->
                <div class="flex md:hidden">
                    <button type="button" class="mobile-menu-button inline-flex items-center justify-center p-2 rounded-md hover:bg-[#003166] focus:outline-none transition-colors duration-200">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="mobile-menu hidden md:hidden border-t border-[#003166]">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                @forelse($navbarCategories ?? [] as $category)
                    @if($category->subcategories && $category->subItems->count() > 0)
                        <!-- Category with Subcategories -->
                        <div class="mobile-dropdown-parent">
                            <button class="w-full text-left px-3 py-2 text-base font-medium hover:bg-[#003166] rounded-md transition-colors duration-200 flex items-center justify-between"
                                    onclick="this.nextElementSibling.classList.toggle('hidden')">
                                <span>{{ $category->display_name }}</span>
                                <svg class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div class="hidden pl-4 space-y-1 mt-1">
                                @foreach($category->subItems as $subcategory)
                                    <a href="{{ $subcategory->path ?? '#' }}" class="block px-3 py-2 text-sm text-gray-300 hover:bg-[#003166] rounded-md transition-colors duration-200">
                                        {{ $subcategory->display_name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <!-- Regular Category Link -->
                        <a href="{{ $category->path ?? '#' }}" class="block px-3 py-2 text-base font-medium hover:bg-[#003166] rounded-md transition-colors duration-200">
                            {{ $category->display_name }}
                        </a>
                    @endif
                @empty
                    <!-- Fallback if no categories -->
                    <a href="/" class="block px-3 py-2 text-base font-medium hover:bg-[#003166] rounded-md transition-colors duration-200">Beranda</a>
                @endforelse
            </div>
        </div>
    </nav>

    <script>
        // Mobile menu toggle
        const btn = document.querySelector(".mobile-menu-button");
        const menu = document.querySelector(".mobile-menu");

        btn.addEventListener("click", () => {
            menu.classList.toggle("hidden");
        });

        // User dropdown toggle (if logged in)
        const userDropdownBtn = document.getElementById('user-dropdown-btn');
        const userDropdown = document.getElementById('user-dropdown');
        
        userDropdownBtn?.addEventListener('click', (e) => {
            e.stopPropagation();
            userDropdown.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (userDropdownBtn && !userDropdownBtn.contains(e.target) && !userDropdown?.contains(e.target)) {
                userDropdown?.classList.add('hidden');
            }
        });

        // Keybind "xxxa" to show login button
        let keySequence = '';
        let keyTimeout;

        document.addEventListener('keypress', (e) => {
            clearTimeout(keyTimeout);
            keySequence += e.key.toLowerCase();
            
            // Check if sequence matches "xxxa"
            if (keySequence === 'xxxa') {
                const authSection = document.getElementById('auth-section');
                authSection?.classList.remove('hidden');
                keySequence = ''; // Reset
            }
            
            // Reset sequence after 2 seconds of no typing
            keyTimeout = setTimeout(() => {
                keySequence = '';
            }, 2000);
            
            // Keep only last 4 characters to prevent memory issues
            if (keySequence.length > 4) {
                keySequence = keySequence.slice(-4);
            }
        });
    </script>

    
</body>
</html>