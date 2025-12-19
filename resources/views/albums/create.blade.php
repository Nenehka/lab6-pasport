@extends('layouts.app')

@section('title', 'Добавить альбом')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Добавить альбом</h1>

    <form method="POST"
          action="{{ route('albums.store') }}"
          enctype="multipart/form-data">
        @include('albums._form', ['submitText' => 'Создать альбом'])
    </form>
</div>
@endsection