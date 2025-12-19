<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Album;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AlbumController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Альбомы только текущего пользователя, новые в конце списка
        $albums = $user->albums()
            ->orderBy('created_at', 'asc') // или ->oldest()
            ->get();

        return view('albums.index', compact('albums', 'user'));
    }

    public function show(Album $album)
    {
        $album->load(['comments.user']);

        $currentUser = auth()->user();
        if ($currentUser) {
            $currentUser->loadMissing('friends');
        }

        return view('albums.show', compact('album', 'currentUser'));
    }

    public function create()
    {
        // Пустая модель для формы
        $album = new Album();

        return view('albums.create', compact('album'));
    }

    public function store(Request $request)
    {
        $data = $this->validateAlbum($request);

        // Привязываем альбом к текущему пользователю
        $data['user_id'] = auth()->id();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('albums', 'public');
            $data['image_path'] = $path;
        }

        Album::create($data);

        return redirect()
            ->route('albums.index')
            ->with('success', 'Альбом успешно добавлен.');
    }
    
    public function edit(Album $album)
    {
        Gate::authorize('update-album', $album);

        return view('albums.edit', compact('album'));
    }

    public function update(Request $request, Album $album)
    {
        Gate::authorize('update-album', $album);

        $data = $this->validateAlbum($request, true);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('albums', 'public');
            $data['image_path'] = $path;
        }

        $album->update($data);

        return redirect()
            ->route('albums.show', $album)
            ->with('success', 'Альбом обновлён.');
    }

    public function destroy(Album $album)
    {
        Gate::authorize('delete-album', $album);

        $album->delete(); // Soft Delete

        return redirect()
            ->route('albums.index')
            ->with('success', 'Альбом удалён.');
    }

    public function usersList()
    {
        $users = User::orderBy('name')->get();
        $currentUser = auth()->user()->load('friends');

        return view('users.index', compact('users', 'currentUser'));
    }

    public function userAlbums(User $user)
    {
        $currentUser = auth()->user();

        // Если админ — видит и удалённые, иначе только живые
        $query = $user->albums()->orderByDesc('created_at');

        if ($currentUser->is_admin) {
            $albums = $query->withTrashed()->get();
        } else {
            $albums = $query->get();
        }

        return view('albums.user_albums', compact('user', 'albums', 'currentUser'));
    }

    public function restore(Album $album)
    {
        Gate::authorize('restore-album', $album);

        $album->restore();

        return back()->with('success', 'Альбом восстановлен.');
    }

    public function forceDelete(Album $album)
    {
        Gate::authorize('force-delete-album', $album);

        $album->forceDelete();

        return back()->with('success', 'Альбом удалён безвозвратно.');
    }

    public function feed()
    {
        $user = auth()->user()->loadMissing('friends');

        // id всех друзей
        $friendIds = $user->friends->pluck('id');

        if ($friendIds->isEmpty()) {
            $albums = collect();
        } else {
            $albums = Album::whereIn('user_id', $friendIds)
                ->latest()                 // по created_at DESC
                ->with('user')
                ->get();
        }

        return view('albums.feed', compact('albums', 'user'));
    }

    protected function validateAlbum(Request $request, bool $isUpdate = false): array
    {
        $rules = [
            'title'        => ['required', 'string', 'max:255'],
            'description'  => ['required', 'string'],
            'release_date' => ['required', 'date_format:d.m.Y'],
        ];

        // При создании картинка обязательна, при редактировании — нет
        if ($isUpdate) {
            $rules['image'] = ['nullable', 'image', 'max:2048'];
        } else {
            $rules['image'] = ['required', 'image', 'max:2048'];
        }

        return $request->validate($rules);
    }
}


