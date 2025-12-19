@extends('layouts.app')

@section('title', 'Альбомы группы Rammstein')

@section('content')
    <div class="title text-center mb-4">
        <h2 class="title-text">Альбомы группы</h2>
    </div>

    <div class="container-fluid container-xxl my-5">
        <div class="d-flex flex-wrap align-items-center mb-3">
            <div class="d-flex gap-2 ms-auto">
                <a href="{{ route('users.index') }}" class="btn btn-outline-light">
                    Список пользователей
                </a>
                <a href="{{ route('albums.create') }}" class="btn btn-success">
                    Добавить альбом
                </a>
            </div>
        </div>

        <div class="row g-3 g-sm-4 cards-row justify-content-center">
            @forelse($albums as $index => $album)
                <div class="col-12 col-sm-6 col-md-4 col-xl-3 col-xxxl-5col">
                    <div class="card h-100 shadow-sm"
                        data-index="{{ $index }}"
                        data-title="{{ $album->title }}"
                        data-description="{{ $album->description }}"
                        data-release="{{ $album->release_date }}">

                        @if($album->image_path)
                            <img class="card-img-top img-fluid"
                                 src="{{ asset('storage/'.$album->image_path) }}"
                                 alt="{{ $album->title }}">
                        @else
                            {{-- Пустой img, если нет картинки (чтобы JS/верстка не ломались) --}}
                            <img class="card-img-top img-fluid"
                                 src=""
                                 alt="{{ $album->title }}">
                        @endif

                        <div class="type">{{ $album->title }}</div>

                        <div class="card-body">
                            <h5 class="card-title">{{ $album->title }}</h5>

                            @if($album->description)
                                <p class="card-text">{{ $album->description }}</p>
                            @endif

                            @if($album->release_date)
                                <p class="card-text release-date">
                                    Год выпуска: {{ $album->release_date }}
                                </p>
                            @endif
                        </div>

                        <div class="card-footer text-center">
                            {{-- Кнопка модалки --}}
                            <button class="btn btn-btn btn-detail mb-2 w-100"
                                    data-index="{{ $index }}">
                                Подробнее
                            </button>

                            {{-- Детальная страница доступна всем авторизованным --}}
                            <a href="{{ route('albums.show', $album) }}"
                            class="btn btn-primary mb-2 w-100">
                                Детальная страница
                            </a>

                            {{-- Редактировать можно только если Gate разрешает --}}
                            @can('update-album', $album)
                                <a href="{{ route('albums.edit', $album) }}"
                                class="btn btn-secondary mb-2 w-100">
                                    Редактировать
                                </a>
                            @endcan

                            {{-- Удалять можно только если Gate разрешает --}}
                            @can('delete-album', $album)
                                <form action="{{ route('albums.destroy', $album) }}"
                                    method="POST"
                                    onsubmit="return confirm('Удалить этот альбом?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100">
                                        Удалить
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center">Пока нет ни одного альбома.</p>
            @endforelse
        </div>
    </div>
@endsection