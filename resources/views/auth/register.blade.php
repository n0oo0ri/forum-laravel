<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - {{ config('app.name', 'Forum') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#1b1b18',
                        secondary: '#706f6c',
                    }
                }
            }
        }
    </script>
    <script>
        if (localStorage.getItem('dark-mode') === 'true' ||
            (!localStorage.getItem('dark-mode') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
</head>

<body class="bg-white dark:bg-slate-950 text-slate-900 dark:text-slate-100 transition-colors duration-200">
    <!-- Header -->
    <header
        class="sticky top-0 z-50 border-b border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 transition-colors">
        <div class="w-full px-6 py-3">
            <div class="flex items-center justify-between">
                <!-- LEFT: Logo -->
                <a href="{{ route('home') }}" class="text-2xl font-bold">Forum</a>

                <!-- THEME TOGGLE (SVG) -->
                <button id="theme-toggle" aria-label="Toggle dark mode"
                    class="p-2 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                    <!-- Moon -->
                    <svg class="w-5 h-5 text-slate-700 dark:hidden" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" />
                    </svg>

                    <!-- Sun -->
                    <svg class="w-5 h-5 text-yellow-400 hidden dark:block" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="5" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 1v2m0 18v2m11-11h-2M3 12H1 m16.95 6.95l-1.414-1.414M6.464 6.464L5.05 5.05 m12.9 0l-1.414 1.414M6.464 17.536L5.05 18.95" />
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="mt-20 max-w-md mx-auto px-6 py-8">
        <div
            class="bg-white dark:bg-slate-900 rounded-lg shadow dark:shadow-lg p-8 border border-slate-200 dark:border-slate-800">
            <h2 class="text-3xl font-bold mb-2">Daftar</h2>
            <p class="text-slate-600 dark:text-slate-400 mb-6">Buat akun baru untuk bergabung</p>

            @if ($errors->any())
                <div
                    class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 p-4 rounded-lg mb-6">
                    @foreach ($errors->all() as $error)
                        <p class="text-sm">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium mb-2">Nama</label>
                    <input type="text" name="name" id="name" required value="{{ old('name') }}"
                        placeholder="Nama Anda (tanpa spasi)"
                        class="w-full px-4 py-2
           border border-slate-300 dark:border-slate-600
           rounded-lg dark:bg-slate-800 dark:text-white
           focus:outline-none focus:ring-2
           focus:ring-slate-900 dark:focus:ring-slate-100"
                        onkeydown="if (event.key === ' ') event.preventDefault();"
                        oninput="this.value = this.value.replace(/\s+/g, '')">

                </div>

                <div>
                    <label for="email" class="block text-sm font-medium mb-2">Email</label>
                    <input type="email" name="email" id="email" required value="{{ old('email') }}"
                        class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg dark:bg-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-slate-900 dark:focus:ring-slate-100"
                        placeholder="email@example.com">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium mb-2">Password</label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg dark:bg-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-slate-900 dark:focus:ring-slate-100"
                        placeholder="••••••••">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium mb-2">Konfirmasi
                        Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg dark:bg-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-slate-900 dark:focus:ring-slate-100"
                        placeholder="••••••••">
                </div>

                <button type="submit"
                    class="w-full bg-slate-900 dark:bg-slate-100 text-white dark:text-slate-900 py-2 rounded-lg font-semibold hover:bg-slate-800 dark:hover:bg-slate-200 transition-colors mt-6">
                    Daftar
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-slate-600 dark:text-slate-400">
                Sudah punya akun? <a href="{{ route('login') }}" class="font-semibold hover:underline">Masuk di sini</a>
            </p>
        </div>
    </main>

    <script>
        document.getElementById('theme-toggle')?.addEventListener('click', () => {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('dark-mode', isDark);
        });
    </script>
</body>

</html>
