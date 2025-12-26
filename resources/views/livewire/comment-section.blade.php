<div>
    @auth
        <form wire:submit.prevent="submit">
            <textarea wire:model.defer="body"></textarea>
            <button type="submit">Kirim</button>
        </form>
    @else
        <p>
            <a href="{{ route('login') }}">Login</a> untuk berkomentar.
        </p>
    @endauth

    <hr>

    @foreach ($comments as $comment)
        <div>
            <strong>{{ $comment->user->name }}</strong><br>
            {{ $comment->body }}
        </div>
    @endforeach
</div>