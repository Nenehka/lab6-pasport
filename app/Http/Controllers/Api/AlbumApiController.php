<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AlbumResource;
use App\Models\Album;
use Illuminate\Http\Request;

class AlbumApiController extends Controller
{
    public function index(Request $request)
    {
        $albums = Album::with('user')->latest()->get();
        return AlbumResource::collection($albums);
    }

    public function show(Request $request, Album $album)
    {
        $album->load('user');
        return new AlbumResource($album);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string'],
            'release_date' => ['nullable', 'date_format:d.m.Y'],
            // картинку через API можно пока не обрабатывать
        ]);

        $data['user_id'] = $user->id;

        $album = Album::create($data);

        return (new AlbumResource($album->load('user')))
            ->response()
            ->setStatusCode(201);
    }

    public function update(Request $request, Album $album)
    {
        $user = $request->user();

        if (!$user->is_admin && $album->user_id !== $user->id) {
            abort(403, 'Можно изменять только свои альбомы');
        }

        $data = $request->validate([
            'title'        => ['sometimes', 'required', 'string', 'max:255'],
            'description'  => ['nullable', 'string'],
            'release_date' => ['nullable', 'date_format:d.m.Y'],
        ]);

        $album->update($data);

        return new AlbumResource($album->load('user'));
    }
}