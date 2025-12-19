@extends('layouts.app')

@section('title', 'Альбомы пользователя '.$user->name)

@section('content')
<div class="container-fluid container-xxl my-5">
    <h2 class="mb-4">
        Альбомы пользователя {{ $user->name }}
    </h2>

    @if($albums->isEmpty())
        <p>У этого пользователя пока нет ни одного альбома.</p>
    @else
        <div class="row g-3 g-sm-4 cards-row justify-content-center">
            @foreach($albums as $index => $album)
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
                            <img class="card-img-top img-fluid"
                                 src=""
                                 alt="{{ $album->title }}">
                        @endif

                        <div class="type">
                            {{ $album->title }}
                            @if($album->trashed())
                                <span class="badge bg-warning text-dark ms-2">Удалён</span>
                            @endif
                        </div>

                        <div class="card-body">
                            <h5 class="card-title">{{ $album->title }}</h5>

                            @if($album->description)
                                <p class="card-text">{{ $album->description }}</p>
                            @endif

                            @if($album->release_date)
                                <p class="card-text">
                                    Год выпуска: {{ $album->release_date }}
                                </p>
                            @endif
                        </div>

                        <div class="card-footer text-center">
                            {{-- Модалка и детальная страница доступны всем --}}
                            <button class="btn btn-btn btn-detail mb-2 w-100"
                                    data-index="{{ $index }}">
                                Подробнее
                            </button>

                            <a href="{{ route('albums.show', $album) }}"
                               class="btn btn-primary mb-2 w-100">
                                Детальная страница
                            </a>

                            {{-- Админ может восстанавливать и удалять навсегда --}}
                            @if($currentUser->is_admin)
                                @if($album->trashed())
                                    <form action="{{ route('albums.restore', $album) }}"
                                          method="POST" class="mb-2">
                                        @csrf
                                        <button type="submit" class="btn btn-success w-100">
                                            Восстановить
                                        </button>
                                    </form>

                                    <form action="{{ route('albums.forceDelete', $album) }}"
                                          method="POST"
                                          onsubmit="return confirm('Удалить безвозвратно?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger w-100">
                                            Удалить навсегда
                                        </button>
                                    </form>
                                @else
                                    {{-- Для не удалённых альбомов админ может удалить мягко --}}
                                    <form action="{{ route('albums.destroy', $album) }}"
                                          method="POST"
                                          onsubmit="return confirm('Удалить альбом?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-warning w-100">
                                            Мягко удалить
                                        </button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection