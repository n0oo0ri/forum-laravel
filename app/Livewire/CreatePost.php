<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;
use App\Models\Community;

class CreatePost extends Component
{
    public $title = '';
    public $body = '';
    public $community_id = '';
    public $communities = [];

    public function mount()
    {
        $this->communities = Community::all();
    }

    public function store()
    {
        $this->validate([
            'title' => 'required|min:5|max:255',
            'body' => 'required|min:10',
            'community_id' => 'nullable|exists:communities,id',
        ], [
            'title.required' => 'Judul postingan harus diisi',
            'title.min' => 'Judul minimal 5 karakter',
            'title.max' => 'Judul maksimal 255 karakter',
            'body.required' => 'Isi postingan harus diisi',
            'body.min' => 'Isi postingan minimal 10 karakter',
            'community_id.exists' => 'Komunitas tidak ditemukan',
        ]);

        Post::create([
            'title' => $this->title,
            'body' => $this->body,
            'user_id' => auth()->id(),
            'community_id' => $this->community_id ?: null,
        ]);

        session()->flash('success', 'Postingan berhasil dibuat!');
        
        return redirect()->route('home');
    }

    public function render()
    {
        return view('livewire.create-post');
    }
}
