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
                            <img class="h-10 w-10" src="assets/image/skansev.png" alt="SMK 7 Pekanbaru Logo">
                            <span class="font-bold text-xl">SMK 7 Pekanbaru</span>
                        </a>
                    </div>
                    
                    <!-- Desktop Menu -->
                    <div class="hidden md:block ml-10">
                        <div class="flex items-baseline space-x-4">
                            <a href="#" class="px-3 py-2 text-sm font-medium hover:bg-[#003166] rounded-md transition-colors duration-200">Beranda</a>
                            <a href="#" class="px-3 py-2 text-sm font-medium hover:bg-[#003166] rounded-md transition-colors duration-200">Profil</a>
                            <a href="#" class="px-3 py-2 text-sm font-medium hover:bg-[#003166] rounded-md transition-colors duration-200">Akademik</a>
                            <a href="#" class="px-3 py-2 text-sm font-medium hover:bg-[#003166] rounded-md transition-colors duration-200">Kegiatan</a>
                            <a href="#" class="px-3 py-2 text-sm font-medium hover:bg-[#003166] rounded-md transition-colors duration-200">PPDB</a>
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
                    
                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-2 text-white hover:bg-[#003166] px-4 py-2 rounded-md">
                                <span>{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                                <div class="py-1">
                                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('dashboard') }}" class="bg-white text-[#002147] px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-100 transition-colors duration-200">
                            Login
                        </a>
                    @endauth
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
                <a href="#" class="block px-3 py-2 text-base font-medium hover:bg-[#003166] rounded-md transition-colors duration-200">Beranda</a>
                <a href="#" class="block px-3 py-2 text-base font-medium hover:bg-[#003166] rounded-md transition-colors duration-200">Profil</a>
                <a href="#" class="block px-3 py-2 text-base font-medium hover:bg-[#003166] rounded-md transition-colors duration-200">Akademik</a>
                <a href="#" class="block px-3 py-2 text-base font-medium hover:bg-[#003166] rounded-md transition-colors duration-200">Kegiatan</a>
                <a href="#" class="block px-3 py-2 text-base font-medium hover:bg-[#003166] rounded-md transition-colors duration-200">PPDB</a>
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
    </script>

    
</body>
</html>