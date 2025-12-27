<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Vote;

class UserController extends Controller
{
    public function profile(User $user)
{
    $posts = $user->posts()->latest()->paginate(10);
    $totalPosts = $user->posts()->count();
    $totalComments = $user->comments()->count();

    // 🔥 REDDIT-LIKE KARMA
    $totalVotes = Vote::whereHas('post', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->sum('value');

    return view('users.profile', compact(
        'user',
        'posts',
        'totalPosts',
        'totalComments',
        'totalVotes'
    ));
}

    public function edit()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login');
        }

        $user = Auth::user();
        return view('users.edit', compact('user'));
    }

    public function update(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        $validated = $request->validate([
            'name' => [
        'required',
        'string',
        'max:255',
        'regex:/^\S+$/'
    ],
            'email' => 'required|email|unique:users,email,' . $user->id,
            'bio' => 'nullable|string|max:500',
            'location' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'bio.max' => 'Bio maksimal 500 karakter',
            'location.max' => 'Lokasi maksimal 255 karakter',
            'website.url' => 'URL website tidak valid',
            'avatar.image' => 'File harus berupa gambar',
            'avatar.mimes' => 'Format gambar harus JPEG, PNG, JPG, atau GIF',
            'avatar.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar jika ada
            if ($user->avatar && file_exists(storage_path('app/public/' . $user->avatar))) {
                unlink(storage_path('app/public/' . $user->avatar));
            }

            $file = $request->file('avatar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('avatars', $filename, 'public');
            $validated['avatar'] = 'avatars/' . $filename;
        }

        $user->update($validated);

        return redirect()->route('users.profile', $user)->with('success', 'Profil berhasil diperbarui!');
    }
}
