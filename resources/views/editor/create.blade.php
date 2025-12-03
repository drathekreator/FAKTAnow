<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Artikel Baru - Author</title>
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
    </style>
</head>

<body>

    <div class="navbar">
        <h1>FAKTAnow Author</h1>
        <a href="{{ route('editor.dashboard') }}" class="btn" style="background: #444;">
            < Kembali ke Dashboard
        </a>
    </div>

    <div class="container">

        <div class="title">Tulis Artikel Baru</div>
        
        <div class="form-card">
            
            <form action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="form-group">
                    <label for="title">Judul Artikel</label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required>
                    @error('title')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="slug">Slug (URL)</label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug') }}" placeholder="Contoh: judul-artikel-keren">
                    @error('slug')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="content">Isi Konten Artikel</label>
                    <textarea id="content" name="content" required>{{ old('content') }}</textarea>
                    @error('content')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="thumbnail_file">Upload Thumbnail</label>
                    <input type="file" id="thumbnail_file" name="thumbnail_file">
                    @error('thumbnail_file')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group" style="margin-top: 30px;">
                    <button type="submit" class="btn">Simpan & Kirim Draft</button>
                    <a href="{{ route('editor.dashboard') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>

        </div>

    </div>

</body>
</html>