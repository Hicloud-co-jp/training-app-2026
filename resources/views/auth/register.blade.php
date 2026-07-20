@extends('layouts.app')
@section('title', '利用者登録')
@section('content')
<div class="row justify-content-center"><div class="col-md-7 col-lg-5"><div class="card page-card"><div class="card-body p-4 p-lg-5">
<h1 class="h3 mb-4">利用者登録</h1>
<form method="POST" action="{{ route('register') }}">@csrf
    <div class="mb-3"><label class="form-label" for="name">氏名</label><input class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>@error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
    <div class="mb-3"><label class="form-label" for="email">メールアドレス</label><input class="form-control @error('email') is-invalid @enderror" id="email" name="email" type="email" value="{{ old('email') }}" required>@error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
    <div class="mb-3"><label class="form-label" for="password">パスワード（8文字以上）</label><input class="form-control @error('password') is-invalid @enderror" id="password" name="password" type="password" required>@error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
    <div class="mb-4"><label class="form-label" for="password_confirmation">パスワード確認</label><input class="form-control" id="password_confirmation" name="password_confirmation" type="password" required></div>
    <button class="btn btn-primary w-100">登録する</button>
</form><p class="text-center mt-4 mb-0"><a href="{{ route('login') }}">ログインへ戻る</a></p>
</div></div></div></div>
@endsection
