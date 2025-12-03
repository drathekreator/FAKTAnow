<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth bg-white dark:bg-gray-950 scheme-light dark:scheme-dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FAKTAnow - Berita Terpercaya</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 dark:text-white-900 flex flex-col min-h-screen">

    <nav class="bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-red-600 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                
                <div class="flex items-center gap-8">
                    <a href="/" class="flex-shrink-0 flex items-center">
                        <span class="text-2xl font-black text-red-600 tracking-tighter">FAKTA</span>
                        <span class="text-2xl font-black text-gray-800 tracking-tighter dark:text-white">now.</span>
                    </a>

                    <div class="hidden md:flex space-x-6">
                        @foreach($categories as $category)
                            <a href="/category/{{ $category->slug }}" 
                               class="text-sm font-bold text-gray-500 hover:text-red-600 transition uppercase tracking-wide">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    
                    <form action="/" method="GET" class="hidden sm:block relative">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Cari berita..." 
                               class="w-64 pl-4 pr-10 py-2 rounded-full bg-gray-100 border-transparent focus:bg-white focus:border-red-500 focus:ring-0 text-sm transition placeholder-gray-400">
                        <button type="submit" class="absolute right-0 top-0 mt-2 mr-3 text-gray-400 hover:text-red-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </button>
                    </form>

                    @if (Route::has('login'))
                        <div class="ml-2">
                            @auth
                                <div class="relative group">
                                    <button class="flex items-center gap-2 text-sm font-bold text-gray-700 dark:text-gray-100 hover:text-red-600 transition">
                                        <span>Halo, {{ Auth::user()->name }}</span>
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </button>
                                    <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform z-50 border border-gray-100">
                                        <a href="{{ url('/dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600">Dashboard</a>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600">Log Out</button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <div class="flex gap-2">
                                    <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-bold text-gray-600 hover:text-red-600 transition">Log in</a>
                                    <a href="{{ route('register') }}" class="px-5 py-2 text-sm font-bold text-white bg-red-600 rounded-full hover:bg-red-700 shadow-md transition transform hover:-translate-y-0.5">Daftar</a>
                                </div>
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 w-full">
        
        <div class="mb-8 border-l-4 border-red-600 pl-4">
            <h1 class="text-gray-700 text-3xl font-black dark:text-white">
                {{ request('search') ? 'Hasil Pencarian: "' . request('search') . '"' : 'Berita Terkini' }}
            </h1>
            <p class="text-gray-500 mt-1">Menyajikan informasi aktual dan terpercaya untuk Anda.</p>
        </div>

        @if($articles->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($articles as $article)
                    <article class="bg-white rounded-xl shadow-sm hover:shadow-xl border border-gray-100 overflow-hidden transition duration-300 flex flex-col h-full group">
                        <div class="h-48 bg-gray-200 relative overflow-hidden">
                             <img src="https://placehold.co/600x400?text=News+Image" alt="Article Image" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <div class="absolute top-0 right-0 bg-red-600 text-white text-xs font-bold px-3 py-1 rounded-bl-lg">
                                {{-- $article->category->name ?? 'Umum' --}} Berita
                            </div>
                        </div>
                        
                        <div class="p-6 flex flex-col flex-grow">
                            <div class="flex items-center text-xs text-gray-400 mb-3 space-x-2">
                                <span>{{ $article->created_at->format('d M Y') }}</span>
                                <span>&bull;</span>
                                <span>{{ $article->user->name ?? 'Admin' }}</span>
                            </div>
                            
                            <h2 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-red-600 transition">
                                {{ $article->title }}
                            </h2>
                            
                            <p class="text-gray-600 text-sm line-clamp-3 mb-4 flex-grow">
                                {{ Str::limit(strip_tags($article->content), 100) }}
                            </p>
                            
                            <a href="#" class="inline-flex items-center text-red-600 font-bold text-sm hover:underline mt-auto">
                                Baca Selengkapnya 
                                <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-12">
                {{ $articles->links() }}
            </div>
        @else
            <div class="text-center py-20">
                <div class="inline-block p-4 rounded-full bg-gray-100 mb-4 text-gray-400">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-200">Tidak ada berita ditemukan</h3>
                <p class="text-gray-500 mt-1">Coba kata kunci lain atau kembali lagi nanti.</p>
            </div>
        @endif

    </main>

    <footer class="bg-red-700 text-white mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <div>
                    <h3 class="text-2xl font-black mb-4 tracking-tight">FAKTAnow.</h3>
                    <p class="text-red-100 text-sm leading-relaxed mb-6">
                        Platform berita digital yang menyajikan informasi terkini dengan standar jurnalisme berkualitas. Berdedikasi untuk memberikan fakta tanpa bias.
                    </p>
                    <div class="text-red-200 text-sm space-y-2">
                        <p class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Gedung Pers, Lt. 3, Jakarta Selatan
                        </p>
                        <p class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            redaksi@faktanow.com
                        </p>
                    </div>
                </div>

                <div class="md:pl-10">
                    <h4 class="text-lg font-bold mb-4 border-b border-red-500 pb-2 inline-block">Jelajahi</h4>
                    <ul class="space-y-3 text-red-100 text-sm">
                        <li><a href="#" class="hover:text-white hover:underline transition">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-white hover:underline transition">Karir</a></li>
                        <li><a href="#" class="hover:text-white hover:underline transition">Pedoman Media Siber</a></li>
                        <li><a href="#" class="hover:text-white hover:underline transition">Kebijakan Privasi</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-lg font-bold mb-4 border-b border-red-500 pb-2 inline-block">Berlangganan</h4>
                    <p class="text-red-100 text-sm mb-4">Dapatkan update berita pilihan setiap pagi di email Anda.</p>
                    <form class="flex gap-2">
                        <input type="email" placeholder="Email Anda" class="w-full px-4 py-2 rounded-lg bg-red-800 border border-red-600 text-white placeholder-red-300 focus:outline-none focus:ring-2 focus:ring-white text-sm">
                        <button class="bg-white text-red-700 font-bold px-4 py-2 rounded-lg hover:bg-gray-100 transition text-sm">Kirim</button>
                    </form>
                </div>
            </div>
            
            <div class="border-t border-red-600 mt-10 pt-6 text-center text-red-300 text-sm">
                &copy; {{ date('Y') }} FAKTAnow. All rights reserved.
            </div>
        </div>
    </footer>

</body>
</html>