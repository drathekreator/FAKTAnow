<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FAKTAnow - Berita Terpercaya</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-200">

    <!-- Navbar -->
    <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Top Navbar -->
            <div class="flex justify-between items-center h-20">
                <!-- Logo & Brand -->
                <a href="/" class="flex items-center gap-3 group">
                    <img src="{{ asset('icons/Logo_FAKTA_now 1.png') }}" alt="FAKTAnow Logo" class="h-10 w-10 object-contain transition-transform group-hover:scale-110">
                    <div class="flex items-baseline">
                        <span class="text-2xl font-black text-red-600 tracking-tight">FAKTA</span>
                        <span class="text-2xl font-black text-gray-800 dark:text-white tracking-tight">now</span>
                    </div>
                </a>

                <!-- Search Bar -->
                <form action="/" method="GET" class="hidden md:block flex-1 max-w-md mx-8">
                    <div class="relative">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Cari berita..." 
                               class="w-full pl-4 pr-10 py-2.5 rounded-full bg-gray-100 dark:bg-gray-700 border-transparent focus:bg-white dark:focus:bg-gray-600 focus:border-red-500 focus:ring-2 focus:ring-red-500 dark:focus:ring-red-400 text-sm transition placeholder-gray-400 dark:placeholder-gray-500 text-gray-900 dark:text-gray-100">
                        <button type="submit" class="absolute right-0 top-0 mt-2.5 mr-3 text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </button>
                    </div>
                </form>

                <!-- Auth Buttons -->
                <div class="flex items-center gap-3">
                    @auth
                        <div class="relative group">
                            <button class="flex items-center gap-2 px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:text-red-600 dark:hover:text-red-400 transition rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span>{{ Str::limit(Auth::user()->name, 15) }}</span>
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-lg shadow-xl py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform border border-gray-200 dark:border-gray-700">
                                @if(Auth::user()->role === 'admin')
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-red-50 dark:hover:bg-gray-700 hover:text-red-600 dark:hover:text-red-400 transition">
                                        <span class="flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                            Dashboard Admin
                                        </span>
                                    </a>
                                @elseif(Auth::user()->role === 'editor')
                                    <a href="{{ route('editor.dashboard') }}" class="block px-4 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-red-50 dark:hover:bg-gray-700 hover:text-red-600 dark:hover:text-red-400 transition">
                                        <span class="flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            Dashboard Editor
                                        </span>
                                    </a>
                                @endif
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-red-50 dark:hover:bg-gray-700 hover:text-red-600 dark:hover:text-red-400 transition">
                                    <span class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        Pengaturan
                                    </span>
                                </a>
                                <hr class="my-2 border-gray-200 dark:border-gray-700">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2.5 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-gray-700 transition">
                                        <span class="flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                            Keluar
                                        </span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-semibold text-gray-600 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 transition">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="px-5 py-2 text-sm font-semibold text-white bg-red-600 rounded-full hover:bg-red-700 shadow-md transition transform hover:scale-105">
                            Daftar
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Category Navigation -->
            <div class="border-t border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-1 py-3 overflow-x-auto scrollbar-hide">
                    <a href="/" class="px-4 py-2 text-sm font-bold {{ !request('category') && !request('search') ? 'text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20' : 'text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700' }} rounded-lg transition whitespace-nowrap">
                        BERANDA
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ route('category.show', $category->slug) }}" 
                           class="px-4 py-2 text-sm font-bold {{ request()->is('category/'.$category->slug) ? 'text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20' : 'text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700' }} rounded-lg transition uppercase whitespace-nowrap">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Mobile Search -->
        <div class="md:hidden px-4 pb-3">
            <form action="/" method="GET">
                <div class="relative">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Cari berita..." 
                           class="w-full pl-4 pr-10 py-2.5 rounded-full bg-gray-100 dark:bg-gray-700 border-transparent focus:bg-white dark:focus:bg-gray-600 focus:border-red-500 focus:ring-2 focus:ring-red-500 text-sm transition placeholder-gray-400 dark:placeholder-gray-500 text-gray-900 dark:text-gray-100">
                    <button type="submit" class="absolute right-0 top-0 mt-2.5 mr-3 text-gray-400 hover:text-red-600 dark:hover:text-red-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl md:text-4xl font-black text-gray-900 dark:text-white mb-2">
                @if(request('search'))
                    Hasil Pencarian: "{{ request('search') }}"
                @elseif(request()->is('category/*'))
                    {{ $categories->where('slug', request()->segment(2))->first()->name ?? 'Kategori' }}
                @else
                    Berita Terkini
                @endif
            </h1>
            <p class="text-gray-600 dark:text-gray-400">Menyajikan informasi FAHHHtual dan terpercaya untuk Anda</p>
        </div>

        @if (session('error'))
            <div class="mb-6 p-4 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-400 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Articles Grid -->
        @if($articles->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach($articles as $article)
                    <article class="bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-300 flex flex-col group">
                        <!-- Thumbnail -->
                        <div class="relative h-48 bg-gray-200 dark:bg-gray-700 overflow-hidden">
                            @if($article->thumbnail_url)
                                <img src="{{ $article->thumbnail_url }}" 
                                     alt="{{ $article->title }}" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition duration-500"
                                     onerror="this.onerror=null; this.src='https://placehold.co/600x400/dc2626/ffffff?text={{ urlencode($article->title) }}'">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-red-500 to-red-700">
                                    <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                    </svg>
                                </div>
                            @endif
                            <div class="absolute top-3 right-3">
                                <span class="inline-block bg-red-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">
                                    {{ $article->category->name ?? 'Berita' }}
                                </span>
                            </div>
                        </div>
                        
                        <!-- Content -->
                        <div class="p-5 flex flex-col flex-grow">
                            <!-- Meta -->
                            <div class="flex items-center text-xs text-gray-500 dark:text-gray-400 mb-3 gap-2">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $article->created_at->format('d M Y') }}
                                </span>
                                <span>&bull;</span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    {{ $article->user->name ?? 'Admin' }}
                                </span>
                            </div>
                            
                            <!-- Title -->
                            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-3 line-clamp-2 group-hover:text-red-600 dark:group-hover:text-red-400 transition leading-tight">
                                {{ $article->title }}
                            </h2>
                            
                            <!-- Excerpt -->
                            <p class="text-gray-600 dark:text-gray-400 text-sm line-clamp-3 mb-4 flex-grow leading-relaxed">
                                {{ Str::limit(strip_tags($article->content), 120) }}
                            </p>
                            
                            <!-- Footer -->
                            <div class="flex items-center justify-between mt-auto pt-4 border-t border-gray-100 dark:border-gray-700">
                                <a href="{{ route('article.show', $article->slug) }}" class="inline-flex items-center text-red-600 dark:text-red-400 font-semibold text-sm hover:gap-2 transition-all group/link">
                                    Baca Selengkapnya
                                    <svg class="ml-1 w-4 h-4 group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                    </svg>
                                </a>
                                <div class="flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                        </svg>
                                        {{ $article->likes_count ?? 0 }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        {{ number_format($article->views) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $articles->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-20">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-100 dark:bg-gray-800 mb-6">
                    <svg class="w-10 h-10 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Tidak ada berita ditemukan</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    @if(request('search'))
                        Coba kata kunci lain atau <a href="/" class="text-red-600 dark:text-red-400 hover:underline">kembali ke beranda</a>
                    @else
                        Belum ada artikel yang dipublikasikan
                    @endif
                </p>
            </div>
        @endif

    </main>

    <!-- Footer -->
    <footer class="bg-red-700 dark:bg-red-900 text-white mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <!-- About -->
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <img src="{{ asset('icons/Logo_FAKTA_now 1.png') }}" alt="FAKTAnow Logo" class="h-8 w-8 object-contain brightness-0 invert">
                        <h3 class="text-2xl font-black tracking-tight">FAKTAnow</h3>
                    </div>
                    <p class="text-red-100 dark:text-red-200 text-sm leading-relaxed mb-6">
                        Platform berita FAHHHtual dan terkini. Sesuai logo perusahaan, platform ini menjunjung tinggi kredibilitas berita karena seperti kata pepatah "sudah jatuh ketiban FAKTA".
                    </p>
                    <div class="text-red-200 dark:text-red-300 text-sm space-y-2">
                        <p class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Bogor, Indonesia
                        </p>
                        <p class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            faktanow@gmail.com
                        </p>
                    </div>
                </div>

                <!-- Links -->
                <div class="md:pl-10">
                    <h4 class="text-lg font-bold mb-4 border-b border-red-600 dark:border-red-800 pb-2 inline-block">Jelajahi</h4>
                    <ul class="space-y-3 text-red-100 dark:text-red-200 text-sm">
                        <li><a href="#" class="hover:text-white hover:underline transition">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-white hover:underline transition">Karir</a></li>
                        <li><a href="#" class="hover:text-white hover:underline transition">Pedoman Media Siber</a></li>
                        <li><a href="#" class="hover:text-white hover:underline transition">Kebijakan Privasi</a></li>
                    </ul>
                </div>

                <!-- Newsletter -->
                <div>
                    <h4 class="text-lg font-bold mb-4 border-b border-red-600 dark:border-red-800 pb-2 inline-block">Berlangganan</h4>
                    <p class="text-red-100 dark:text-red-200 text-sm mb-4">Dapatkan update berita pilihan setiap pagi di email Anda.</p>
                    <form class="flex gap-2">
                        <input type="email" placeholder="Email Anda" class="flex-1 px-4 py-2 rounded-lg bg-red-800 dark:bg-red-950 border border-red-600 dark:border-red-800 text-white placeholder-red-300 dark:placeholder-red-400 focus:outline-none focus:ring-2 focus:ring-white text-sm">
                        <button class="bg-white text-red-700 dark:text-red-900 font-bold px-4 py-2 rounded-lg hover:bg-gray-100 transition text-sm whitespace-nowrap">
                            Kirim
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Copyright -->
            <div class="border-t border-red-600 dark:border-red-800 mt-10 pt-6 text-center text-red-300 dark:text-red-400 text-sm">
               Semua Copyright Hanya Milik Allah SWT &copy; {{ date('Y') }} FAKTAnow. All rights reserved. 
            </div>
        </div>
    </footer>

</body>
</html>
