<!doctype html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', '社員管理') | {{ config('app.name') }}</title>
    @vite(['resources/js/app.js'])
</head>
<body>
<nav class="navbar navbar-dark app-navbar shadow-sm"><div class="container">
    <a class="navbar-brand fw-bold" href="{{ route('employees.index') }}">社員管理 2026</a>
    @auth
        <div class="d-flex align-items-center gap-3 text-white"><span class="small">{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn btn-sm btn-outline-light">ログアウト</button></form>
        </div>
    @endauth
</div></nav>
<main class="container py-4 py-lg-5">
    @if (session('success'))<div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button class="btn-close" data-bs-dismiss="alert" aria-label="閉じる"></button></div>@endif
    @yield('content')
</main>
</body>
</html>
