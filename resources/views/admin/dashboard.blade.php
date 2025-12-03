<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
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
        /* Style Tabel */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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
            text-transform: uppercase;
        }
        .data-table tr:hover {
            background-color: #f1f1f1;
        }
        .action-btn {
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            cursor: pointer;
        }
        .btn-delete {
            background: #d60000;
            color: white;
            border: none;
        }
        .btn-delete:hover {
            background: #b20000;
        }
    </style>
</head>
<body>

    <div class="navbar">
        <h1>FAKTAnow Admin</h1>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn" style="background: #444; border: none; cursor: pointer;">
                Logout
            </button>
        </form>
    </div>

    <div class="container">
        <div class="title">Dashboard Admin</div>

        @if (session('success'))
            <div class="p-3 mb-4 bg-green-100 text-green-700 rounded-md shadow">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="p-3 mb-4 bg-red-100 text-red-700 rounded-md shadow">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid">
            <div class="card">
                <h2>Kelola Berita</h2>
                <p>Total Artikel Saat Ini: **{{ count($articles) }}**</p>
                <a href="{{ route('editor.dashboard') }}" class="btn">Masuk</a>
            </div>

            <div class="card" style="border-left: 6px solid red;">
                <h2>Kelola User</h2>
                <p>Total User (Non-Admin): **{{ count($users) }}**</p>
                <a href="#user-management" class="btn" style="background:#444;">Kelola User</a>
            </div>

            <div class="card">
                <h2>Laporan Berita</h2>
                <p>Lihat statistik berita & aktivitas user.</p>
                <a href="#" class="btn">Lihat</a>
            </div>
        </div>

        <hr style="margin: 50px 0; border: 0; border-top: 1px solid #ccc;">
        
        <div id="user-management">
            <h2 class="title" style="font-size: 24px;">Manajemen Pengguna ({{ count($users) }})</h2>
            
            @if ($users->isEmpty())
                <p>Tidak ada pengguna yang terdaftar selain Anda.</p>
            @else
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Terdaftar Sejak</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td><span style="font-weight: bold; color: {{ $user->role == 'editor' ? 'blue' : '#444' }};">{{ strtoupper($user->role) }}</span></td>
                                <td>{{ $user->created_at->format('d M Y') }}</td>
                                <td>
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('PERINGATAN: Yakin ingin menghapus pengguna {{ $user->name }}? Aksi ini tidak dapat dibatalkan.');" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn btn-delete">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        
        <hr style="margin: 50px 0; border: 0; border-top: 1px solid #ccc;">
        
        <div>
            <h2 class="title" style="font-size: 24px;">Manajemen Artikel</h2>
            
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($articles as $article)
                        <tr>
                            <td>{{ $article->id }}</td>
                            <td>{{ $article->title }}</td>
                            <td>{{ $article->user->name }}</td> 
                            <td>Draft/Publish</td>
                            <td>{{ $article->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('articles.edit', $article) }}" class="action-btn" style="background:#007bff; color:white;">Edit</a>
                                
                                <form action="{{ route('articles.destroy', $article) }}" method="POST" onsubmit="return confirm('Yakin hapus artikel?');" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn btn-delete">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    <tr><td colspan="6">Data artikel akan muncul di sini setelah Model Article dan Route Management Artikel diimplementasikan.</td></tr>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>