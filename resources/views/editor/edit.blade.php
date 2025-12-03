<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Artikel - FAKTAnow</title>
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
                <a href="{{ route('editor.dashboard') }}" class="px-4 py-2 text-sm font-semibold text-gray-600 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 transition">
                    ← Kembali ke Dashboard
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <h2 class="text-3xl font-black text-gray-900 dark:text-white mb-8">Edit Artikel</h2>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-8">
            
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-400 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('articles.update', $article->slug) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <!-- Title -->
                <div class="mb-6">
                    <label for="title" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                        Judul Artikel <span class="text-red-600">*</span>
                    </label>
                    <input type="text" id="title" name="title" value="{{ old('title', $article->title) }}" required
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-red-500 dark:focus:ring-red-400 focus:border-transparent transition"
                           placeholder="Masukkan judul artikel yang menarik">
                    @error('title')
                        <span class="text-red-600 dark:text-red-400 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
                
                <!-- Slug -->
                <div class="mb-6">
                    <label for="slug" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                        Slug (URL)
                    </label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug', $article->slug) }}"
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-red-500 dark:focus:ring-red-400 focus:border-transparent transition"
                           placeholder="judul-artikel-keren (kosongkan untuk auto-generate)">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Biarkan kosong untuk generate otomatis dari judul</p>
                    @error('slug')
                        <span class="text-red-600 dark:text-red-400 text-sm mt-1 block">{{ $message }}</span>
                    @enderror>
                </div>

                <!-- Category -->
                <div class="mb-6">
                    <label for="category_id" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                        Kategori <span class="text-red-600">*</span>
                    </label>
                    <select id="category_id" name="category_id" required
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-red-500 dark:focus:ring-red-400 focus:border-transparent transition">
                        <option value="">Pilih Kategori</option>
                        @foreach(\App\Models\Category::all() as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $article->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <span class="text-red-600 dark:text-red-400 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Content -->
                <div class="mb-6">
                    <label for="content" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                        Isi Konten Artikel <span class="text-red-600">*</span>
                    </label>
                    <textarea id="content" name="content" required rows="15"
                              class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-red-500 dark:focus:ring-red-400 focus:border-transparent transition resize-y"
                              placeholder="Tulis konten artikel Anda di sini...">{{ old('content', $article->content) }}</textarea>
                    @error('content')
                        <span class="text-red-600 dark:text-red-400 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Thumbnail -->
                <div class="mb-8">
                    <label for="thumbnail_file" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                        Ganti Thumbnail
                    </label>
                    
                    @if ($article->thumbnail_url)
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Thumbnail saat ini:</p>
                            <div class="relative inline-block">
                                <img src="{{ $article->thumbnail_url }}" 
                                     alt="Current Thumbnail" 
                                     class="max-w-xs rounded-lg border-2 border-gray-300 dark:border-gray-600"
                                     onerror="this.onerror=null; this.src='https://placehold.co/400x300/dc2626/ffffff?text=Thumbnail'">
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Kosongkan field di bawah jika tidak ingin mengganti gambar</p>
                        </div>
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Saat ini tidak ada thumbnail yang terpasang</p>
                    @endif
                    
                    <div class="flex items-center justify-center w-full">
                        <label for="thumbnail_file" class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-10 h-10 mb-3 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF (MAX. 2MB)</p>
                            </div>
                            <input id="thumbnail_file" name="thumbnail_file" type="file" class="hidden" accept="image/*" />
                        </label>
                    </div>
                    @error('thumbnail_file')
                        <span class="text-red-600 dark:text-red-400 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
                
                <!-- Actions -->
                <div class="flex items-center gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <button type="submit" class="px-6 py-3 text-sm font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700 transition shadow-md hover:shadow-lg transform hover:scale-105">
                        Perbarui Artikel
                    </button>
                    <a href="{{ route('editor.dashboard') }}" class="px-6 py-3 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-700 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                        Batal
                    </a>
                </div>
            </form>

        </div>

    </div>

</body>
</html>
