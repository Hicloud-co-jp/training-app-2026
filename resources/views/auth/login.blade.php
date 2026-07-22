<x-guest-layout>
    <x-slot name="title">ログイン</x-slot>

    <h1 class="h3 mb-4">ログイン</h1>

    <form method="POST" action="{{ route('login') }}">
        @csrf

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
                autofocus
                autocomplete="username"
            >
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- パスワード --}}
        <div class="mb-3">
            <label class="form-label" for="password">パスワード</label>
            <input
                class="form-control @error('password') is-invalid @enderror"
                id="password"
                name="password"
                type="password"
                required
                autocomplete="current-password"
            >
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- ログイン状態の保持 --}}
        <div class="form-check mb-4">
            <input class="form-check-input" id="remember" name="remember" type="checkbox" value="1">
            <label class="form-check-label" for="remember">ログイン状態を保持する</label>
        </div>

        <button class="btn btn-primary w-100">ログイン</button>
    </form>

    <p class="text-center mt-4 mb-0">
        <a href="{{ route('register') }}">利用者を登録する</a>
    </p>
</x-guest-layout>
