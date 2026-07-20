@echo off
setlocal
chcp 65001 >nul

cd /d "%~dp0.."

echo [1/8] 必要なコマンドを確認しています...
where git >nul 2>&1
if errorlevel 1 goto :missing_git
where php >nul 2>&1
if errorlevel 1 goto :missing_php
where composer >nul 2>&1
if errorlevel 1 goto :missing_composer
where node >nul 2>&1
if errorlevel 1 goto :missing_node
where corepack >nul 2>&1
if errorlevel 1 goto :missing_corepack

echo [2/8] PHP拡張を確認しています...
php -r "foreach (['curl','fileinfo','mbstring','openssl','pdo_sqlite','sqlite3'] as $extension) { if (!extension_loaded($extension)) { fwrite(STDERR, $extension.PHP_EOL); exit(1); } }"
if errorlevel 1 goto :missing_extension

echo [3/8] PHPパッケージをインストールしています...
call composer install --no-interaction
if errorlevel 1 goto :failed

echo [4/8] 環境ファイルを準備しています...
if not exist ".env" copy /Y ".env.example" ".env" >nul
findstr /X /C:"DB_CONNECTION=sqlite" ".env" >nul
if errorlevel 1 goto :not_sqlite
findstr /X /C:"APP_KEY=" ".env" >nul
if not errorlevel 1 (
    php artisan key:generate --force
    if errorlevel 1 goto :failed
)

echo [5/8] SQLiteファイルを準備しています...
if not exist "database\database.sqlite" type nul > "database\database.sqlite"

echo [6/8] DBと研修データを準備しています...
php artisan migrate --seed --force
if errorlevel 1 goto :failed

echo [7/8] フロントエンドパッケージをインストールしています...
call corepack pnpm install --frozen-lockfile
if errorlevel 1 goto :failed

echo [8/8] 画面ファイルをビルドしています...
call corepack pnpm run build
if errorlevel 1 goto :failed

echo.
echo セットアップが完了しました。
echo 起動: php artisan serve
echo URL : http://127.0.0.1:8000
exit /b 0

:missing_git
echo [エラー] Gitが見つかりません。Git for Windowsをインストールしてください。
exit /b 1

:missing_php
echo [エラー] PHPが見つかりません。PHP 8.3をインストールしてPATHを設定してください。
exit /b 1

:missing_composer
echo [エラー] Composerが見つかりません。Composer 2.xをインストールしてください。
exit /b 1

:missing_node
echo [エラー] Node.jsが見つかりません。Node.js LTSをインストールしてください。
exit /b 1

:missing_corepack
echo [エラー] Corepackが見つかりません。Node.jsのインストール内容を確認してください。
exit /b 1

:missing_extension
echo [エラー] 上に表示されたPHP拡張が無効です。
echo php --ini で設定ファイルを確認し、拡張を有効にしてください。
exit /b 1

:not_sqlite
echo [エラー] .envのDB_CONNECTIONがsqliteではありません。
echo Sail用やMySQL用の.envを誤って変更しないため、処理を停止しました。
exit /b 1

:failed
echo.
echo [エラー] セットアップを中断しました。直前のメッセージを確認してください。
exit /b 1
