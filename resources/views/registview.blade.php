<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Tailwind CDN (opsional, lebih gampang styling-nya) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f5f7;
        }

        /* Card shadow lembut */
        .card {
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        input:focus {
            outline: none !important;
            border-color: #d32f2f !important;
            box-shadow: 0 0 5px rgba(211,47,47,0.4);
        }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen">

    <div class="card bg-white p-10 rounded-2xl w-full max-w-md">

        <!-- Header -->
        <h2 class="text-3xl font-semibold text-gray-800 mb-2">Register</h2>
        <p class="text-gray-500 mb-6">Buat akun baru untuk memulai</p>

        <!-- Form -->
        <form action="{{ route('register.post') }}" method="POST">
            @csrf

            <!-- Nama Lengkap -->
            <label class="text-gray-600 font-medium">Nama Lengkap</label>
            <div class="flex items-center mt-1 mb-4 bg-gray-100 p-3 rounded-xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500 mr-2" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a8.25 8.25 0 1115 0"/>
                </svg>
                <input type="text" name="name" class="w-full bg-transparent" placeholder="Masukkan nama lengkap" required>
            </div>

            <!-- Email -->
            <label class="text-gray-600 font-medium">Email</label>
            <div class="flex items-center mt-1 mb-4 bg-gray-100 p-3 rounded-xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500 mr-2" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M21.75 6.75l-9.75 6-9.75-6M3 6.75v10.5A2.25 2.25 0 005.25 19.5h13.5A2.25 2.25 0 0021 17.25V6.75"/>
                </svg>
                <input type="email" name="email" class="w-full bg-transparent" placeholder="nama@email.com" required>
            </div>

            <!-- Password -->
            <label class="text-gray-600 font-medium">Password</label>
            <div class="flex items-center mt-1 mb-4 bg-gray-100 p-3 rounded-xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500 mr-2" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M16.5 10.5V7.5a4.5 4.5 0 00-9 0v3m-.75 0h10.5A1.75 1.75 0 0120 12.25v7.5A1.75 1.75 0 0118.25 21.5H5.75A1.75 1.75 0 014 19.75v-7.5A1.75 1.75 0 015.75 10.5z"/>
                </svg>
                <input type="password" name="password" class="w-full bg-transparent" placeholder="Masukkan password" required>
            </div>

            <!-- Konfirmasi Password -->
            <label class="text-gray-600 font-medium">Konfirmasi Password</label>
            <div class="flex items-center mt-1 mb-6 bg-gray-100 p-3 rounded-xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500 mr-2" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M16.5 10.5V7.5a4.5 4.5 0 00-9 0v3m-.75 0h10.5A1.75 1.75 0 0120 12.25v7.5A1.75 1.75 0 0118.25 21.5H5.75A1.75 1.75 0 014 19.75v-7.5A1.75 1.75 0 015.75 10.5z"/>
                </svg>
                <input type="password" name="password_confirmation" class="w-full bg-transparent"
                       placeholder="Ulangi password" required>
            </div>

            <!-- Button -->
            <button type="submit"
                class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 rounded-xl transition">
                Register
            </button>

        </form>

        <!-- Link Login -->
        <p class="text-center text-gray-600 mt-4">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-red-600 font-semibold hover:underline">Login</a>
        </p>

    </div>

</body>
</html>
