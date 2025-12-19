@extends('layouts.app')

@section('title', 'Редактировать альбом')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Редактировать альбом</h1>

    <form method="POST"
          action="{{ route('albums.update', $album) }}"
          enctype="multipart/form-data">
        @method('PUT')
        @include('albums._form', ['submitText' => 'Сохранить изменения'])
    </form>
</div>
@endsection