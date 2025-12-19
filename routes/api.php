<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AlbumApiController;
use App\Http\Controllers\Api\CommentApiController;

Route::middleware('auth:api')->group(function () {

    // Albums
    Route::get('/albums', [AlbumApiController::class, 'index']);
    Route::get('/albums/{album}', [AlbumApiController::class, 'show']);
    Route::post('/albums', [AlbumApiController::class, 'store']);
    Route::put('/albums/{album}', [AlbumApiController::class, 'update']);

    // Comments
    Route::get('/albums/{album}/comments', [CommentApiController::class, 'index']);
    Route::post('/albums/{album}/comments', [CommentApiController::class, 'store']);
    Route::put('/comments/{comment}', [CommentApiController::class, 'update']);
});