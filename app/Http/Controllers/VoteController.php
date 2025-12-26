<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoteController extends Controller
{
    public function store(Request $request, Post $post)
{
    $request->validate([
        'value' => 'required|in:1,-1',
    ]);

    $user = auth()->user();
    $value = (int) $request->value;

    $vote = Vote::where('user_id', $user->id)
        ->where('post_id', $post->id)
        ->first();

    if ($vote) {
        // klik vote yang sama → cancel
        if ($vote->value === $value) {
            $vote->delete();
            $userVote = null;
        } else {
            // ganti vote
            $vote->update(['value' => $value]);
            $userVote = $value;
        }
    } else {
        Vote::create([
            'user_id' => $user->id,
            'post_id' => $post->id,
            'value' => $value,
        ]);
        $userVote = $value;
    }

    // 🔥 HITUNG SCORE POST
    $score = $post->votes()->sum('value');

    // 🔥 HITUNG KARMA PEMILIK PROFILE (BUKAN YANG VOTE)
    $totalKarma = Vote::whereHas('post', function ($q) use ($post) {
        $q->where('user_id', $post->user_id);
    })->sum('value');

    return response()->json([
        'score' => $score,
        'userVote' => $userVote,
        'totalKarma' => $totalKarma,
    ]);
}

}
