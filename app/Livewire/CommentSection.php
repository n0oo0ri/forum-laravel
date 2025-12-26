<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;
use App\Models\Comment;

class CommentSection extends Component
{
    public Post $post;
    public string $body = '';

    protected $rules = [
        'body' => 'required|min:3',
    ];

    public function submit()
{
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    $this->validate();

    Comment::create([
        'body' => $this->body,
        'user_id' => auth()->id(),
        'post_id' => $this->post->id,
    ]);

    $this->body = '';
}


    public function render()
    {
        return view('livewire.comment-section', [
        'comments' => $this->post
            ->comments()
            ->with('user')
            ->latest()
            ->get()
        ]);
    }
}



