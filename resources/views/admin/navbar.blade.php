<nav class="bg-[#002147] text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo & Brand -->
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3">
                        <span class="font-bold text-xl">Dashboard Management</span>
                    </a>
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden md:block ml-10">
                    <div class="flex items-baseline space-x-4">
                        <a href="{{ route('admin.dashboard') }}" 
                           class="px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-[#003166]' : 'hover:bg-[#003166]' }} rounded-md transition-colors duration-200">
                            Dashboard
                        </a>
                        <a href="{{ route('admin.categories.index') }}" 
                           class="px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.categories.*') ? 'bg-[#003166]' : 'hover:bg-[#003166]' }} rounded-md transition-colors duration-200">
                            Categories
                        </a>
                        <a href="{{ route('admin.articles.index') }}" 
                           class="px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.articles.*') ? 'bg-[#003166]' : 'hover:bg-[#003166]' }} rounded-md transition-colors duration-200">
                            Articles
                        </a>
                        @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.users.index') }}" 
                           class="px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.users.*') ? 'bg-[#003166]' : 'hover:bg-[#003166]' }} rounded-md transition-colors duration-200">
                            Users
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Side Items -->
            <div class="hidden md:flex items-center space-x-4">
                <div class="relative z-50">
                    <button id="user-menu-button" class="flex items-center space-x-2 text-white hover:bg-[#003166] px-4 py-2 rounded-md transition-all duration-200">
                        <div class="h-8 w-8 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-white font-semibold">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <span>{{ Auth::user()->name }}</span>
                        <svg class="w-4 h-4 transition-transform duration-200" id="dropdown-arrow-admin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="dropdown-user" class="hidden absolute right-0 mt-2 w-56 rounded-xl shadow-2xl overflow-hidden bg-white border border-gray-200">
                        <div class="py-2">
                            <div class="px-4 py-3 border-b border-gray-200/50">
                                <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 truncate mt-0.5">{{ Auth::user()->email }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ ucfirst(Auth::user()->role) }}</p>
                            </div>
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-all duration-150">
                                <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                Dashboard
                            </a>
                            <a href="/" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-all duration-150">
                                <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                </svg>
                                View Site
                            </a>
                            <div class="border-t border-gray-200/50 mt-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-all duration-150">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    Sign out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile menu button -->
            <div class="flex md:hidden">
                <button type="button" id="mobile-menu-button" class="inline-flex items-center justify-center p-2 rounded-md hover:bg-[#003166] focus:outline-none transition-colors duration-200">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden border-t border-[#003166]">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            <a href="{{ route('admin.dashboard') }}" 
               class="block px-3 py-2 text-base font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-[#003166]' : 'hover:bg-[#003166]' }} rounded-md transition-colors duration-200">
                Dashboard
            </a>
            <a href="{{ route('admin.categories.index') }}" 
               class="block px-3 py-2 text-base font-medium {{ request()->routeIs('admin.categories.*') ? 'bg-[#003166]' : 'hover:bg-[#003166]' }} rounded-md transition-colors duration-200">
                Categories
            </a>
            <a href="{{ route('admin.articles.index') }}" 
               class="block px-3 py-2 text-base font-medium {{ request()->routeIs('admin.articles.*') ? 'bg-[#003166]' : 'hover:bg-[#003166]' }} rounded-md transition-colors duration-200">
                Articles
            </a>
            @if(Auth::user()->role === 'admin')
            <a href="{{ route('admin.users.index') }}" 
               class="block px-3 py-2 text-base font-medium {{ request()->routeIs('admin.users.*') ? 'bg-[#003166]' : 'hover:bg-[#003166]' }} rounded-md transition-colors duration-200">
                Users
            </a>
            @endif
        </div>
        <div class="pt-4 pb-3 border-t border-[#003166]">
            <div class="flex items-center px-4">
                <div class="flex-shrink-0">
                    <div class="h-10 w-10 rounded-full bg-white/20 flex items-center justify-center text-white font-semibold">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </div>
                <div class="ml-3">
                    <div class="text-base font-medium">{{ Auth::user()->name }}</div>
                    <div class="text-sm font-medium text-white/70">{{ Auth::user()->email }}</div>
                </div>
            </div>
            <div class="mt-3 space-y-1 px-2">
                <a href="/" class="block px-3 py-2 text-base font-medium hover:bg-[#003166] rounded-md transition-colors duration-200">View Site</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-3 py-2 text-base font-medium text-red-300 hover:bg-[#003166] rounded-md transition-colors duration-200">
                        Sign out
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

<script>
    // Toggle user dropdown
    const userMenuButton = document.getElementById('user-menu-button');
    const dropdownUser = document.getElementById('dropdown-user');
    
    userMenuButton?.addEventListener('click', (e) => {
        e.stopPropagation();
        dropdownUser.classList.toggle('hidden');
    });

    // Toggle mobile menu
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    
    mobileMenuButton?.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', (e) => {
        if (!userMenuButton?.contains(e.target) && !dropdownUser?.contains(e.target)) {
            dropdownUser?.classList.add('hidden');
        }
    });
</script>
