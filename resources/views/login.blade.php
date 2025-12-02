<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white w-full max-w-md p-8 rounded-xl shadow-lg">
        <h2 class="text-2xl font-semibold mb-1">Login</h2>
        <p class="text-gray-500 text-sm mb-6">Masuk ke akun Anda untuk melanjutkan</p>

        @if ($errors->any())
            <div class="mb-4 bg-red-100 text-red-700 p-3 rounded">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ url('/login') }}">
            @csrf

            <!-- Email -->
            <label class="block text-sm font-medium mb-1">Email</label>
            <div class="flex items-center bg-gray-100 p-3 rounded-lg mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M16 12l-4 4m0 0l-4-4m4 4V8" />
                </svg>
                <input type="email" name="email" placeholder="nama@email.com"
                    class="bg-gray-100 outline-none w-full" required value="{{ old('email') }}">
            </div>

            <!-- Password -->
            <label class="block text-sm font-medium mb-1">Password</label>
            <div class="flex items-center bg-gray-100 p-3 rounded-lg mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M12 11c.828 0 1.5-.672 1.5-1.5S12.828 8 12 8s-1.5.672-1.5 1.5S11.172 11 12 11z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M17.25 11.25v6a2.25 2.25 0 01-2.25 2.25h-6a2.25 2.25 0 01-2.25-2.25v-6A2.25 2.25 0 019 9h6a2.25 2.25 0 012.25 2.25z" />
                </svg>
                <input type="password" name="password" placeholder="Masukkan password"
                    class="bg-gray-100 outline-none w-full" required>
            </div>

            <!-- Button -->
            <button type="submit"
                class="w-full bg-red-600 text-white py-3 rounded-lg text-lg font-semibold hover:bg-red-700 transition">
                Login
            </button>

            <p class="text-center mt-4 text-sm">
                Belum punya akun?
                <a href="/register" class="text-red-600 font-semibold">Register</a>
            </p>
        </form>
    </div>

</body>
</html>
