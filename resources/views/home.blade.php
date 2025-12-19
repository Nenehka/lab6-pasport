@extends('layouts.app')

@section('title', 'Добро пожаловать')

@section('content')
<div class="container-fluid container-xxl my-5">
    <h1 class="mb-4 text-center title-text">Добро пожаловать!</h1>

    @guest
        <p class="text-center mb-4">
            Для доступа к альбомам войдите или зарегистрируйтесь.
        </p>
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('register') }}" class="btn btn-primary">
                Зарегистрироваться
            </a>
        </div>
    @else
        <p class="text-center mb-4">
            Вы вошли как {{ auth()->user()->name }}.
        </p>
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('albums.index') }}" class="btn btn-primary">
                Перейти к альбомам
            </a>
            <a href="{{ route('feed') }}" class="btn btn-primary">
                Лента друзей
            </a>
            <a href="{{ route('users.index') }}" class="btn btn-primary">
                Список пользователей
            </a>
        </div>
    @endauth
</div>
@endsection