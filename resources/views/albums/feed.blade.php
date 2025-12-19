@extends('layouts.app')

@section('title', 'Лента друзей')

@section('content')
<div class="container-fluid container-xxl my-5">
    <h2 class="mb-4">Лента друзей</h2>

    @if($albums->isEmpty())
        <p>В ленте пока пусто. Добавьте кого-нибудь в друзья и дождитесь их новых альбомов.</p>
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

                            <p class="mt-2 mb-0 text-muted" style="font-size: 0.85rem">
                                Автор: {{ $album->user->name ?? 'Пользователь #'.$album->user_id }}<br>
                                Создан: {{ $album->created_at->format('d.m.Y H:i') }}
                            </p>
                        </div>

                        <div class="card-footer text-center">
                            <button class="btn btn-btn btn-detail mb-2 w-100"
                                    data-index="{{ $index }}">
                                Подробнее
                            </button>

                            <a href="{{ route('albums.show', $album) }}"
                               class="btn btn-primary w-100">
                                Перейти к альбому
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
