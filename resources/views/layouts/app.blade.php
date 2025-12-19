<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Музыкальные альбомы Rammstein')</title>

    {{-- Иконки FontAwesome для анимации в Toast --}}
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    {{-- Скомпилированные стили из Laravel Mix --}}
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body class="d-flex flex-column min-vh-100">

    {{-- НАВБАР --}}
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid container-xxl">
            <a class="navbar-brand" href="{{ route('albums.index') }}">
                <img src="" alt="Logo" class="navbar-logo">
                Музыкальные альбомы группы Rammstein
            </a>
            <div class="ms-auto">
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-btn">
                            Выйти
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-btn" id="loginButton">
                        Войти
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- ОСНОВНОЙ КОНТЕНТ --}}
    <main class="main">
        @yield('content')
    </main>

    {{-- ФУТЕР --}}
    <footer class="footer py-4 mt-auto">
        <div class="container-fluid container-xxl d-flex justify-content-between align-items-center flex-wrap gap-3">
            <p class="mb-0 fw-bold author-text">Паршукова Елена</p>
            <div class="d-flex gap-3 socials">
                {{-- src оставляем пустыми, их заполнит JS (app.js) --}}
                <a href="https://ru.wikipedia.org/wiki/Rammstein">
                    <img src="" alt="Wiki" class="social-icon">
                </a>
                <a href="https://vk.com/chooocha">
                    <img src="" alt="VK" class="social-icon">
                </a>
                <a href="https://t.me/Chooocha">
                    <img src="" alt="Telegram" class="social-icon">
                </a>
            </div>
        </div>
    </footer>

    {{-- МОДАЛЬНОЕ ОКНО --}}
    <div class="modal fade" id="rammsteinModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rammsteinModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <img id="modalImage" src="" alt="" class="img-fluid mb-3 rounded">
                    <p id="modalText"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Закрыть
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- TOAST "Загрузка..." --}}
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1080;">
        <div id="loginToast" class="toast align-items-center text-bg-dark border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-spinner fa-spin me-2"></i>
                    Загрузка...
                </div>
                <button type="button"
                        class="btn-close btn-close-white me-2 m-auto"
                        data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

    {{-- Скомпилированный JS Laravel Mix (Bootstrap + код) --}}
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>