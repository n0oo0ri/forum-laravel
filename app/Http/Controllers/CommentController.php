<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function destroy(Comment $comment)
    {
        if (!Auth::check() || Auth::id() !== $comment->user_id) {
            abort(403);
        }

        $post = $comment->post;
        $comment->delete();

        // Return JSON for AJAX requests
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Komentar berhasil dihapus'
            ]);
        }

        return redirect()->route('posts.show', $post);
    }
}
