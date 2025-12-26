<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with(['user', 'community'])->latest();
        
        // Handle search
        if ($request->has('q') && $request->q) {
            $searchTerm = $request->q;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('body', 'like', '%' . $searchTerm . '%');
            });
        }
        
        $posts = $query->paginate(15);

        return view('welcome', compact('posts'));
    }

    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    public function create()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk membuat postingan');
        }

        return view('posts.create');
    }

    public function createWithLivewire()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk membuat postingan');
        }

        return view('posts.create-livewire');
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string|min:10',
            'media' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,webm,mov|max:50000', // Max 50MB
        ]);

        $mediaPath = null;
        $mediaType = null;

        // Handle file upload
        if ($request->hasFile('media')) {
            $file = $request->file('media');
            $mimeType = $file->getMimeType();
            
            // Tentukan tipe media
            if (str_starts_with($mimeType, 'image/')) {
                $mediaType = 'image';
                $filename = 'posts/images/' . time() . '_' . $file->getClientOriginalName();
            } else {
                $mediaType = 'video';
                $filename = 'posts/videos/' . time() . '_' . $file->getClientOriginalName();
            }
            
            // Simpan file
            $mediaPath = Storage::disk('public')->putFileAs('', $file, $filename);
        }

        $post = Post::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'body' => $validated['body'],
            'media_path' => $mediaPath,
            'media_type' => $mediaType,
        ]);

        // Return JSON for AJAX requests
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Postingan berhasil dibuat!',
                'redirect' => route('posts.show', $post)
            ]);
        }

        return redirect()->route('posts.show', $post)->with('success', 'Postingan berhasil dibuat!');
    }

    public function storeComment(Request $request, Post $post)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'body' => 'required|min:3|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        Comment::create([
            'body' => $validated['body'],
            'user_id' => auth()->id(),
            'post_id' => $post->id,
            'parent_id' => $validated['parent_id'] ?? null,
        ]);

        return redirect()->route('posts.show', $post);
    }

    public function edit(Post $post)
    {
        // Check authorization
        if (!Auth::check() || Auth::id() !== $post->user_id) {
            return redirect()->route('posts.show', $post)->with('error', 'Anda tidak bisa mengedit postingan ini');
        }

        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        // Check authorization
        if (!Auth::check() || Auth::id() !== $post->user_id) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            return redirect()->route('posts.show', $post)->with('error', 'Anda tidak bisa mengedit postingan ini');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string|min:10',
            'media' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,webm,mov|max:50000',
        ]);

        $mediaPath = $post->media_path;
        $mediaType = $post->media_type;

        // Handle new file upload
        if ($request->hasFile('media')) {
            // Delete old media jika ada
            if ($post->media_path && Storage::disk('public')->exists($post->media_path)) {
                Storage::disk('public')->delete($post->media_path);
            }

            $file = $request->file('media');
            $mimeType = $file->getMimeType();
            
            // Tentukan tipe media
            if (str_starts_with($mimeType, 'image/')) {
                $mediaType = 'image';
                $filename = 'posts/images/' . time() . '_' . $file->getClientOriginalName();
            } else {
                $mediaType = 'video';
                $filename = 'posts/videos/' . time() . '_' . $file->getClientOriginalName();
            }
            
            $mediaPath = Storage::disk('public')->putFileAs('', $file, $filename);
        }

        $post->update([
            'title' => $validated['title'],
            'body' => $validated['body'],
            'media_path' => $mediaPath,
            'media_type' => $mediaType,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Postingan berhasil diperbarui!',
                'redirect' => route('posts.show', $post)
            ]);
        }

        return redirect()->route('posts.show', $post)->with('success', 'Postingan berhasil diperbarui!');
    }

    public function destroy(Post $post)
    {
        // Check authorization
        if (!Auth::check() || Auth::id() !== $post->user_id) {
            return redirect()->route('posts.show', $post)->with('error', 'Anda tidak bisa menghapus postingan ini');
        }

        // Delete media jika ada
        if ($post->media_path && Storage::disk('public')->exists($post->media_path)) {
            Storage::disk('public')->delete($post->media_path);
        }

        // Delete all comments dan votes associated
        $post->comments()->delete();
        $post->votes()->delete();

        $post->delete();

        return redirect()->route('home')->with('success', 'Postingan berhasil dihapus!');
    }
}
