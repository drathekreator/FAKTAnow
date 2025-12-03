<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Editor - FAKTAnow</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-200">

    <!-- Navbar -->
    <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('icons/Logo_FAKTA_now 1.png') }}" alt="FAKTAnow Logo" class="h-8 w-8 object-contain">
                    <h1 class="text-xl font-black">
                        <span class="text-red-600">FAKTA</span><span class="text-gray-800 dark:text-white">now</span>
                        <span class="text-sm font-normal text-gray-500 dark:text-gray-400 ml-2">Editor</span>
                    </h1>
                </div>
                <div class="flex items-center gap-3">
                    <a href="/" class="px-4 py-2 text-sm font-semibold text-gray-600 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 transition">
                        Homepage
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-gray-700 dark:bg-gray-600 rounded-lg hover:bg-gray-800 dark:hover:bg-gray-700 transition">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <h2 class="text-3xl font-black text-gray-900 dark:text-white mb-8">Dashboard Penulis</h2>

        <!-- Notifications -->
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-400 rounded-lg">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-6 p-4 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-400 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Tulis Berita</h3>
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <p class="text-gray-600 dark:text-gray-400 mb-4">Buat berita baru untuk dipublikasikan</p>
                <a href="{{ route('articles.create') }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700 transition">
                    Mulai Menulis
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border-l-4 border-red-600 p-6 hover:shadow-lg transition">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Total Berita Saya</h3>
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <p class="text-4xl font-black text-red-600 mb-4">{{ $articles->count() }}</p>
                <a href="#article-list" class="inline-flex items-center text-sm font-semibold text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400">
                    Lihat Semua
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Articles List -->
        <div id="article-list">
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Daftar Artikel Saya ({{ $articles->count() }})</h3>
            
            @if ($articles->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-xl p-12 text-center border border-gray-200 dark:border-gray-700">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-gray-600 dark:text-gray-400 text-lg mb-4">Anda belum memiliki artikel</p>
                    <a href="{{ route('articles.create') }}" class="inline-flex items-center px-6 py-3 text-sm font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700 transition">
                        Mulai Menulis Sekarang
                    </a>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Judul</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Views</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Update</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($articles as $article)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ Str::limit($article->title, 50) }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-3 py-1 text-xs font-bold rounded-full
                                                {{ $article->status == 'published' ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400' : 
                                                   ($article->status == 'draft' ? 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300' : 
                                                   'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400') }}">
                                                {{ strtoupper($article->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                            {{ number_format($article->views) }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                            {{ $article->updated_at->format('d M Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('articles.edit', $article->slug) }}" 
                                                   class="px-3 py-1.5 text-sm font-semibold text-white bg-yellow-500 rounded-lg hover:bg-yellow-600 transition">
                                                    Edit
                                                </a>
                                                <form action="{{ route('articles.destroy', $article->slug) }}" method="POST" 
                                                      onsubmit="return confirm('Yakin ingin menghapus artikel: {{ $article->title }}?');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="px-3 py-1.5 text-sm font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700 transition">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
        
    </div>

</body>
</html>
