<div class="bg-slate-50 dark:bg-slate-800 rounded p-4 border border-slate-200 dark:border-slate-700">
    <div class="flex justify-between items-start mb-2">
        <div>
            <p class="font-semibold text-sm">{{ $comment->user->name }}</p>
            <p class="text-xs text-slate-600 dark:text-slate-400" title="{{ $comment->created_at->format('d M Y H:i') }}">
                {{ $comment->created_at->diffForHumans() }}</p>
        </div>
        @auth
            @if (auth()->id() === $comment->user_id)
                <button onclick="deleteComment({{ $comment->id }})"
                    class="text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 text-xs font-semibold">
                    Hapus
                </button>
            @endif
        @endauth
    </div>

    <!-- Comment body with mention parsing -->
    <p class="text-slate-700 dark:text-slate-300 text-sm mb-3">
        {!! preg_replace(
            '/@(\w+)/',
            '<span class="text-blue-600 dark:text-blue-400 font-semibold">@$1</span>',
            e($comment->body),
        ) !!}
    </p>

    <!-- Reply button -->
    <div class="flex gap-4">
        @auth
            <button onclick="toggleReplyForm({{ $comment->id }})"
                class="text-xs text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200 font-semibold">
                Balas
            </button>
        @else
            <a href="{{ route('login') }}"
                class="text-xs text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200 font-semibold">
                Balas
            </a>
        @endauth
    </div>

    <!-- Reply Form -->
    @auth
        <div id="reply-form-{{ $comment->id }}"
            class="hidden mt-3 p-3 bg-white dark:bg-slate-700 rounded border border-slate-200 dark:border-slate-600">
            <form method="POST" action="{{ route('posts.comment', $post) }}">
                @csrf
                <!-- Keep parent_id same as parent comment to maintain flat structure -->
                <input type="hidden" name="parent_id" value="{{ $comment->parent_id ?? $comment->id }}">
                <textarea name="body" placeholder="Balas komentar..." required
                    class="w-full px-3 py-2 text-sm border border-slate-300 dark:border-slate-600 rounded dark:bg-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 mb-2 min-h-16"
                    data-mention="{{ $comment->user->name }}"></textarea>
                <div class="flex gap-2">
                    <button type="submit"
                        class="bg-blue-600 dark:bg-blue-500 text-white px-3 py-1.5 rounded text-xs font-semibold hover:bg-blue-700 dark:hover:bg-blue-600 transition-colors">
                        Kirim
                    </button>
                    <button type="button" onclick="toggleReplyForm({{ $comment->id }})"
                        class="bg-slate-300 dark:bg-slate-600 px-3 py-1.5 rounded text-xs font-semibold hover:bg-slate-400 dark:hover:bg-slate-500 transition-colors">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    @endauth
</div>
