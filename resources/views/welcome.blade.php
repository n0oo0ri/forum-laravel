<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
@use('Illuminate\Support\Facades\Storage')

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forum - {{ config('app.name', 'Forum') }}</title>

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
        if (
            localStorage.getItem('dark-mode') === 'true' ||
            (!localStorage.getItem('dark-mode') &&
                window.matchMedia('(prefers-color-scheme: dark)').matches)
        ) {
            document.documentElement.classList.add('dark');
        }
    </script>
</head>

<body class="bg-white dark:bg-slate-950 text-slate-900 dark:text-slate-100 transition-colors duration-200">

    <!-- HEADER -->
    <header class="sticky top-0 z-50 border-b border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900">
        <div class="relative w-full py-3">

            <!-- LEFT & RIGHT -->
            <div class="flex items-center justify-between px-6">
                <a href="{{ route('home') }}" class="text-2xl font-bold">Forum</a>

                <div class="flex items-center gap-4">
                    @auth
                        <button onclick="openCreatePostModal()"
                            class="text-sm px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded font-semibold">
                            + Buat
                        </button>

                        <a href="{{ route('users.profile', auth()->user()) }}"
                            class="flex items-center gap-2 hover:opacity-80 transition-opacity">
                            @if (auth()->user()->avatar)
                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}"
                                    class="w-8 h-8 rounded-full object-cover">
                            @else
                                <div
                                    class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                            @endif
                            <span class="text-sm text-slate-600 dark:text-slate-400 hover:underline">
                                {{ auth()->user()->name }}
                            </span>
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="text-sm font-semibold hover:underline">Keluar</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold hover:underline">Masuk</a>
                        <a href="{{ route('register') }}" class="text-sm font-semibold hover:underline">Daftar</a>
                    @endauth

                    <!-- THEME TOGGLE (SVG) -->
                    <button id="theme-toggle" aria-label="Toggle dark mode"
                        class="p-2 rounded-lg bg-slate-100 dark:bg-slate-800
                           hover:bg-slate-200 dark:hover:bg-slate-700
                           transition-colors">
                        <!-- Moon -->
                        <svg class="w-5 h-5 text-slate-700 dark:hidden" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3
                               7 7 0 0021 12.79z" />
                        </svg>

                        <!-- Sun -->
                        <svg class="w-5 h-5 text-yellow-400 hidden dark:block" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="5" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 1v2m0 18v2m11-11h-2M3 12H1
                               m16.95 6.95l-1.414-1.414M6.464 6.464L5.05 5.05
                               m12.9 0l-1.414 1.414M6.464 17.536L5.05 18.95" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- CENTER SEARCH -->
            <div class="absolute inset-x-0 top-1/2 -translate-y-1/2 flex justify-center pointer-events-none">
                <form action="{{ route('home') }}" method="GET" class="w-full max-w-xl pointer-events-auto px-4">
                    <div class="relative">
                        <!-- Search Icon -->
                        <svg class="absolute inset-y-0 left-3 my-auto w-4 h-4 text-slate-400"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <circle cx="11" cy="11" r="8" />
                            <line x1="21" y1="21" x2="16.65" y2="16.65" />
                        </svg>

                        <input type="text" name="q" value="{{ request('q') }}"
                            placeholder="Cari postingan..."
                            class="w-full rounded-full pl-10 pr-4 py-2 text-sm
                               border border-slate-300 dark:border-slate-600
                               bg-slate-100 dark:bg-slate-800
                               focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                </form>
            </div>

        </div>
    </header>


    <!-- MAIN -->
    <main class="max-w-3xl mx-auto px-6 py-8">
        <div class="mb-8 flex items-center justify-between gap-4">
            <div>
                @if (request('q'))
                    <h2 class="text-4xl font-bold mb-2">Hasil Pencarian: "{{ request('q') }}"</h2>
                    <p class="text-slate-600 dark:text-slate-400">
                        Ditemukan {{ $posts->total() }} hasil
                    </p>
                @else
                    <h2 class="text-4xl font-bold mb-2">Selamat Datang di Forum</h2>
                    <p class="text-slate-600 dark:text-slate-400">
                        Berbagi pengetahuan dan diskusi dengan komunitas
                    </p>
                @endif
            </div>
            <select id="sortPosts" onchange="sortPostsHandler(this.value)"
                class="px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 h-fit">
                <option value="">Urutkan...</option>
                <option value="newest">Recent</option>
                <option value="oldest">Oldest</option>
                <option value="votes_high">Most Votes</option>
                <option value="votes_low">Least Votes</option>
            </select>
        </div>

        <!-- POSTS -->
        <div class="space-y-6" data-posts-container>
            @forelse($posts as $post)
                @php
                    $score = $post->votes()->sum('value');
                    $userVote = auth()->check()
                        ? $post
                            ->votes()
                            ->where('user_id', auth()->id())
                            ->value('value')
                        : null;
                @endphp

                <article
                    class="bg-white dark:bg-slate-900 rounded-lg shadow border border-slate-200 dark:border-slate-800 p-6 hover:shadow-lg transition-shadow cursor-pointer"
                    onclick="window.location.href='{{ route('posts.show', $post) }}'"
                    data-post-id="{{ $post->id }}" data-votes="{{ $post->votes()->sum('value') }}"
                    data-time="{{ $post->created_at->toDateTimeString() }}">

                    <h3 class="text-2xl font-bold mb-2 hover:text-slate-700 dark:hover:text-slate-300">
                        {{ $post->title }}
                    </h3>

                    <div class="flex items-center gap-4 text-sm text-slate-600 dark:text-slate-400 mb-4 pb-4 border-b">
                        <span>
                            Oleh
                            <a href="{{ route('users.profile', $post->user) }}" onclick="event.stopPropagation();"
                                class="font-bold hover:text-blue-600">
                                {{ $post->user->name }}
                            </a>
                        </span>
                        <span>•</span>
                        <span>{{ $post->created_at_relative }}</span>

                    </div>

                    <p class="text-slate-700 dark:text-slate-300 line-clamp-3 mb-4">
                        {{ Str::limit($post->body, 200) }}
                    </p>

                    <!-- Media Thumbnail -->
                    @if ($post->media_path)
                        <div class="mb-4 rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-800"
                            onclick="event.stopPropagation();">
                            @if ($post->media_type === 'image')
                                <div class="aspect-video bg-black flex items-center justify-center cursor-pointer"
                                    onclick="openLightbox('{{ Storage::url($post->media_path) }}', 'image')">
                                    <img src="{{ Storage::url($post->media_path) }}" alt="{{ $post->title }}"
                                        class="w-full h-full object-cover hover:opacity-90 transition-opacity">
                                </div>
                            @elseif ($post->media_type === 'video')
                                <div class="aspect-video bg-black flex items-center justify-center cursor-pointer"
                                    onclick="openLightbox('{{ Storage::url($post->media_path) }}', 'video')">
                                    <video class="w-full h-full object-cover">
                                        <source src="{{ Storage::url($post->media_path) }}" type="video/mp4">
                                    </video>
                                    <svg class="absolute w-16 h-16 text-white opacity-70" fill="currentColor"
                                        viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- VOTE + COMMENT -->
                    <div class="flex items-center gap-3 mt-4" onclick="event.stopPropagation();">

                        <!-- VOTE -->
                        <div
                            class="flex items-center gap-2 px-3 py-1.5 rounded-full
               bg-slate-100 dark:bg-slate-800
               text-xs text-slate-600 dark:text-slate-300
               hover:bg-slate-200 dark:hover:bg-slate-700
               transition-colors">
                            @auth
                                <!-- UPVOTE -->
                                <form class="vote-form" data-post="{{ $post->id }}">
                                    @csrf
                                    <input type="hidden" name="value" value="1">
                                    <button type="submit" data-type="up"
                                        class="font-bold transition
        {{ $userVote === 1 ? 'text-orange-500' : 'hover:text-orange-400' }}">
                                        ▲
                                    </button>
                                </form>



                                <!-- SCORE -->
                                <span class="vote-score font-semibold min-w-8 text-center"
                                    data-post="{{ $post->id }}">
                                    {{ $score }}
                                </span>


                                <!-- DOWNVOTE -->
                                <form class="vote-form" data-post="{{ $post->id }}">
                                    @csrf
                                    <input type="hidden" name="value" value="-1">
                                    <button type="submit" data-type="down"
                                        class="font-bold transition
        {{ $userVote === -1 ? 'text-blue-500' : 'hover:text-blue-400' }}">
                                        ▼
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="hover:text-orange-400">▲</a>

                                <span class="font-semibold min-w-6 text-center">
                                    {{ $score }}
                                </span>

                                <a href="{{ route('login') }}" class="hover:text-blue-400">▼</a>
                            @endauth
                        </div>

                        <!-- COMMENT -->
                        <a href="{{ route('posts.show', $post) }}#comments"
                            class="flex items-center gap-1.5 px-3 py-1.5 rounded-full
               bg-slate-100 dark:bg-slate-800
               text-xs text-slate-600 dark:text-slate-300
               hover:bg-slate-200 dark:hover:bg-slate-700
               transition-colors">
                            <!-- Comment Icon (SVG, not emoji) -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8 10h8m-8 4h5m-7 8l-4 4V5a2 2 0 012-2h14a2 2 0 012 2v11a2 2 0 01-2 2H8z" />
                            </svg>

                            <span class="font-medium">
                                {{ $post->comments()->count() }}
                            </span>
                        </a>

                    </div>

                </article>

            @empty
                <div
                    class="p-6 text-center rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800">
                    @if (request('q'))
                        <p class="font-semibold text-blue-900 dark:text-blue-100">Tidak ada hasil untuk
                            "{{ request('q') }}"</p>
                        <p class="text-sm text-blue-700 dark:text-blue-300 mt-2">Coba cari dengan kata kunci yang
                            berbeda</p>
                    @else
                        <p class="font-semibold text-blue-900 dark:text-blue-100">Belum ada postingan</p>
                        <p class="text-sm text-blue-700 dark:text-blue-300 mt-2">Jadilah yang pertama untuk membuat
                            postingan!</p>
                    @endif
                </div>
            @endforelse
        </div>

        @if ($posts->hasPages())
            <div class="mt-8 flex justify-center">
                {{ $posts->links() }}
            </div>
        @endif
    </main>

    <!-- MODAL TAMBAH POSTINGAN -->
    @auth
        <div id="createPostModal"
            class="fixed inset-0 z-50 opacity-0 pointer-events-none bg-black/50 items-center justify-center p-4 transition-opacity duration-300"
            style="display: flex;">
            <div class="bg-white dark:bg-slate-900 rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <!-- Header -->
                <div
                    class="sticky top-0 flex items-center justify-between p-6 border-b border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900">
                    <h2 class="text-2xl font-bold">Buat Postingan Baru</h2>
                    <button onclick="closeCreatePostModal()"
                        class="text-slate-500 hover:text-slate-700 dark:hover:text-slate-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Content -->
                <div class="p-6">
                    <form id="quickCreatePostForm" method="POST" action="{{ route('posts.store') }}"
                        enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Title -->
                        <div>
                            <label for="modal_title" class="block text-sm font-semibold mb-2">Judul Postingan *</label>
                            <input type="text" id="modal_title" name="title"
                                placeholder="Masukkan judul postingan..."
                                class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-slate-400 dark:placeholder-slate-500"
                                required>
                            <div id="title_error" class="text-red-600 dark:text-red-400 text-sm mt-1 hidden"></div>
                        </div>

                        <!-- Content -->
                        <div>
                            <label for="modal_body" class="block text-sm font-semibold mb-2">Konten *</label>
                            <textarea id="modal_body" name="body" placeholder="Tuliskan konten postingan Anda di sini..." rows="6"
                                class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-slate-400 dark:placeholder-slate-500 resize-vertical"
                                required></textarea>
                            <div id="body_error" class="text-red-600 dark:text-red-400 text-sm mt-1 hidden"></div>
                            <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Minimal 10 karakter</p>
                        </div>

                        <!-- Media Upload -->
                        <div>
                            <label class="block text-sm font-semibold mb-2">Foto atau Video (Opsional)</label>
                            <div id="media-drop-zone"
                                class="border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-lg p-6 text-center cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <input type="file" id="modal_media" name="media" accept="image/*,video/*"
                                    class="hidden" onchange="previewMedia(event)">

                                <div id="media-placeholder" class="pointer-events-none">
                                    <svg class="mx-auto h-12 w-12 text-slate-400" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
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
                            <div id="media_error" class="text-red-600 dark:text-red-400 text-sm mt-1 hidden"></div>
                        </div>

                        <!-- Buttons -->
                        <div class="flex gap-4 justify-end">
                            <button type="button" onclick="closeCreatePostModal()"
                                class="px-6 py-2 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-900 dark:text-white rounded-lg font-semibold transition-colors">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-colors">
                                Publikasikan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endauth

    <script>
        function openCreatePostModal() {
            const modal = document.getElementById('createPostModal');
            modal?.classList.remove('opacity-0', 'pointer-events-none');
            modal?.classList.add('opacity-100');
            document.body.style.overflow = 'hidden';
        }

        function closeCreatePostModal() {
            const modal = document.getElementById('createPostModal');
            modal?.classList.add('opacity-0', 'pointer-events-none');
            modal?.classList.remove('opacity-100');
            document.body.style.overflow = 'auto';
            document.getElementById('quickCreatePostForm')?.reset();
            document.getElementById('title_error')?.classList.add('hidden');
            document.getElementById('body_error')?.classList.add('hidden');
            document.getElementById('media_error')?.classList.add('hidden');
            clearMedia();
        }

        function previewMedia(event) {
            const file = event.target.files[0];
            if (!file) return;

            const dropZone = document.getElementById('media-drop-zone');
            const placeholder = document.getElementById('media-placeholder');
            const preview = document.getElementById('media-preview');
            const filename = document.getElementById('media-filename');
            const previewImg = document.getElementById('preview-img');
            const previewVideo = document.getElementById('preview-video');

            placeholder.classList.add('hidden');
            preview.classList.remove('hidden');
            filename.textContent = file.name;

            const reader = new FileReader();
            reader.onload = (e) => {
                if (file.type.startsWith('image/')) {
                    previewImg.src = e.target.result;
                    previewImg.classList.remove('hidden');
                    previewVideo.classList.add('hidden');
                } else if (file.type.startsWith('video/')) {
                    previewVideo.src = e.target.result;
                    previewVideo.classList.add('hidden');
                    previewImg.classList.add('hidden');
                    previewVideo.classList.remove('hidden');
                }
            };
            reader.readAsDataURL(file);
        }

        function clearMedia() {
            document.getElementById('modal_media').value = '';
            document.getElementById('media-placeholder')?.classList.remove('hidden');
            document.getElementById('media-preview')?.classList.add('hidden');
            document.getElementById('preview-img')?.classList.add('hidden');
            document.getElementById('preview-video')?.classList.add('hidden');
        }

        // Drag and drop handling
        const dropZone = document.getElementById('media-drop-zone');
        if (dropZone) {
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, () => {
                    dropZone.classList.add('bg-slate-100', 'dark:bg-slate-800');
                }, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, () => {
                    dropZone.classList.remove('bg-slate-100', 'dark:bg-slate-800');
                }, false);
            });

            dropZone.addEventListener('drop', (e) => {
                const dt = e.dataTransfer;
                const files = dt.files;
                document.getElementById('modal_media').files = files;
                const event = {
                    target: {
                        files: files
                    }
                };
                previewMedia(event);
            }, false);

            dropZone.addEventListener('click', () => {
                document.getElementById('modal_media').click();
            });
        }

        // Close modal when clicking outside of it
        document.getElementById('createPostModal')?.addEventListener('click', (e) => {
            if (e.target === document.getElementById('createPostModal')) {
                closeCreatePostModal();
            }
        });

        // Handle form submission
        document.getElementById('quickCreatePostForm')?.addEventListener('submit', async (e) => {
            e.preventDefault();

            const form = e.target;
            const formData = new FormData(form);

            // Clear previous errors
            document.getElementById('title_error')?.classList.add('hidden');
            document.getElementById('body_error')?.classList.add('hidden');
            document.getElementById('media_error')?.classList.add('hidden');

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data.success) {
                        // Redirect to the created post or reload page
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        } else {
                            window.location.reload();
                        }
                    }
                } else if (response.status === 422) {
                    // Handle validation errors
                    const errorData = await response.json();
                    if (errorData.errors) {
                        if (errorData.errors.title) {
                            document.getElementById('title_error').textContent = errorData.errors.title[0];
                            document.getElementById('title_error')?.classList.remove('hidden');
                        }
                        if (errorData.errors.body) {
                            document.getElementById('body_error').textContent = errorData.errors.body[0];
                            document.getElementById('body_error')?.classList.remove('hidden');
                        }
                        if (errorData.errors.media) {
                            document.getElementById('media_error').textContent = errorData.errors.media[0];
                            document.getElementById('media_error')?.classList.remove('hidden');
                        }
                    }
                } else {
                    console.error('Error response:', response);
                    alert('Terjadi kesalahan: ' + (response.statusText || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengirim postingan: ' + error.message);
            }
        });

        document.getElementById('theme-toggle')?.addEventListener('click', (e) => {
            e.stopPropagation();
            const root = document.documentElement;
            root.classList.toggle('dark');
            localStorage.setItem('dark-mode', root.classList.contains('dark'));
        });

        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('click', e => e.stopPropagation());
        });

        // Ganti fungsi openLightbox yang lama dengan yang ini:

        function openLightbox(src, type) {
            const modal = document.getElementById('lightboxModal');
            const image = document.getElementById('lightboxImage');
            const video = document.getElementById('lightboxVideo');
            const videoSource = document.getElementById('lightboxVideoSource');

            // Reset dan hide semua dulu
            image.classList.add('hidden');
            video.classList.add('hidden');
            video.pause();

            if (type === 'image') {
                image.src = src;
                image.classList.remove('hidden');
            } else if (type === 'video') {
                videoSource.src = src;
                video.load();
                // PENTING: Remove hidden SEBELUM play
                video.classList.remove('hidden');

                // Tunggu video ready sebelum play
                video.addEventListener('loadeddata', function playOnce() {
                    video.play().catch(err => console.error('Video play error:', err));
                    video.removeEventListener('loadeddata', playOnce);
                });
            }

            // Show modal dengan transition
            modal.classList.remove('opacity-0', 'pointer-events-none');
            modal.classList.add('opacity-100');

            document.body.style.overflow = 'hidden';
        }

        function closeLightbox() {
            const modal = document.getElementById('lightboxModal');
            const video = document.getElementById('lightboxVideo');

            modal.classList.add('opacity-0', 'pointer-events-none');
            modal.classList.remove('opacity-100');
            setTimeout(() => {
                video.pause();
                video.src = '';
            }, 300);

            document.body.style.overflow = 'auto';
        }

        // Close lightbox when clicking outside content
        document.getElementById('lightboxModal')?.addEventListener('click', (e) => {
            if (e.target === document.getElementById('lightboxModal')) {
                closeLightbox();
            }
        });

        // Close lightbox with Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeLightbox();
            }
        });
    </script>

    <script>
        document.querySelectorAll('.vote-form').forEach(form => {
            form.addEventListener('submit', async (e) => {
                e.preventDefault(); // ⛔ STOP submit normal

                const postId = form.dataset.post;
                const value = form.querySelector('input[name="value"]').value;
                const token = form.querySelector('input[name="_token"]').value;

                const scoreEl = document.querySelector(
                    `.vote-score[data-post="${postId}"]`
                );

                try {
                    const res = await fetch(`/posts/${postId}/vote`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json',
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: new URLSearchParams({
                            value
                        })
                    });

                    if (!res.ok) throw new Error('Vote gagal');

                    const data = await res.json();

                    // Update score
                    scoreEl.textContent = data.score;

                    // Reset warna
                    document
                        .querySelectorAll(`.vote-form[data-post="${postId}"] button`)
                        .forEach(btn => btn.classList.remove(
                            'text-orange-500',
                            'text-blue-500'
                        ));

                    // Apply warna aktif
                    if (data.userVote === 1) {
                        document
                            .querySelector(`.vote-form[data-post="${postId}"] button[data-type="up"]`)
                            ?.classList.add('text-orange-500');
                    }

                    if (data.userVote === -1) {
                        document
                            .querySelector(`.vote-form[data-post="${postId}"] button[data-type="down"]`)
                            ?.classList.add('text-blue-500');
                    }

                } catch (err) {
                    console.error(err);
                    alert('Gagal melakukan vote');
                }
            });
        });

        // Sort posts function
        function sortPostsHandler(sortType) {
            if (!sortType) return;

            const postElements = document.querySelectorAll('[data-post-id]');
            const postsArray = Array.from(postElements);

            postsArray.sort((a, b) => {
                const aVotes = parseInt(a.dataset.votes) || 0;
                const bVotes = parseInt(b.dataset.votes) || 0;
                const aTime = new Date(a.dataset.time).getTime();
                const bTime = new Date(b.dataset.time).getTime();

                switch (sortType) {
                    case 'newest':
                        return bTime - aTime;
                    case 'oldest':
                        return aTime - bTime;
                    case 'votes_high':
                        return bVotes - aVotes;
                    case 'votes_low':
                        return aVotes - bVotes;
                    default:
                        return 0;
                }
            });

            const container = document.querySelector('[data-posts-container]');
            if (container) {
                postsArray.forEach(post => {
                    container.appendChild(post);
                });
            }

            setTimeout(() => {
                document.getElementById('sortPosts').value = '';
            }, 300);
        }
    </script>


    <!-- LIGHTBOX MODAL -->
    <div id="lightboxModal"
        class="fixed inset-0 z-50 opacity-0 pointer-events-none bg-black/90 items-center justify-center p-4 transition-opacity duration-300"
        style="display: flex;">
        <div class="relative w-full h-full max-w-4xl max-h-screen flex items-center justify-center">
            <!-- Close Button -->
            <button onclick="closeLightbox()"
                class="absolute top-4 right-4 text-white hover:bg-white/20 p-2 rounded-full transition-colors z-10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>

            <!-- Image Display -->
            <img id="lightboxImage" src="" alt=""
                class="max-w-full max-h-full object-contain hidden rounded-lg">

            <!-- Video Display -->
            <video id="lightboxVideo" class="max-w-full max-h-full object-contain hidden rounded-lg" controls>
                <source id="lightboxVideoSource" src="" type="video/mp4">
                Browser Anda tidak mendukung video HTML5.
            </video>
        </div>
    </div>

</body>

</html>
