<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlbumController;
use App\Models\User;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FriendController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Домашняя страница
Route::get('/', function () {
    return view('home');
})->name('home');

// После логина Breeze ведёт на этот маршрут
Route::get('/dashboard', function () {
    return redirect()->route('albums.index');
})->middleware(['auth'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::resource('albums', AlbumController::class);

    Route::get('/users', [AlbumController::class, 'usersList'])
        ->name('users.index');

    Route::get('/users/{user}/albums', [AlbumController::class, 'userAlbums'])
        ->name('users.albums');

        // Дружба
    Route::post('/users/{user}/friends', [FriendController::class, 'store'])
        ->name('friends.store');

    Route::delete('/users/{user}/friends', [FriendController::class, 'destroy'])
        ->name('friends.destroy');

    Route::post('/albums/{album}/restore', [AlbumController::class, 'restore'])
        ->name('albums.restore');

    Route::delete('/albums/{album}/force-delete', [AlbumController::class, 'forceDelete'])
        ->name('albums.forceDelete');

        // Добавление комментария к альбому
    Route::post('/albums/{album}/comments', [CommentController::class, 'store'])
        ->name('albums.comments.store');

    // Удаление комментария
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])
        ->name('comments.destroy');

    Route::get('/feed', [AlbumController::class, 'feed'])
        ->name('feed');
});

require __DIR__.'/auth.php';