<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

/** 未認証の利用者向け画面で使用する共通レイアウトコンポーネント */
class GuestLayout extends Component
{
    /** ゲスト用レイアウトを描画 */
    public function render(): View
    {
        return view('layouts.guest');
    }
}
