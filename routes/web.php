<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Posts\PublicPostController;
use App\Http\Controllers\Posts\AdminPostController;

// Public routes
Route::prefix('posts')->group(function () {
    Route::get('/', [PublicPostController::class, 'index'])->name('posts.index');
    Route::get('/{slug}', [PublicPostController::class, 'show'])->name('posts.show');
});

// Admin routes
Route::prefix('admin')->group(function () {
    Route::get('/posts', [AdminPostController::class, 'index'])->name('admin.posts.index');
    Route::post('/posts', [AdminPostController::class, 'store'])->name('admin.posts.store');
    Route::put('/posts/{id}', [AdminPostController::class, 'update'])->name('admin.posts.update');
    Route::delete('/posts/{id}', [AdminPostController::class, 'destroy'])->name('admin.posts.destroy');
});
