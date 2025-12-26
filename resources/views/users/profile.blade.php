<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
@use('Illuminate\Support\Facades\Storage')

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profil {{ $user->name }} - Forum</title>
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
    <!-- SheetJS Library for Excel Export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.min.js"></script>
    <!-- Print Styles -->
    <style>
        @media print {

            header,
            #lightboxModal,
            button,
            a[href*="edit"],
            a[href*="logout"] {
                display: none !important;
            }

            body {
                background: white;
            }

            .dark\:bg-slate-900 {
                background: white !important;
            }

            .dark\:text-slate-100 {
                color: #000 !important;
            }

            .text-slate-900 {
                color: #000 !important;
            }

            main {
                padding: 0;
            }

            .bg-white {
                background: white;
                border: none;
                box-shadow: none;
                padding: 20px 0;
            }
        }
    </style>
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
        <!-- Back Button -->
        <a href="{{ route('home') }}"
            class="inline-block mb-6 text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200 transition-colors">
            ← Kembali
        </a>

        <!-- Profile Card -->
        <div class="bg-white dark:bg-slate-900 rounded-lg shadow-lg p-8 mb-8">
            <div class="flex items-start justify-between mb-6">
                <div class="flex items-start gap-6">
                    @if ($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}"
                            class="w-24 h-24 rounded-full object-cover">
                    @else
                        <div
                            class="w-24 h-24 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-4xl">
                            👤</div>
                    @endif
                    <div>
                        <h1 class="text-4xl font-bold mb-2">{{ $user->name }}</h1>
                        <p class="text-slate-600 dark:text-slate-400">{{ $user->email }}</p>
                        @if ($user->location)
                            <p class="text-slate-600 dark:text-slate-400">📍 {{ $user->location }}</p>
                        @endif
                        @if ($user->website)
                            <p class="text-slate-600 dark:text-slate-400">
                                🔗 <a href="{{ $user->website }}" target="_blank"
                                    class="text-blue-600 dark:text-blue-400 hover:underline">{{ $user->website }}</a>
                            </p>
                        @endif
                        <p class="text-slate-500 dark:text-slate-500 text-sm mt-2">
                            Bergabung {{ $user->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
                @auth
                    @if (Auth::id() === $user->id)
                        <div class="flex items-center gap-3">
                            <!-- Edit Profil -->
                            <a href="{{ route('users.edit') }}" title="Edit Profil"
                                class="p-2 rounded-lg bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/20 dark:hover:bg-blue-900/40 text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5 m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>

                            <!-- Print -->
                            <button onclick="window.print()" title="Cetak Profil"
                                class="p-2 rounded-lg bg-green-50 hover:bg-green-100 dark:bg-green-900/20 dark:hover:bg-green-900/40 text-green-600 hover:text-green-700 dark:text-green-400 dark:hover:text-green-300 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2 M6 14h12v8H6z" />
                                </svg>
                            </button>

                            <!-- Export Excel -->
                            <button onclick="exportToExcel()" title="Export Excel"
                                class="p-2 rounded-lg bg-orange-50 hover:bg-orange-100 dark:bg-orange-900/20 dark:hover:bg-orange-900/40 text-orange-600 hover:text-orange-700 dark:text-orange-400 dark:hover:text-orange-300 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 17v-6m6 6v-4m3 8H6a2 2 0 01-2-2V5a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2z" />
                                </svg>
                            </button>
                        </div>
                    @endif
                @endauth
            </div>

            @if ($user->bio)
                <div class="mb-6 pb-6 border-b border-slate-200 dark:border-slate-700">
                    <p class="text-slate-700 dark:text-slate-300">{{ $user->bio }}</p>
                </div>
            @endif

            <!-- Stats -->
            <div class="grid grid-cols-3 gap-4">
                <!-- Posts -->
                <div
                    class="rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-4 py-3 text-center">
                    <p class="text-xl font-semibold">{{ $totalPosts }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Postingan</p>
                </div>

                <!-- Comments -->
                <div
                    class="rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-4 py-3 text-center">
                    <p class="text-xl font-semibold">{{ $totalComments }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Komentar</p>
                </div>

                <!-- Votes -->
                <div
                    class="rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-4 py-3 text-center">
                    <p id="karma-count"
                        class="text-xl font-semibold {{ $totalVotes >= 0 ? 'text-orange-500' : 'text-blue-500' }}">
                        {{ $totalVotes }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Karma</p>
                </div>
            </div>
        </div>

        <!-- Posts Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold">Postingan dari {{ $user->name }}</h2>
                @auth
                    @if (Auth::id() === $user->id)
                        <select id="sortPosts" onchange="sortPostsHandler(this.value)"
                            class="px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Urutkan...</option>
                            <option value="newest">Recent</option>
                            <option value="oldest">Oldest</option>
                            <option value="votes_high">Most Votes</option>
                            <option value="votes_low">Least Vote</option>
                        </select>
                    @endif
                @endauth
            </div>

            @if ($posts->count() > 0)
                <div class="space-y-4" data-posts-container>
                    @foreach ($posts as $post)
                        <div class="bg-white dark:bg-slate-900 rounded-lg shadow border border-slate-200 dark:border-slate-700 p-6 hover:shadow-lg dark:hover:shadow-xl transition-shadow cursor-pointer"
                            onclick="window.location.href='{{ route('posts.show', $post) }}'"
                            data-post-id="{{ $post->id }}" data-votes="{{ $post->votes()->sum('value') }}"
                            data-time="{{ $post->created_at->toDateTimeString() }}">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h3 class="text-xl font-bold">{{ $post->title }}</h3>
                                    <p class="text-slate-600 dark:text-slate-400 text-sm">
                                        <a href="{{ route('users.profile', $post->user) }}" class="hover:underline">
                                            {{ $post->user->name }}
                                        </a>
                                    </p>
                                </div>
                                <span class="text-slate-500 dark:text-slate-500 text-sm"
                                    title="{{ $post->created_at->format('d M Y H:i') }}">
                                    {{ $post->created_at->diffForHumans() }}
                                </span>
                            </div>

                            <p class="text-slate-700 dark:text-slate-300 mb-4 line-clamp-2">
                                {{ Str::limit($post->body, 150) }}
                            </p>

                            <!-- Media Thumbnail -->
                            @if ($post->media_path)
                                <div class="mb-4 rounded-lg overflow-hidden bg-black"
                                    onclick="event.stopPropagation();">
                                    @if ($post->media_type === 'image')
                                        <div class="aspect-video bg-black flex items-center justify-center cursor-pointer"
                                            onclick="openLightbox('{{ Storage::url($post->media_path) }}', 'image')">
                                            <img src="{{ Storage::url($post->media_path) }}"
                                                alt="{{ $post->title }}"
                                                class="w-full h-full object-cover hover:opacity-90 transition-opacity">
                                        </div>
                                    @else
                                        <div class="aspect-video bg-black flex items-center justify-center cursor-pointer relative group"
                                            onclick="openLightbox('{{ Storage::url($post->media_path) }}', 'video')">
                                            <video class="w-full h-full object-cover">
                                                <source src="{{ Storage::url($post->media_path) }}" type="video/mp4">
                                            </video>
                                            <svg class="absolute w-12 h-12 text-white opacity-70 group-hover:opacity-100 transition-opacity"
                                                fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M8 5v14l11-7z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            @php
                                $score = $post->votes()->sum('value');
                                $userVote = auth()->check()
                                    ? $post
                                        ->votes()
                                        ->where('user_id', auth()->id())
                                        ->value('value')
                                    : null;
                                $isOwner = auth()->check() && auth()->id() === $post->user_id;
                            @endphp

                            <div class="flex items-center gap-3 mt-4" onclick="event.stopPropagation();">
                                <div
                                    class="inline-flex items-center gap-3 px-4 py-2 rounded-full bg-slate-100 dark:bg-slate-800 text-sm text-slate-600 dark:text-slate-300">
                                    @auth
                                        <form class="vote-form" data-post="{{ $post->id }}"
                                            action="{{ route('posts.vote', $post) }}" method="POST"
                                            onclick="event.stopPropagation()">
                                            @csrf
                                            <input type="hidden" name="value" value="1">
                                            <button type="submit" data-type="up"
                                                class="vote-btn font-bold transition {{ $userVote === 1 ? 'text-orange-500' : 'hover:text-orange-400' }}">▲</button>
                                        </form>
                                    @else
                                        <span class="font-bold text-slate-400">▲</span>
                                    @endauth
                                    <span class="vote-score font-semibold min-w-8 text-center"
                                        data-post="{{ $post->id }}">{{ $score }}</span>
                                    @auth
                                        <form class="vote-form" data-post="{{ $post->id }}"
                                            action="{{ route('posts.vote', $post) }}" method="POST"
                                            onclick="event.stopPropagation()">
                                            @csrf
                                            <input type="hidden" name="value" value="-1">
                                            <button type="submit" data-type="down"
                                                class="vote-btn font-bold transition {{ $userVote === -1 ? 'text-blue-500' : 'hover:text-blue-400' }}">▼</button>
                                        </form>
                                    @else
                                        <span class="font-bold text-slate-400">▼</span>
                                    @endauth
                                </div>
                                <a href="{{ route('posts.show', $post) }}#comments"
                                    onclick="event.stopPropagation();"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-slate-100 dark:bg-slate-800 text-xs text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8 10h8m-8 4h5m-7 8l-4 4V5a2 2 0 012-2h14a2 2 0 012 2v11a2 2 0 01-2 2H8z" />
                                    </svg>
                                    <span class="font-medium">{{ $post->comments()->count() }}</span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $posts->links() }}
                </div>
            @else
                <div class="bg-slate-50 dark:bg-slate-800 rounded-lg p-8 text-center">
                    <p class="text-slate-600 dark:text-slate-400 mb-4">Belum ada postingan</p>
                    @auth
                        @if (Auth::id() === $user->id)
                            <a href="{{ route('posts.create') }}"
                                class="inline-block px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-colors">
                                Buat Postingan Pertama
                            </a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>
    </main>

    <script>
        function openLightbox(src, type) {
            const modal = document.getElementById('lightboxModal');
            const image = document.getElementById('lightboxImage');
            const video = document.getElementById('lightboxVideo');
            const videoSource = document.getElementById('lightboxVideoSource');

            image.classList.add('hidden');
            video.classList.add('hidden');
            video.pause();

            if (type === 'image') {
                image.src = src;
                image.classList.remove('hidden');
            } else if (type === 'video') {
                videoSource.src = src;
                video.load();
                video.classList.remove('hidden');

                video.addEventListener('loadeddata', function playOnce() {
                    video.play().catch(err => console.error('Video play error:', err));
                    video.removeEventListener('loadeddata', playOnce);
                });
            }

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

        document.getElementById('lightboxModal')?.addEventListener('click', (e) => {
            if (e.target === document.getElementById('lightboxModal')) {
                closeLightbox();
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeLightbox();
            }
        });
    </script>

    <script>
        document.getElementById('theme-toggle')?.addEventListener('click', () => {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('dark-mode', isDark);
        });

        function exportToExcel() {
            // Cek apakah library XLSX sudah ter-load
            if (typeof XLSX === 'undefined') {
                // Coba load library secara dynamic
                const script = document.createElement('script');
                script.src = 'https://unpkg.com/xlsx@0.18.5/dist/xlsx.full.min.js';
                script.onload = () => {
                    console.log('XLSX loaded, retrying export...');
                    setTimeout(exportToExcel, 500); // Retry setelah loaded
                };
                script.onerror = () => {
                    alert('Gagal memuat library Excel. Periksa koneksi internet Anda.');
                };
                document.head.appendChild(script);
                return;
            }

            try {
                // Data header
                const userData = [
                    ['Informasi Profil'],
                    [],
                    ['Field', 'Value'],
                    ['Username', '{{ $user->name }}'],
                    ['Email', '{{ $user->email }}'],
                    @if ($user->location)
                        ['Lokasi', '{{ $user->location }}'],
                    @endif
                    @if ($user->website)
                        ['Website', '{{ $user->website }}'],
                    @endif
                    @if ($user->bio)
                        ['Bio', '{{ addslashes($user->bio) }}'],
                    @endif
                    ['Bergabung', '{{ $user->created_at->format('d M Y') }}'],
                    [],
                    ['Statistik'],
                    [],
                    ['Metric', 'Total'],
                    ['Total Postingan', {{ $totalPosts }}],
                    ['Total Komentar', {{ $totalComments }}],
                    ['Total Karma', {{ $totalVotes }}]
                ];

                // Buat worksheet dari array
                const worksheet = XLSX.utils.aoa_to_sheet(userData);

                // Set column widths
                worksheet['!cols'] = [{
                        wch: 20
                    }, // Column A
                    {
                        wch: 50
                    } // Column B
                ];

                // Merge cells untuk judul
                if (!worksheet['!merges']) worksheet['!merges'] = [];
                worksheet['!merges'].push({
                        s: {
                            r: 0,
                            c: 0
                        },
                        e: {
                            r: 0,
                            c: 1
                        }
                    }, // Merge A1:B1
                    {
                        s: {
                            r: 7,
                            c: 0
                        },
                        e: {
                            r: 7,
                            c: 1
                        }
                    } // Merge untuk "Statistik"
                );

                // Buat workbook
                const workbook = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(workbook, worksheet, "Profil");

                // Generate filename dengan timestamp
                const timestamp = new Date().toISOString().slice(0, 19).replace(/:/g, '-');
                const fileName = `Profil_{{ str_replace(' ', '_', $user->name) }}_${timestamp}.xlsx`;

                // Download file
                XLSX.writeFile(workbook, fileName);

                console.log('Excel exported successfully:', fileName);

            } catch (error) {
                console.error('Export error details:', error);
                alert('Gagal mengexport ke Excel: ' + error.message);
            }
        }

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

            // Reset select after sorting
            setTimeout(() => {
                document.getElementById('sortPosts').value = '';
            }, 300);
        }
    </script>

    <script>
        document.querySelectorAll('.vote-form').forEach(form => {
            form.addEventListener('submit', async e => {
                e.preventDefault();

                const postId = form.dataset.post;
                const value = form.querySelector('input[name="value"]').value;
                const token = form.querySelector('input[name="_token"]').value;

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
                    if (!res.ok) throw new Error();
                    const data = await res.json();
                    document.querySelector(`.vote-score[data-post="${postId}"]`).textContent = data
                        .score;
                    if (data.totalKarma !== undefined) document.getElementById('karma-count')
                        .textContent = data.totalKarma;
                    document.querySelectorAll(`.vote-form[data-post="${postId}"] button`).forEach(btn =>
                        btn.classList.remove('text-orange-500', 'text-blue-500'));
                    if (data.userVote === 1) form.querySelector('button:first-of-type')?.classList.add(
                        'text-orange-500');
                    if (data.userVote === -1) form.querySelector('button:last-of-type')?.classList.add(
                        'text-blue-500');
                } catch {
                    alert('Gagal melakukan vote');
                }
            });
        });
    </script>

    <div id="lightboxModal"
        class="fixed inset-0 z-50 opacity-0 pointer-events-none bg-black/90 items-center justify-center p-4"
        style="display: flex;">
        <div class="relative w-full h-full max-w-4xl max-h-screen flex items-center justify-center">
            <button onclick="closeLightbox()"
                class="absolute top-4 right-4 text-white hover:bg-white/20 p-2 rounded-full z-10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <img id="lightboxImage" src="" alt=""
                class="max-w-full max-h-full object-contain hidden rounded-lg">
            <video id="lightboxVideo" class="max-w-full max-h-full object-contain hidden rounded-lg" controls>
                <source id="lightboxVideoSource" src="" type="video/mp4">Browser Anda tidak mendukung video
                HTML5.
            </video>
        </div>
    </div>
</body>

</html>
