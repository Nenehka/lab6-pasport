@extends('layouts.app')

@section('title', 'Пользователи')

@section('content')
<div class="container-fluid container-xxl my-5">
    <h2 class="mb-4">Пользователи</h2>

    @if($users->isEmpty())
        <p>Пока нет ни одного пользователя.</p>
    @else
        <ul class="list-group">
            @foreach($users as $user)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        {{ $user->name }}
                        @if($user->is_admin)
                            <span class="badge bg-primary ms-2">Администратор</span>
                        @endif
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('users.albums', $user) }}"
                           class="btn btn-sm btn-outline-secondary">
                            Альбомы
                        </a>

                        @if($currentUser->id !== $user->id)
                            @php
                                $isFriend = $currentUser->friends->contains($user->id);
                            @endphp

                            @if($isFriend)
                                <form method="POST"
                                      action="{{ route('friends.destroy', $user) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">
                                        Удалить из друзей
                                    </button>
                                </form>
                            @else
                                <form method="POST"
                                      action="{{ route('friends.store', $user) }}">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-success">
                                        Добавить в друзья
                                    </button>
                                </form>
                            @endif
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection