<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Artikel - {{ Str::limit($article->title, 20) }}</title>
    <style>
        /* Menggunakan ulang CSS dari Dashboard Author */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: #f4f4f4;
        }
        .navbar {
            background: white;
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .navbar h1 {
            color: #d60000;
            margin: 0;
        }
        .container {
            width: 95%;
            max-width: 900px; /* Lebar lebih kecil untuk formulir */
            margin: 40px auto;
        }
        .title {
            font-size: 28px;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .form-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 12px rgba(0,0,0,0.08);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }
        .form-group input[type="text"],
        .form-group input[type="file"],
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 16px;
        }
        .form-group textarea {
            min-height: 250px;
            resize: vertical;
        }
        .btn {
            display: inline-block;
            padding: 10px 18px;
            background: #d60000;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        .btn:hover {
            background: #b20000;
        }
        .btn-secondary {
            background: #6c757d;
            margin-left: 10px;
        }
        .btn-secondary:hover {
            background: #5a6268;
        }
        .error-message {
            color: #d60000;
            font-size: 14px;
            margin-top: 5px;
            display: block;
        }
        .thumbnail-preview {
            margin-top: 10px;
            border: 1px solid #eee;
            padding: 5px;
            border-radius: 5px;
            max-width: 200px;
        }
        .thumbnail-preview img {
            max-width: 100%;
            height: auto;
            border-radius: 3px;
        }
    </style>
</head>

<body>

    <div class="navbar">
        <h1>FAKTAnow Author</h1>
        <!-- Link kembali ke dashboard editor -->
        <a href="{{ route('editor.dashboard') }}" class="btn" style="background: #444;">
            < Kembali ke Dashboard
        </a>
    </div>

    <div class="container">

        <div class="title">Edit Artikel: {{ Str::limit($article->title, 50) }}</div>
        
        <div class="form-card">
            
            <!-- NOTIFIKASI SUKSES/ERROR -->
            @if (session('success'))
                <div style="padding: 15px; margin-bottom: 20px; border-radius: 5px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb;">
                    {{ session('success') }}
                </div>
            @endif

            <!-- FORMULIR UPDATE ARTIKEL -->
            <form action="{{ route('articles.update', $article->slug) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <!-- Field Judul -->
                <div class="form-group">
                    <label for="title">Judul Artikel</label>
                    <input type="text" id="title" name="title" 
                           value="{{ old('title', $article->title) }}" required>
                    @error('title')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                
                <!-- Field Slug -->
                <div class="form-group">
                    <label for="slug">Slug (URL)</label>
                    <input type="text" id="slug" name="slug" 
                           value="{{ old('slug', $article->slug) }}" placeholder="Contoh: judul-artikel-keren">
                    @error('slug')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Field Konten -->
                <div class="form-group">
                    <label for="content">Isi Konten Artikel</label>
                    <textarea id="content" name="content" required>{{ old('content', $article->content) }}</textarea>
                    @error('content')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Field Thumbnail URL / File -->
                <div class="form-group">
                    <label for="thumbnail_file">Ganti Thumbnail</label>
                    
                    @if ($article->thumbnail_url)
                        <p style="margin-bottom: 5px; font-size: 14px; color: #555;">Thumbnail saat ini:</p>
                        <div class="thumbnail-preview">
                            <img src="{{ asset($article->thumbnail_url) }}" alt="Thumbnail Artikel">
                        </div>
                        <p style="margin-top: 10px; font-size: 12px; color: #777;">Kosongkan field di bawah jika tidak ingin mengganti gambar.</p>
                    @else
                        <p style="font-size: 14px; color: #999;">Saat ini tidak ada thumbnail yang terpasang.</p>
                    @endif
                    
                    <!-- Input file untuk upload baru -->
                    <input type="file" id="thumbnail_file" name="thumbnail_file" style="margin-top: 10px;">
                    @error('thumbnail_file')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                
                <!-- Tombol Aksi -->
                <div class="form-group" style="margin-top: 30px;">
                    <button type="submit" class="btn">Perbarui Artikel</button>
                    <!-- Tombol untuk kembali -->
                    <a href="{{ route('editor.dashboard') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>

        </div>

    </div>

</body>
</html>


