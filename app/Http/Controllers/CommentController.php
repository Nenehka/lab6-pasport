<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Album $album)
    {
        // Авторизованный пользователь может комментировать любые альбомы
        $data = $request->validate([
            'text' => ['required', 'string', 'max:2000'],
        ]);

        $data['user_id']  = Auth::id();
        $data['album_id'] = $album->id;

        Comment::create($data);

        return redirect()
            ->route('albums.show', $album)
            ->with('success', 'Комментарий добавлен.');
    }

    public function destroy(Comment $comment)
    {
        $user = Auth::user();

        // Правило: свой комментарий может удалить автор или админ
        if (!$user->is_admin && $comment->user_id !== $user->id) {
            abort(403);
        }

        $album = $comment->album;

        $comment->delete();

        return redirect()
            ->route('albums.show', $album)
            ->with('success', 'Комментарий удалён.');
    }
}