<x-guest-layout>
    <x-slot name="title">利用者登録</x-slot>

    <h1 class="h3 mb-4">利用者登録</h1>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- 氏名 --}}
        <div class="mb-3">
            <label class="form-label" for="name">氏名</label>
            <input
                class="form-control @error('name') is-invalid @enderror"
                id="name"
                name="name"
                value="{{ old('name') }}"
                required
                autofocus
                autocomplete="name"
            >
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- メールアドレス --}}
        <div class="mb-3">
            <label class="form-label" for="email">メールアドレス</label>
            <input
                class="form-control @error('email') is-invalid @enderror"
                id="email"
                name="email"
                type="email"
                value="{{ old('email') }}"
                required
                autocomplete="username"
            >
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- パスワード --}}
        <div class="mb-3">
            <label class="form-label" for="password">パスワード（8文字以上）</label>
            <input
                class="form-control @error('password') is-invalid @enderror"
                id="password"
                name="password"
                type="password"
                required
                autocomplete="new-password"
            >
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- 確認用パスワード --}}
        <div class="mb-4">
            <label class="form-label" for="password_confirmation">パスワード確認</label>
            <input
                class="form-control"
                id="password_confirmation"
                name="password_confirmation"
                type="password"
                required
                autocomplete="new-password"
            >
        </div>

        <button class="btn btn-primary w-100">登録する</button>
    </form>

    <p class="text-center mt-4 mb-0">
        <a href="{{ route('login') }}">ログインへ戻る</a>
    </p>
</x-guest-layout>
