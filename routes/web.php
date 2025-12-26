<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

// Public routes
Route::get('/', [PostController::class, 'index'])->name('home');
Route::get('/posts/{post}', [PostController::class, 'show'])
    ->whereNumber('post')
    ->name('posts.show');
Route::get('/users/{user}', [UserController::class, 'profile'])
    ->name('users.profile');

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Protected routes (Require Authentication)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::get('/posts/create-new', [PostController::class, 'createWithLivewire'])->name('posts.create-new');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/posts/{post}/vote', [VoteController::class, 'store'])->name('posts.vote');
    Route::post('/posts/{post}/comment', [PostController::class, 'storeComment'])->name('posts.comment');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    
    // User profile routes
    Route::get('/profile/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/profile', [UserController::class, 'update'])->name('users.update');
});


