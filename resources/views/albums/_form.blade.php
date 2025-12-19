@csrf

<div class="mb-3">
    <label for="title" class="form-label">Название альбома</label>
    <input
        type="text"
        id="title"
        name="title"
        class="form-control @error('title') is-invalid @enderror"
        value="{{ old('title', $album->title ?? '') }}"
        required
        minlength="2"
        maxlength="255"
    >
    @error('title')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="description" class="form-label">Описание</label>
    <textarea
        id="description"
        name="description"
        class="form-control @error('description') is-invalid @enderror"
        rows="4"
        required
    >{{ old('description', $album->description ?? '') }}</textarea>
    @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="release_date" class="form-label">Дата выхода</label>
    <input
        type="text"
        id="release_date"
        name="release_date"
        class="form-control @error('release_date') is-invalid @enderror"
        value="{{ old('release_date', $album->release_date ?? '') }}"
        placeholder="дд.мм.гггг"
        pattern="\d{2}\.\d{2}\.\d{4}"
        required
    >
    <div class="form-text">Формат: 22.08.1997</div>
    @error('release_date')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="image" class="form-label">Обложка альбома</label>
    <input
        type="file"
        id="image"
        name="image"
        class="form-control @error('image') is-invalid @enderror"
        accept="image/*"
        @if(empty($album->image_path)) required @endif
    >
    @error('image')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror

    @if(!empty($album->image_path))
        <div class="mt-2">
            <p class="mb-1">Текущая картинка:</p>
            <img src="{{ asset('storage/'.$album->image_path) }}"
                 alt="{{ $album->title }}"
                 style="max-height: 150px;">
        </div>
    @endif
</div>

<button type="submit" class="btn btn-primary">
    {{ $submitText ?? 'Сохранить' }}
</button>