<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $article->title }} - FAKTAnow</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 flex flex-col min-h-screen transition-colors duration-200">

    <!-- Navbar -->
    <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="/" class="flex items-center gap-3 group">
                    <img src="{{ asset('icons/Logo_FAKTA_now 1.png') }}" alt="FAKTAnow Logo" class="h-10 w-10 object-contain transition-transform group-hover:scale-110">
                    <div class="flex items-baseline">
                        <span class="text-2xl font-black text-red-600 tracking-tight">FAKTA</span>
                        <span class="text-2xl font-black text-gray-800 dark:text-white tracking-tight">now</span>
                    </div>
                </a>
                
                <div class="hidden md:flex items-center gap-1">
                    @foreach($categories->take(5) as $category)
                        <a href="{{ route('category.show', $category->slug) }}" 
                           class="px-3 py-2 text-sm font-bold text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition uppercase">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
                
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : (Auth::user()->role === 'editor' ? route('editor.dashboard') : route('home')) }}" 
                           class="px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:text-red-600 dark:hover:text-red-400 transition">
                            {{ Str::limit(Auth::user()->name, 15) }}
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-semibold text-gray-600 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 transition">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="px-5 py-2 text-sm font-semibold text-white bg-red-600 rounded-full hover:bg-red-700 shadow-md transition">
                            Daftar
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 w-full">
        
        <!-- Breadcrumb -->
        <nav class="mb-6 text-sm">
            <ol class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                <li><a href="/" class="hover:text-red-600 dark:hover:text-red-400 transition">Home</a></li>
                <li><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></li>
                <li><a href="{{ route('category.show', $article->category->slug ?? '#') }}" class="hover:text-red-600 dark:hover:text-red-400 transition">{{ $article->category->name ?? 'Berita' }}</a></li>
                <li><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></li>
                <li class="text-gray-700 dark:text-gray-300 font-medium">{{ Str::limit($article->title, 50) }}</li>
            </ol>
        </nav>

        <!-- Article Header -->
        <article class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8 mb-8">
            <div class="mb-6">
                <span class="inline-block bg-red-600 text-white text-xs font-bold px-4 py-1.5 rounded-full mb-4">
                    {{ $article->category->name ?? 'Berita' }}
                </span>
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-black text-gray-900 dark:text-white mb-4 leading-tight">
                    {{ $article->title }}
                </h1>
                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span>Oleh <strong class="text-red-600 dark:text-red-400">{{ $article->user->name }}</strong></span>
                    </span>
                    <span class="text-gray-400 dark:text-gray-600">•</span>
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ $article->created_at->format('d M Y, H:i') }}
                    </span>
                    <span class="text-gray-400 dark:text-gray-600">•</span>
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        {{ number_format($article->views) }} views
                    </span>
                </div>
            </div>

            <!-- Article Image -->
            <div class="mb-8 rounded-xl overflow-hidden">
                @if($article->thumbnail_url)
                    <img src="{{ $article->thumbnail_url }}" 
                         alt="{{ $article->title }}" 
                         class="w-full max-h-[500px] object-cover"
                         onerror="this.onerror=null; this.src='https://placehold.co/1200x700/dc2626/ffffff?text={{ urlencode($article->title) }}'">
                @else
                    <div class="w-full h-96 bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center">
                        <svg class="w-24 h-24 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                    </div>
                @endif
            </div>

            <!-- Article Content -->
            <div class="prose prose-lg dark:prose-invert max-w-none mb-8">
                <div class="text-gray-800 dark:text-gray-200 leading-relaxed text-lg space-y-4">
                    {!! nl2br(e($article->content)) !!}
                </div>
            </div>

            <!-- Like Button -->
            <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
                @auth
                    <form action="{{ route('articles.like', $article->slug) }}" method="POST" class="inline-block">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 rounded-lg font-semibold transition-all transform hover:scale-105 shadow-md
                            {{ $article->isLikedBy(Auth::user()) 
                                ? 'bg-red-600 text-white hover:bg-red-700' 
                                : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-red-100 dark:hover:bg-red-900/30' }}">
                            <svg class="w-5 h-5 {{ $article->isLikedBy(Auth::user()) ? 'fill-current' : '' }}" fill="{{ $article->isLikedBy(Auth::user()) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            <span>{{ $article->isLikedBy(Auth::user()) ? 'Disukai' : 'Suka' }}</span>
                            <span class="px-2 py-0.5 bg-white/20 rounded-full text-sm">{{ $article->likes_count ?? 0 }}</span>
                        </button>
                    </form>
                @else
                    <div class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        <span>{{ $article->likes_count ?? 0 }} Suka</span>
                    </div>
                @endauth
            </div>
        </article>

        <!-- Comment Section -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                Komentar ({{ $comments->count() }})
            </h2>

            @if (session('success'))
                <div class="p-4 mb-6 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-400 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Comment Form -->
            @auth
                <form action="{{ route('comments.store', $article->slug) }}" method="POST" class="mb-8">
                    @csrf
                    <div class="flex gap-3">
                        <input type="text" name="content" placeholder="Tulis komentar Anda..." 
                               class="flex-1 px-4 py-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-red-500 dark:focus:ring-red-400 focus:border-transparent transition placeholder-gray-400 dark:placeholder-gray-500"
                               required maxlength="1000">
                        <button type="submit" class="px-6 py-3 bg-red-600 text-white font-bold rounded-lg hover:bg-red-700 transition shadow-md hover:shadow-lg transform hover:scale-105">
                            Kirim
                        </button>
                    </div>
                    @error('content')
                        <span class="text-red-600 dark:text-red-400 text-sm mt-2 block">{{ $message }}</span>
                    @enderror
                </form>
            @else
                <div class="p-6 bg-gray-100 dark:bg-gray-700 rounded-lg text-center mb-8 border border-gray-200 dark:border-gray-600">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    <p class="text-gray-600 dark:text-gray-400">
                        Silakan <a href="{{ route('login') }}" class="text-red-600 dark:text-red-400 font-bold hover:underline">login</a> untuk berkomentar.
                    </p>
                </div>
            @endauth

            <!-- Comments List -->
            <div class="space-y-4">
                @forelse($comments as $comment)
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-5 border border-gray-200 dark:border-gray-600 hover:shadow-md transition">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full bg-red-600 flex items-center justify-center text-white font-bold">
                                    {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <strong class="text-gray-900 dark:text-white font-semibold">{{ $comment->user->name }}</strong>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">• {{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $comment->content }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400 text-lg">Belum ada komentar. Jadilah yang pertama berkomentar!</p>
                    </div>
                @endforelse
            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="bg-red-700 dark:bg-red-900 text-white mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 text-center border-t border-red-600 dark:border-red-800">
            <div class="flex items-center justify-center gap-2 mb-2">
                <img src="{{ asset('icons/Logo_FAKTA_now 1.png') }}" alt="FAKTAnow Logo" class="h-6 w-6 object-contain brightness-0 invert">
                <span class="text-lg font-black">FAKTAnow</span>
            </div>
            <p class="text-red-200 dark:text-red-300 text-sm">&copy; {{ date('Y') }} FAKTAnow. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
