<div class="bg-slate-50 dark:bg-slate-800 rounded p-4 border border-slate-200 dark:border-slate-700">
    <div class="flex justify-between items-start mb-2">
        <div>
            <p class="font-semibold text-sm">{{ $comment->user->name }}</p>
            <p class="text-xs text-slate-600 dark:text-slate-400" title="{{ $comment->created_at->format('d M Y H:i') }}">{{ $comment->created_at->diffForHumans() }}</p>
        </div>
        @auth
            @if(auth()->id() === $comment->user_id)
                <button onclick="deleteComment({{ $comment->id }})" class="text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 text-xs font-semibold">
                    Hapus
                </button>
            @endif
        @endauth
    </div>
    <p class="text-slate-700 dark:text-slate-300 text-sm">{{ $comment->body }}</p>
</div>
