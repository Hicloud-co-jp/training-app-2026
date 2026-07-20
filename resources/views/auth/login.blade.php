@extends('layouts.app')
@section('title', 'ログイン')
@section('content')
<div class="row justify-content-center"><div class="col-md-7 col-lg-5"><div class="card page-card"><div class="card-body p-4 p-lg-5">
<h1 class="h3 mb-4">ログイン</h1>
<form method="POST" action="{{ route('login') }}">@csrf
    <div class="mb-3"><label class="form-label" for="email">メールアドレス</label><input class="form-control @error('email') is-invalid @enderror" id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>@error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
    <div class="mb-3"><label class="form-label" for="password">パスワード</label><input class="form-control @error('password') is-invalid @enderror" id="password" name="password" type="password" required>@error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
    <div class="form-check mb-4"><input class="form-check-input" id="remember" name="remember" type="checkbox" value="1"><label class="form-check-label" for="remember">ログイン状態を保持する</label></div>
    <button class="btn btn-primary w-100">ログイン</button>
</form><p class="text-center mt-4 mb-0"><a href="{{ route('register') }}">利用者を登録する</a></p>
</div></div></div></div>
@endsection
