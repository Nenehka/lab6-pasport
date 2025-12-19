@extends('layouts.app')

@section('title', 'API токен')

@section('content')
<div class="container-fluid container-xxl my-5">
    <h2 class="mb-4">API токен</h2>

    @if($newToken)
        <div class="alert alert-success">
            Новый токен сгенерирован. Скопируйте его сейчас — больше он показан не будет:
            <pre class="mt-2 mb-0" style="white-space: normal; word-break: break-all;">{{ $newToken }}</pre>
        </div>
    @endif

    @if($token && !$newToken)
        <p>У вас уже есть активный токен.</p>
        <p class="text-muted">
            Вы можете сгенерировать новый токен — старый будет отозван.
        </p>
    @elseif(!$token && !$newToken)
        <p>У вас ещё нет токена для доступа к API.</p>
    @endif

    <form method="POST" action="{{ route('profile.api-token.generate') }}">
        @csrf
        <button type="submit" class="btn btn-primary">
            Сгенерировать новый токен
        </button>
    </form>

    <hr class="my-4">

    <h5>Как использовать</h5>
    <p>
        В запросах к REST API используйте заголовок:
    </p>
    <pre>Authorization: Bearer ВАШ_ТОКЕН</pre>
</div>
@endsection