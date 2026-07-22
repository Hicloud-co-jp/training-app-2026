<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

/** ログイン、利用者登録、ログアウトを処理するコントローラー */
class AuthController extends Controller
{
    /** ログイン画面を表示 */
    public function showLogin(): View
    {
        return view('auth.login');
    }

    /** ログイン処理を実行 */
    public function login(Request $request): RedirectResponse
    {
        // 認証に必要な項目を検証
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // 入力された認証情報が一致しない場合はメール入力だけを保持
        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'メールアドレスまたはパスワードが正しくありません。'])->onlyInput('email');
        }

        // セッション固定攻撃を防ぐため、ログイン成功後にIDを再生成
        $request->session()->regenerate();

        return redirect()->intended(route('employees.index'));
    }

    /** 利用者登録画面を表示 */
    public function showRegister(): View
    {
        return view('auth.register');
    }

    /** 新しい利用者を登録してログイン */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        // 検証済みの値から利用者を作成
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('employees.index');
    }

    /** ログアウトしてセッションを破棄 */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
