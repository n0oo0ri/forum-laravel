<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
@use('Illuminate\Support\Facades\Storage')

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $post->title }} - {{ config('app.name', 'Forum') }}</title>
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

        <div class="w-full px-6 py-3"> {{-- ⬅️ py-3 (SAMA DENGAN WELCOME) --}}
            <div class="flex items-center justify-between">

                <!-- LEFT -->
                <a href="{{ route('home') }}" class="text-2xl font-bold"> {{-- ⬅️ text-xl --}}
                    Forum
                </a>

                <!-- RIGHT -->
                <div class="flex items-center gap-4">

                    @auth
                        <a href="{{ route('users.profile', auth()->user()) }}" class="flex items-center gap-2">

                            @if (auth()->user()->avatar)
                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}"
                                    class="w-8 h-8 rounded-full object-cover"> {{-- ⬅️ w-7 h-7 --}}
                            @else
                                <div
                                    class="w-8 h-8 rounded-full
                                       bg-blue-600 text-white
                                       flex items-center justify-center
                                       text-[11px] font-bold">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            @endif

                            <span class="text-sm text-slate-600 dark:text-slate-400">
                                {{ auth()->user()->name }}
                            </span>
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="text-sm font-semibold hover:underline">
                                Keluar
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm hover:underline">
                            Masuk
                        </a>
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
        </div>
    </header>




    <main class="max-w-3xl mx-auto px-6 py-8">
        <!-- Back Button -->
        <a href="{{ route('home') }}"
            class="inline-block mb-6 text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200 transition-colors">←
            Kembali</a>

        <!-- Post Header -->
        <article
            class="bg-white dark:bg-slate-900 rounded-lg shadow dark:shadow-lg border border-slate-200 dark:border-slate-800 p-8 mb-8">
            <div class="flex items-start justify-between gap-4 mb-4">
                <h1 class="text-4xl font-bold flex-1">{{ $post->title }}</h1>

                @auth
                    @if (auth()->id() === $post->user_id)
                        <div class="flex items-center gap-2">
                            <!-- Edit -->
                            <a href="{{ route('posts.edit', $post) }}"
                                class="p-2 rounded-lg
              bg-blue-50 hover:bg-blue-100
              dark:bg-blue-900/20 dark:hover:bg-blue-900/40
              text-blue-600 dark:text-blue-400
              transition-colors"
                                title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5
                                         m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>

                            <!-- DELETE FORM -->
                            <form method="POST" action="{{ route('posts.destroy', $post) }}"
                                onsubmit="return confirm('Yakin ingin menghapus postingan ini?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    class="p-2 rounded-lg
                   bg-red-50 hover:bg-red-100
                   dark:bg-red-900/20 dark:hover:bg-red-900/40
                   text-red-600 dark:text-red-400
                   transition-colors"
                                    title="Hapus">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862
                                             a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6
                                             m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @endif
                @endauth
            </div>

            <div
                class="flex items-center gap-4 text-sm text-slate-600 dark:text-slate-400 mb-6 pb-6 border-b border-slate-200 dark:border-slate-700">
                <span>Oleh
                    <a href="{{ route('users.profile', $post->user) }}"
                        class="font-bold text-slate-900 dark:text-slate-100 hover:text-blue-600 dark:hover:text-blue-400">
                        {{ $post->user->name }}
                    </a>
                </span>
                @if ($post->community)
                    <span>•</span>
                    <span>{{ $post->community->name }}</span>
                @endif
                <span>•</span>
                <span title="{{ $post->created_at_formatted }}">{{ $post->created_at_relative }}</span>
            </div>

            <!-- Post Content -->
            <div class="prose dark:prose-invert max-w-none mb-8 text-slate-700 dark:text-slate-300 leading-relaxed">
                {{ $post->body }}
            </div>

            <!-- Media Display -->
            @if ($post->media_path)
                <div class="mb-8 rounded-lg overflow-hidden bg-black">
                    @if ($post->media_type === 'image')
                        <div class="aspect-video bg-black flex items-center justify-center cursor-pointer"
                            onclick="openLightbox('{{ Storage::url($post->media_path) }}', 'image')">
                            <img src="{{ Storage::url($post->media_path) }}" alt="{{ $post->title }}"
                                class="w-full h-full object-cover hover:opacity-90 transition-opacity">
                        </div>
                    @elseif ($post->media_type === 'video')
                        <div class="aspect-video bg-black flex items-center justify-center cursor-pointer relative group"
                            onclick="openLightbox('{{ Storage::url($post->media_path) }}', 'video')">
                            <video class="w-full h-full object-cover">
                                <source src="{{ Storage::url($post->media_path) }}" type="video/mp4">
                            </video>
                            <svg class="absolute w-16 h-16 text-white opacity-70 group-hover:opacity-100 transition-opacity"
                                fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z" />
                            </svg>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Vote Section -->
            @php
                $score = $post->votes()->sum('value');
                $userVote = auth()->check()
                    ? $post
                        ->votes()
                        ->where('user_id', auth()->id())
                        ->value('value')
                    : null;
            @endphp

            <!-- VOTE -->
            <div class="inline-flex items-center
           gap-3
           px-2 py-2
           rounded-full
           bg-slate-100 dark:bg-slate-800
           text-sm text-slate-600 dark:text-slate-300
           hover:bg-slate-200 dark:hover:bg-slate-700
           transition-colors"
                onclick="event.stopPropagation();">
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
                    <span class="vote-score font-semibold min-w-8 text-center" data-post="{{ $post->id }}">
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
                    <!-- Guest -->
                    <a href="{{ route('login') }}" class="font-bold leading-none px-1 hover:text-orange-400">
                        ▲
                    </a>

                    <span class="font-semibold leading-none px-1">
                        {{ $score }}
                    </span>

                    <a href="{{ route('login') }}" class="font-bold leading-none px-1 hover:text-blue-400">
                        ▼
                    </a>
                @endauth
            </div>





        </article>

        <!-- Comments Section -->
        <section class="space-y-6">
            <h2 class="text-2xl font-bold">Komentar</h2>

            @auth
                <!-- Comment Form -->
                <div
                    class="bg-white dark:bg-slate-900 rounded-lg shadow dark:shadow-lg border border-slate-200 dark:border-slate-800 p-6">
                    <h3 class="font-semibold mb-4">Tulis Komentar</h3>
                    <form method="POST" action="{{ route('posts.comment', $post) }}">
                        @csrf
                        <textarea name="body" required placeholder="Tulis komentar Anda..."
                            class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg dark:bg-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-slate-900 dark:focus:ring-slate-100 mb-4 min-h-24"></textarea>
                        @error('body')
                            <p class="text-red-600 dark:text-red-400 text-sm mb-4">{{ $message }}</p>
                        @enderror
                        <button type="submit"
                            class="bg-slate-900 dark:bg-slate-100 text-white dark:text-slate-900 px-6 py-2 rounded-lg font-semibold hover:bg-slate-800 dark:hover:bg-slate-200 transition-colors">
                            Kirim Komentar
                        </button>
                    </form>
                </div>
            @else
                <div
                    class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg text-sm text-blue-800 dark:text-blue-300">
                    <a href="{{ route('login') }}" class="font-semibold hover:underline">Login</a> untuk berkomentar
                </div>
            @endauth

            <!-- Comments List -->
            @if ($post->comments()->whereNull('parent_id')->count() > 0)
                <div class="space-y-4">
                    @foreach ($post->comments()->whereNull('parent_id')->latest()->get() as $comment)
                        @include('comments.item', ['post' => $post])
                    @endforeach
                </div>
            @else
                <p class="text-slate-600 dark:text-slate-400 italic">Belum ada komentar. Jadilah yang pertama!</p>
            @endif
        </section>
    </main>

    <script>
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
            modal.style.display = 'flex';
            setTimeout(() => {
                modal.classList.remove('opacity-0', 'pointer-events-none');
            }, 10);

            document.body.style.overflow = 'hidden';
        }

        function closeLightbox() {
            const modal = document.getElementById('lightboxModal');
            const video = document.getElementById('lightboxVideo');

            modal.classList.add('opacity-0', 'pointer-events-none');
            setTimeout(() => {
                modal.style.display = 'none';
                video.pause();
                video.src = '';
            }, 300);

            document.body.style.overflow = 'auto';
        }

        // Close lightbox ketika klik di luar
        document.getElementById('lightboxModal')?.addEventListener('click', (e) => {
            if (e.target === document.getElementById('lightboxModal')) {
                closeLightbox();
            }
        });

        // Close lightbox dengan tombol Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeLightbox();
            }
        });
    </script>

    <script>
        document.querySelectorAll('.vote-form').forEach(form => {
            form.addEventListener('submit', async e => {
                e.preventDefault();

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
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: new URLSearchParams({
                            value
                        }),
                    });

                    if (!res.ok) throw new Error();

                    const data = await res.json();

                    // Update score
                    scoreEl.textContent = data.score;

                    // Reset warna
                    document
                        .querySelectorAll(`.vote-form[data-post="${postId}"] button`)
                        .forEach(btn =>
                            btn.classList.remove('text-orange-500', 'text-blue-500')
                        );

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

                } catch {
                    alert('Gagal melakukan vote');
                }
            });
        });
    </script>

    <script>
        const toggle = document.getElementById('theme-toggle');
        const root = document.documentElement;

        // INIT
        if (
            localStorage.getItem('dark-mode') === 'true' ||
            (!localStorage.getItem('dark-mode') &&
                window.matchMedia('(prefers-color-scheme: dark)').matches)
        ) {
            root.classList.add('dark');
        }

        toggle?.addEventListener('click', () => {
            root.classList.toggle('dark');
            localStorage.setItem(
                'dark-mode',
                root.classList.contains('dark')
            );
        });
    </script>



    <!-- LIGHTBOX MODAL -->
    <div id="lightboxModal"
        class="fixed inset-0 z-50 opacity-0 pointer-events-none bg-black/90 items-center justify-center p-4"
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
