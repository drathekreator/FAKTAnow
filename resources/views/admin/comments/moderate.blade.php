<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moderasi Komentar - Admin</title>
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
                        <span class="text-sm font-normal text-gray-500 dark:text-gray-400 ml-2">Moderasi Komentar</span>
                    </h1>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 text-sm font-semibold text-gray-600 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 transition">
                    ← Kembali ke Dashboard
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <h2 class="text-3xl font-black text-gray-900 dark:text-white mb-8">
            Komentar Menunggu Persetujuan ({{ $comments->total() }})
        </h2>

        @if (session('success'))
            <div class="mb-6 p-4 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-400 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @forelse($comments as $comment)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-4 hover:shadow-md transition">
                <div class="flex items-start justify-between mb-4 pb-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-red-600 flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                            </div>
                        </div>
                        <div>
                            <strong class="text-gray-900 dark:text-white font-semibold">{{ $comment->user->name }}</strong>
                            <span class="text-sm text-gray-500 dark:text-gray-400"> • {{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        Artikel: <a href="{{ route('article.show', $comment->article->slug) }}" target="_blank" class="text-red-600 dark:text-red-400 hover:underline">
                            {{ Str::limit($comment->article->title, 50) }}
                        </a>
                    </div>
                </div>
                
                <div class="mb-4">
                    <p class="text-gray-800 dark:text-gray-200 leading-relaxed">{{ $comment->content }}</p>
                </div>
                
                <div class="flex items-center gap-3">
                    <form action="{{ route('admin.comments.approve', $comment) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-green-600 rounded-lg hover:bg-green-700 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Setujui
                        </button>
                    </form>
                    
                    <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus komentar ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-gray-800 rounded-xl p-12 text-center border border-gray-200 dark:border-gray-700">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <p class="text-gray-600 dark:text-gray-400 text-lg">Tidak ada komentar yang menunggu persetujuan.</p>
            </div>
        @endforelse

        <div class="mt-8">
            {{ $comments->links() }}
        </div>
    </div>

</body>
</html>
