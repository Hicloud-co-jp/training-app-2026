@echo off
setlocal
chcp 65001 >nul
cd /d "%~dp0.."

where docker >nul 2>&1
if errorlevel 1 (
    echo [エラー] Dockerが見つかりません。Docker Desktopを起動してください。
    exit /b 1
)

docker info >nul 2>&1
if errorlevel 1 (
    echo [エラー] Docker Desktopが起動していません。
    exit /b 1
)

if not exist ".env" copy /Y ".env.sail.example" ".env" >nul

echo [1/6] Docker imageを準備しています...
docker compose build
if errorlevel 1 goto :failed

echo [2/6] PHPパッケージを準備しています...
docker compose run --rm laravel.test composer install --no-interaction
if errorlevel 1 goto :failed

echo [3/6] Laravelのキーを準備しています...
findstr /X /C:"APP_KEY=" ".env" >nul
if not errorlevel 1 docker compose run --rm laravel.test php artisan key:generate --force
if errorlevel 1 goto :failed

echo [4/6] アプリとDBを起動しています...
docker compose up -d
if errorlevel 1 goto :failed
docker compose exec -T laravel.test php artisan migrate --seed --force
if errorlevel 1 goto :failed

echo [5/6] 画面パッケージを準備しています...
docker compose exec -T -e CI=true laravel.test pnpm install --frozen-lockfile
if errorlevel 1 goto :failed

echo [6/6] 画面をビルドしています...
docker compose exec -T laravel.test pnpm run build
if errorlevel 1 goto :failed

echo.
echo セットアップが完了しました: http://localhost:8080
exit /b 0

:failed
echo.
echo [エラー] セットアップを中断しました。直前のメッセージを確認してください。
exit /b 1
