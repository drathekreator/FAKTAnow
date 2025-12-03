<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Author</title>
    <style>
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
            max-width: 1200px;
            margin: 40px auto;
        }
        .title {
            font-size: 28px;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
        }
        .card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 12px rgba(0,0,0,0.08);
            transition: 0.2s;
        }
        .card:hover {
            transform: translateY(-3px);
        }
        .card h2 {
            margin: 0 0 10px;
            font-size: 22px;
            color: #d60000;
        }
        .card p {
            margin: 0 0 15px;
            color: #444;
        }
        .btn {
            display: inline-block;
            padding: 10px 18px;
            background: #d60000;
            color: white;
            text-decoration: none;
            border-radius: 10px;
        }
        .btn:hover {
            background: #b20000;
        }
    </style>
</head>

<body>

    <div class="navbar">
        <h1>FAKTAnow Author</h1>
        <a href="/" class="btn">Kembali ke Home</a>
    </div>

    <div class="container">

        <div class="title">Dashboard Author</div>

        <div class="grid">

            <!-- Tulis Berita -->
            <div class="card">
                <h2>Tulis Berita</h2><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Author</title>
    <style>
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
            max-width: 1200px;
            margin: 40px auto;
        }
        .title {
            font-size: 28px;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
        }
        .card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 12px rgba(0,0,0,0.08);
            transition: 0.2s;
        }
        .card:hover {
            transform: translateY(-3px);
        }
        .card h2 {
            margin: 0 0 10px;
            font-size: 22px;
            color: #d60000;
        }
        .card p {
            margin: 0 0 15px;
            color: #444;
        }
        .btn {
            display: inline-block;
            padding: 10px 18px;
            background: #d60000;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            border: none; /* Tambahkan ini agar button type="submit" terlihat sama */
            cursor: pointer;
        }
        .btn:hover {
            background: #b20000;
        }
        /* Style Tabel */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .data-table th, .data-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .data-table th {
            background-color: #f8f8f8;
            color: #333;
            font-weight: 600;
        }
        .data-table tr:hover {
            background-color: #f1f1f1;
        }
        .action-btn {
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            cursor: pointer;
            margin-right: 5px;
        }
        .btn-edit { background: #ffc107; color: #333; }
        .btn-delete { background: #d60000; color: white; border: none; }
    </style>
</head>

<body>

    <div class="navbar">
        <h1>FAKTAnow Author</h1>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn" style="background: #444;">
                Logout
            </button>
        </form>
    </div>

    <div class="container">

        <div class="title">Dashboard Penulis</div>

        @if (session('success'))
            <div style="padding: 15px; margin-bottom: 20px; border-radius: 5px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb;">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div style="padding: 15px; margin-bottom: 20px; border-radius: 5px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid">

            <div class="card">
                <h2>Tulis Berita</h2>
                <p>Buat berita baru untuk dipublikasikan.</p>
                <a href="{{ route('articles.create') }}" class="btn">Mulai Menulis</a>
            </div>

            <div class="card" style="border-left: 6px solid #d60000;">
                <h2>Total Berita Saya</h2>
                <p>Total: **{{ $articles->count() }}** Artikel</p>
                <a href="#article-list" class="btn" style="background:#444;">Lihat Semua</a>
            </div>

        </div>
        
        <hr style="margin: 50px 0; border: 0; border-top: 1px solid #ccc;">

        <div id="article-list">
            <h2 class="title" style="font-size: 24px; margin-top: 0;">Daftar Artikel Saya ({{ $articles->count() }})</h2>
            
            @if ($articles->isEmpty())
                <p style="padding: 15px; background: white; border-radius: 10px;">Anda belum memiliki artikel. Mulai menulis sekarang!</p>
            @else
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Status</th>
                            <th>Views</th>
                            <th>Terakhir Update</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($articles as $article)
                            <tr>
                                <td>{{ Str::limit($article->title, 50) }}</td>
                                <td>
                                    <span style="font-weight: bold; color: {{ $article->status == 'published' ? 'green' : ($article->status == 'draft' ? '#999' : 'orange') }};">
                                        {{ strtoupper($article->status) }}
                                    </span>
                                </td>
                                <td>{{ number_format($article->views) }}</td>
                                <td>{{ $article->updated_at->format('d M Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('articles.edit', $article->slug) }}" class="action-btn btn-edit">Edit</a>
                                    
                                    <form action="{{ route('articles.destroy', $article->slug) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus artikel: {{ $article->title }}?');" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn btn-delete">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        
    </div>

</body>
</html><h2>Berita</h2>
                <p>Buat berita baru untuk dipublikasikan.</p>
                <a href="#" class="btn">Mulai Menulis</a>
            </div>

            <!-- Kelola Berita -->

            <!-- Draft -->

        </div>

    </div>

</body>
</html>
