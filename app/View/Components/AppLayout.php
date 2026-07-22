<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

/** 認証後の画面で使用する共通レイアウトコンポーネント */
class AppLayout extends Component
{
    /** 共通レイアウトを描画 */
    public function render(): View
    {
        return view('layouts.app');
    }
}
