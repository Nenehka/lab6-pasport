@extends('layouts.app')

@section('title', $album->title)

@section('content')
<div class="container py-4">
    <a href="{{ route('albums.index') }}" class="btn btn-secondary mb-3">
        ← Назад к списку
    </a>

    <div class="card">
        @if($album->image_path)
            <img src="{{ asset('storage/'.$album->image_path) }}"
                 class="card-img-top"
                 alt="{{ $album->title }}">
        @endif

        <div class="card-body">
            <h1 class="card-title">{{ $album->title }}</h1>

            @if($album->release_date)
                <p><strong>Дата выхода:</strong> {{ $album->release_date }}</p>
            @endif

            @if($album->description)
                <p class="card-text">{{ $album->description }}</p>
            @endif
        </div>
        <hr class="my-4">

        <div class="mt-4">
            <h3 class="mb-3">Комментарии</h3>

            {{-- Форма добавления комментария --}}
            @auth
                <form action="{{ route('albums.comments.store', $album) }}" method="POST" class="mb-4">
                    @csrf
                    <div class="mb-3">
                        <label for="comment-text" class="form-label">Оставить комментарий</label>
                        <textarea
                            id="comment-text"
                            name="text"
                            class="form-control @error('text') is-invalid @enderror"
                            rows="3"
                            required
                            maxlength="2000"
                        >{{ old('text') }}</textarea>
                        @error('text')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Добавить комментарий</button>
                </form>
            @else
                <p>
                    Только авторизованные пользователи могут оставлять комментарии.
                    <a href="{{ route('login') }}">Войдите</a> или
                    <a href="{{ route('register') }}">зарегистрируйтесь</a>.
                </p>
            @endauth

            {{-- Список комментариев --}}
            @if($album->comments->isEmpty())
                <p class="text-muted">Пока нет ни одного комментария.</p>
            @else
                <ul class="list-group">
                    @foreach($album->comments->sortByDesc('created_at') as $comment)
                        @php
                            $isFriendComment = $currentUser
                                && $currentUser->friends->contains($comment->user_id);
                        @endphp

                        <li class="list-group-item d-flex justify-content-between align-items-start
                                @if($isFriendComment) comment-friend @endif">
                            <div>
                                <div class="fw-bold">
                                    {{ $comment->user->name ?? 'Пользователь #'.$comment->user_id }}
                                    <small class="text-muted">
                                        · {{ $comment->created_at->format('d.m.Y H:i') }}
                                    </small>

                                    @if($isFriendComment)
                                        <span class="badge bg-info text-dark ms-2">Друг</span>
                                    @endif
                                </div>
                                <div>{{ $comment->text }}</div>
                            </div>
                            @auth
                                @if($currentUser->is_admin || $comment->user_id === $currentUser->id)
                                    <form action="{{ route('comments.destroy', $comment) }}"
                                        method="POST"
                                        onsubmit="return confirm('Удалить комментарий?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            Удалить
                                        </button>
                                    </form>
                                @endif
                            @endauth
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
@endsection