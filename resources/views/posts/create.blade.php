<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Buat Postingan - Forum</title>
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
        class="sticky top-0 z-50
           border-b border-slate-200 dark:border-slate-700
           bg-white dark:bg-slate-900
           transition-colors">
        <div class="w-full px-6 py-3">
            <div class="flex items-center justify-between">
                <!-- LEFT: Logo (UJUNG KIRI) -->
                <a href="{{ route('home') }}" class="text-2xl font-bold">
                    Forum
                </a>

                <!-- RIGHT: Theme Toggle (UJUNG KANAN) -->
                <button id="theme-toggle" aria-label="Toggle dark mode"
                    class="p-2 rounded-lg
                       bg-slate-100 dark:bg-slate-800
                       hover:bg-slate-200 dark:hover:bg-slate-700
                       transition-colors">
                    <!-- Moon -->
                    <svg class="w-5 h-5 text-slate-700 dark:hidden" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3
                             a7 7 0 009.79 9.79z" />
                    </svg>

                    <!-- Sun -->
                    <svg class="w-5 h-5 text-yellow-400 hidden dark:block" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="5" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 1v2m0 18v2
                             m11-11h-2M3 12H1
                             m16.95 6.95l-1.414-1.414
                             M6.464 6.464L5.05 5.05
                             m12.9 0l-1.414 1.414
                             M6.464 17.536L5.05 18.95" />
                    </svg>
                </button>
            </div>
        </div>
    </header>



    <!-- Main Content -->
    <main class="max-w-3xl mx-auto px-6 py-8">
        <div class="mb-6">
            <h1 class="text-3xl font-bold mb-2">Buat Postingan Baru</h1>
            <p class="text-slate-600 dark:text-slate-400">Bagikan pemikiran Anda dengan komunitas</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                <p class="text-red-800 dark:text-red-200 font-semibold">Terjadi kesalahan:</p>
                <ul class="list-disc list-inside text-red-700 dark:text-red-300">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data"
            class="bg-white dark:bg-slate-900 rounded-lg shadow-lg p-6 space-y-6">
            @csrf

            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-semibold mb-2">Judul Postingan *</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}"
                    placeholder="Masukkan judul postingan..."
                    class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-slate-400 dark:placeholder-slate-500"
                    required>
                @error('title')
                    <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Content -->
            <div>
                <label for="body" class="block text-sm font-semibold mb-2">Konten *</label>
                <textarea id="body" name="body" placeholder="Tuliskan konten postingan Anda di sini..." rows="10"
                    class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-slate-400 dark:placeholder-slate-500 resize-vertical"
                    required>{{ old('body') }}</textarea>
                @error('body')
                    <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Minimal 10 karakter</p>
            </div>

            <!-- Media Upload -->
            <div>
                <label class="block text-sm font-semibold mb-2">Foto atau Video (Opsional)</label>
                <div id="media-drop-zone"
                    class="border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-lg p-6 text-center cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                    <input type="file" id="media" name="media" accept="image/*,video/*" class="hidden"
                        onchange="previewMedia(event)">

                    <div id="media-placeholder" class="pointer-events-none">
                        <svg class="mx-auto h-12 w-12 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Klik atau seret foto/video
                            di sini</p>
                        <p class="text-xs text-slate-500 dark:text-slate-500 mt-1">Maksimal 50MB (JPEG, PNG,
                            GIF, MP4, WebM)</p>
                    </div>

                    <div id="media-preview" class="hidden mt-4">
                        <img id="preview-img" class="max-h-64 mx-auto rounded-lg hidden" />
                        <video id="preview-video" class="max-h-64 mx-auto rounded-lg hidden" controls />
                        <div id="media-filename" class="text-sm text-slate-600 dark:text-slate-400 mt-2">
                        </div>
                        <button type="button" onclick="clearMedia()"
                            class="mt-2 text-xs px-2 py-1 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded hover:bg-red-200 dark:hover:bg-red-900/50">
                            Hapus
                        </button>
                    </div>
                </div>
                @error('media')
                    <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex gap-4">
                <button type="submit"
                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-colors">
                    Publikasikan
                </button>
                <a href="{{ route('home') }}"
                    class="px-6 py-2 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-900 dark:text-white rounded-lg font-semibold transition-colors">
                    Batal
                </a>
            </div>
        </form>
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

        // Media upload handling
        function previewMedia(event) {
            const file = event.target.files[0];
            if (!file) return;

            const mediaInput = document.getElementById('media');
            const mediaDropZone = document.getElementById('media-drop-zone');
            const mediaPlaceholder = document.getElementById('media-placeholder');
            const mediaPreview = document.getElementById('media-preview');
            const previewImg = document.getElementById('preview-img');
            const previewVideo = document.getElementById('preview-video');
            const mediaFilename = document.getElementById('media-filename');

            previewImg.classList.add('hidden');
            previewVideo.classList.add('hidden');

            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    previewImg.src = e.target.result;
                    previewImg.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else if (file.type.startsWith('video/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    previewVideo.src = e.target.result;
                    previewVideo.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }

            mediaFilename.textContent = file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';
            mediaPlaceholder.classList.add('hidden');
            mediaPreview.classList.remove('hidden');
        }

        function clearMedia() {
            const mediaInput = document.getElementById('media');
            const mediaPlaceholder = document.getElementById('media-placeholder');
            const mediaPreview = document.getElementById('media-preview');

            mediaInput.value = '';
            mediaPlaceholder.classList.remove('hidden');
            mediaPreview.classList.add('hidden');
        }

        // Drag and drop support
        const mediaDropZone = document.getElementById('media-drop-zone');
        const mediaInput = document.getElementById('media');

        if (mediaDropZone) {
            mediaDropZone.addEventListener('click', () => mediaInput.click());

            mediaDropZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                mediaDropZone.classList.add('bg-slate-100', 'dark:bg-slate-800/80');
            });

            mediaDropZone.addEventListener('dragleave', () => {
                mediaDropZone.classList.remove('bg-slate-100', 'dark:bg-slate-800/80');
            });

            mediaDropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                mediaDropZone.classList.remove('bg-slate-100', 'dark:bg-slate-800/80');
                if (e.dataTransfer.files.length) {
                    mediaInput.files = e.dataTransfer.files;
                    previewMedia({
                        target: {
                            files: e.dataTransfer.files
                        }
                    });
                }
            });
        }
    </script>
</body>

</html>
