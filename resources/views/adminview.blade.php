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
    </style>
</head>
<body>

    <div class="navbar">
        <h1>FAKTAnow Admin</h1>
        <a href="/" class="btn">Kembali ke Home</a>
    </div>

    <div class="container">
        <div class="title">Dashboard Admin</div>

        <div class="grid">

            <!-- Kelola Berita -->
            <div class="card">
                <h2>Kelola Berita</h2>
                <p>Buat, edit, dan hapus berita di website.</p>
                <a href="#" class="btn">Masuk</a>
            </div>

            <!-- Kelola User -->
            <div class="card" style="border-left: 6px solid red;">
                <h2>Kelola User</h2>
                <p>Hapus & urus pengguna website.</p>
                <a href="#" class="btn" style="background:#444;">Kelola User</a>
            </div>

            <!-- Laporan -->
            <div class="card">
                <h2>Laporan Berita</h2>
                <p>Lihat statistik berita & aktivitas user.</p>
                <a href="#" class="btn">Lihat</a>
            </div>

        </div>
    </div>

</body>
</html>
