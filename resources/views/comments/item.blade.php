<div
    class="bg-white dark:bg-slate-900 rounded-lg shadow dark:shadow-lg border border-slate-200 dark:border-slate-800 p-6">
    <div class="flex justify-between items-start mb-4">
        <div>
            <p class="font-semibold">{{ $comment->user->name }}</p>
            <p class="text-xs text-slate-600 dark:text-slate-400" title="{{ $comment->created_at->format('d M Y H:i') }}">
                {{ $comment->created_at->diffForHumans() }}</p>
        </div>
        @auth
            @if (auth()->id() === $comment->user_id)
                <button onclick="deleteComment({{ $comment->id }})"
                    class="text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 text-sm font-semibold">
                    Hapus
                </button>
            @endif
        @endauth
    </div>

    <p class="text-slate-700 dark:text-slate-300 mb-4">
        {!! preg_replace(
            '/@(\w+)/',
            '<span class="text-blue-600 dark:text-blue-400 font-semibold">@$1</span>',
            e($comment->body),
        ) !!}
    </p>

    <div class="flex gap-4">
        @auth
            <button onclick="toggleReplyForm({{ $comment->id }})"
                class="text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200 font-semibold">
                Balas
            </button>
        @else
            <a href="{{ route('login') }}"
                class="text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200 font-semibold">
                Balas
            </a>
        @endauth
    </div>

    <!-- Reply Form -->
    @auth
        <div id="reply-form-{{ $comment->id }}"
            class="hidden mt-4 p-4 bg-slate-50 dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700">
            <form method="POST" action="{{ route('posts.comment', $post) }}">
                @csrf
                <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                <textarea name="body" placeholder="Balas komentar..." required
                    class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg dark:bg-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-slate-900 dark:focus:ring-slate-100 mb-3 min-h-20"
                    data-mention="{{ $comment->user->name }}"></textarea>
                <div class="flex gap-2">
                    <button type="submit"
                        class="bg-slate-900 dark:bg-slate-100 text-white dark:text-slate-900 px-4 py-2 rounded text-sm font-semibold hover:bg-slate-800 dark:hover:bg-slate-200 transition-colors">
                        Kirim
                    </button>
                    <button type="button" onclick="toggleReplyForm({{ $comment->id }})"
                        class="bg-slate-300 dark:bg-slate-700 px-4 py-2 rounded text-sm font-semibold hover:bg-slate-400 dark:hover:bg-slate-600 transition-colors">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    @endauth

    <!-- Replies -->
    @if ($comment->replies->count() > 0)
        <div class="mt-4 ml-6 pl-4 border-l-2 border-slate-300 dark:border-slate-600 space-y-3">
            @foreach ($comment->replies()->with('user')->oldest()->get() as $reply)
                @include('comments.reply-item', ['comment' => $reply, 'post' => $post])
            @endforeach
        </div>
    @endif
</div>
