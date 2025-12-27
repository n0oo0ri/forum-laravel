<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Profil - Forum</title>
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
    <main class="max-w-3xl mx-auto px-6 py-8">
        <div class="mb-8">
            <a href="{{ route('users.profile', auth()->user()) }}"
                class="inline-block mb-6 text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200 transition-colors">←
                Kembali ke Profil</a>
            <h1 class="text-4xl font-bold">Edit Profil</h1>
        </div>

        @if ($errors->any())
            <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                <h3 class="text-red-800 dark:text-red-300 font-semibold mb-2">Terjadi kesalahan:</h3>
                <ul class="text-red-700 dark:text-red-400 text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div
                class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                <p class="text-green-800 dark:text-green-300 font-semibold">{{ session('success') }}</p>
            </div>
        @endif

        <div
            class="bg-white dark:bg-slate-900 rounded-lg shadow dark:shadow-lg border border-slate-200 dark:border-slate-800 p-8">
            <form method="POST" action="{{ route('users.update') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Avatar -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Avatar
                    </label>
                    <div class="flex items-center gap-6">
                        @if ($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar"
                                class="w-24 h-24 rounded-full object-cover">
                        @else
                            <div
                                class="w-24 h-24 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-2xl">
                                👤
                            </div>
                        @endif
                        <div class="flex flex-col sm:flex-row sm:items-center gap-4">

                            <!-- Hidden native input -->
                            <input type="file" id="avatar" name="avatar"
                                accept="image/jpeg,image/png,image/jpg,image/gif" class="hidden"
                                onchange="previewAvatar(this)">

                            <!-- Custom button -->
                            <label for="avatar"
                                class="inline-flex items-center justify-center
               w-full sm:w-auto
               px-4 py-2
               border border-slate-300 dark:border-slate-600
               rounded-lg
               bg-white dark:bg-slate-800
               text-slate-700 dark:text-slate-200
               cursor-pointer
               hover:bg-slate-50 dark:hover:bg-slate-700
               transition">

                                <!-- Upload Icon -->
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 12V4m0 0l-4 4m4-4l4 4" />
                                </svg>

                                <span class="text-sm font-medium">
                                    Pilih Foto
                                </span>
                            </label>

                            <!-- File info -->
                            <p id="avatar-filename" class="text-sm text-slate-500 dark:text-slate-400 truncate">
                                Belum ada file dipilih
                            </p>
                        </div>

                    </div>
                    @error('avatar')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Nama -->
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Nama
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                        placeholder="Masukkan nama Anda..."
                        class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg
           dark:bg-slate-800 dark:text-white
           focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required oninput="this.value = this.value.replace(/\s+/g, '')"
                        onkeydown="if(event.key === ' ') event.preventDefault();">

                    @error('name')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Email
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                        placeholder="Masukkan email Anda..."
                        class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg dark:bg-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                    @error('email')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Bio -->
                <div>
                    <label for="bio" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Bio (Opsional)
                    </label>
                    <textarea id="bio" name="bio" placeholder="Ceritakan tentang diri Anda..." rows="4"
                        class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg dark:bg-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('bio', $user->bio) }}</textarea>
                    <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Maksimal 500 karakter</p>
                    @error('bio')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Lokasi -->
                <div>
                    <label for="location" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Lokasi (Opsional)
                    </label>
                    <input type="text" id="location" name="location"
                        value="{{ old('location', $user->location) }}" placeholder="Kota atau negara Anda..."
                        class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg dark:bg-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('location')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Website -->
                <div>
                    <label for="website" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Website (Opsional)
                    </label>
                    <input type="url" id="website" name="website"
                        value="{{ old('website', $user->website) }}" placeholder="https://contoh.com"
                        class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg dark:bg-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('website')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="flex gap-4 pt-4">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition-colors">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('users.profile', auth()->user()) }}"
                        class="bg-slate-300 dark:bg-slate-700 text-slate-800 dark:text-slate-100 px-6 py-2 rounded-lg font-semibold hover:bg-slate-400 dark:hover:bg-slate-600 transition-colors">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </main>

    <script>
        document.getElementById('theme-toggle')?.addEventListener('click', () => {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('dark-mode', isDark);
            document.querySelector('#theme-toggle span').textContent = isDark ? '☀️' : '🌙';
        });

        // Set initial emoji
        if (document.documentElement.classList.contains('dark')) {
            document.querySelector('#theme-toggle span').textContent = '☀️';
        }

        // Preview avatar
        const avatarInput = document.querySelector('input[name="avatar"]');
        if (avatarInput) {
            avatarInput.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (event) => {
                        const img = document.querySelector('img[alt="Avatar"]');
                        if (img) {
                            img.src = event.target.result;
                        }
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    </script>

    <script>
        function previewAvatar(input) {
            const fileName = input.files[0]?.name ?? 'Belum ada file dipilih';
            document.getElementById('avatar-filename').textContent = fileName;
        }
    </script>

</body>

</html>
