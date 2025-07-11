<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link
    href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css"
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
/>
    <title>KPI App</title>
</head>
<body>
    <section class="bg-blue-500 min-h-screen flex items-center justify-center">
        <div class="bg-blue-100 flex shadow-lg max-w-5xl p-5 rounded-2xl">
            <div class="sm:w-1/2 px-5">
                <h2 class="font-bold text-3xl">Login</h2>
                <p class="text-sm mt-4 mb-6">Login untuk melanjutkan ke Aplikasi KPI</p>
                <form action="{{ url('auth/me') }}" method="POST" class="flex flex-col">
                    @csrf
                    <label for="email" class="font-semibold mb-0.5">Email:</label>
                    <input class="p-2 rounded-xl border-none mb-2" type="text" name="email" id="email" placeholder="Email" autocomplete="on">
                    <label for="password" class="font-semibold mb-0.5">Password:</label>
                    <input class="p-2 rounded-xl border-none mb-0" type="password" name="password" id="password" placeholder="Password" autocomplete="off">
                    <div class="mb-3 mt-1 ml-1">
                        <a href="{{ route('reset-password') }}">
                            <p class="text-blue-500">Reset Password</p>
                        </a>
                    </div>
                    <button class="bg-blue-700 text-white rounded-xl py-2">Login</button>
                </form>
            </div>
            <div class="w-2/3">
                <img src="{{ asset('image/mature-3.png') }}" alt="" class="rounded-2xl sm:block hidden w-full h-full object-cover">
            </div>
        </div>
    </section>
</body>
</html>