<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Comment\CommentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Posts\PublicPostController;
use App\Http\Controllers\Posts\AdminPostController;

// Public routes
Route::prefix('posts')->group(function () {
    Route::get('/', [PublicPostController::class, 'index'])->name('posts.index');
    Route::get('/{slug}', [PublicPostController::class, 'show'])->name('posts.show');
    Route::post('/upvote', [PublicPostController::class, 'upvote'])->name('posts.upvote');
    Route::post('/downvote', [PublicPostController::class, 'downvote'])->name('posts.downvote');
});

// Admin routes
Route::prefix('admin')->middleware('adminAuthorization')->group(function () {
    Route::get('/posts', [AdminPostController::class, 'index'])->name('admin.posts.index');
    Route::post('/posts', [AdminPostController::class, 'store'])->name('admin.posts.store');
    Route::put('/posts/{id}', [AdminPostController::class, 'update'])->name('admin.posts.update');
    Route::delete('/posts/{id}', [AdminPostController::class, 'destroy'])->name('admin.posts.destroy');
});

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
});

Route::prefix('comments')->middleware('jwt')->group(function () {
    Route::get('/{postId}', [CommentController::class, 'index'])->name('comments.index');
    Route::post('create/{postId}', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/{commentId}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/{commentId}', [CommentController::class, 'destroy'])->name('comments.destroy');
});
