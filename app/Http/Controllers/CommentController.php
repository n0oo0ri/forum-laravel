<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function destroy(Comment $comment)
    {
        if (!auth()->check() || auth()->id() !== $comment->user_id) {
            abort(403);
        }

        $post = $comment->post;
        $comment->delete();

        return redirect()->route('posts.show', $post);
    }
}
