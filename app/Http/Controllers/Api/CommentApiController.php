<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Album;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentApiController extends Controller
{
    public function index(Request $request, Album $album)
    {
        $comments = $album->comments()
            ->with(['user', 'album'])
            ->latest()
            ->get();

        return CommentResource::collection($comments);
    }

    public function store(Request $request, Album $album)
    {
        $user = $request->user();

        $data = $request->validate([
            'text' => ['required', 'string', 'max:2000'],
        ]);

        $data['user_id']  = $user->id;
        $data['album_id'] = $album->id;

        $comment = Comment::create($data);

        return (new CommentResource($comment->load(['user', 'album'])))
            ->response()
            ->setStatusCode(201);
    }

    public function update(Request $request, Comment $comment)
    {
        $user = $request->user();

        if (!$user->is_admin && $comment->user_id !== $user->id) {
            abort(403, 'Можно изменять только свои комментарии');
        }

        $data = $request->validate([
            'text' => ['required', 'string', 'max:2000'],
        ]);

        $comment->update($data);

        return new CommentResource($comment->load(['user', 'album']));
    }
}