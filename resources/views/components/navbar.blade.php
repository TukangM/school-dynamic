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
                            <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
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
                    
                    <!-- Login Button -->
                    <a href="#" class="bg-white text-[#002147] px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-100 transition-colors duration-200">
                        Login
                    </a>
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