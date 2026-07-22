<!doctype html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? '認証' }} | {{ config('app.name') }}</title>
    @vite(['resources/js/app.js'])
</head>
<body>
    {{-- ログイン・利用者登録画面の共通ヘッダー --}}
    <nav class="navbar navbar-dark app-navbar shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">社員管理2026</a>
        </div>
    </nav>

    {{-- 各認証画面の内容 --}}
    <main class="container py-4 py-lg-5">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-5">
                <div class="card page-card">
                    <div class="card-body p-4 p-lg-5">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
