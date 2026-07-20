# 社員管理アプリ2026 環境構築

> PCごとの差を抑える推奨手順は [Laravel Sail入門](./Laravel%20Sail入門.md) です。この文書はPHP等をPCへ直接入れる場合の代替手順です。

## 1. 標準環境

| 項目 | 標準 |
| --- | --- |
| OS | Windows 11 |
| PHP | 8.3 |
| Composer | 2.x |
| Laravel | 12.x |
| Node.js | LTS版（Corepack・pnpmを使用） |
| 開発DB | SQLite |
| UI | Bootstrap 5 |

SQLiteを標準にすることで、MySQLの準備前でも研修を開始できます。

## 2. 必要ソフトウェア

Git for Windows、PHP 8.3、Composer 2.x、Node.js LTSをインストールし、PowerShellで確認します。

これらのソフトウェア本体とPHP拡張は、既存のPC環境や管理者権限に影響するため、一括バッチではインストールしません。一括バッチは不足を検出すると、対象を表示して安全に停止します。

```powershell
git --version
php --version
composer --version
node --version
corepack --version
```

使用中の `php.ini` は `php --ini` で確認できます。少なくとも次の拡張を有効にします。

```ini
extension=curl
extension=fileinfo
extension=mbstring
extension=openssl
extension=pdo_sqlite
extension=sqlite3
```

MySQLを使う場合は `extension=pdo_mysql` も有効にします。

## 3. リポジトリ取得

```powershell
cd C:\mywork
git clone <研修用リポジトリURL> training-app-2026
cd training-app-2026
```

## 4. 一括バッチでアプリをセットアップする

必要ソフトウェアの準備後、プロジェクト直下のPowerShellで次を実行します。

```powershell
.\scripts\pc-direct-setup.bat
```

バッチは次の処理を順番に実行します。

1. Git、PHP、Composer、Node.js、Corepackの存在確認
2. 必要なPHP拡張の確認
3. `composer install`
4. `.env`の作成とアプリケーションキー生成
5. SQLiteファイルの作成
6. migrationとSeederの実行
7. pnpmパッケージのインストール
8. フロントエンドのbuild

途中で失敗した場合はその場で停止します。原因を直した後、同じバッチをもう一度実行できます。既存の`.env`と設定済みの`APP_KEY`は上書きしません。既存の`.env`がSail用またはMySQL用で、`DB_CONNECTION=sqlite`ではない場合も、誤操作防止のため処理を停止します。

## 5. 手動でアプリをセットアップする

一括バッチを使わない場合や、各処理を学習しながら進める場合は、以下を順番に実行します。

PHP依存関係をlockファイルから再現します。

```powershell
composer install
```

環境ファイルを作り、アプリケーションキーを生成します。

```powershell
Copy-Item .env.example .env
php artisan key:generate
```

`.env` は秘密情報を含むためGitへ追加しません。

SQLiteファイル、テーブル、研修用データを作成します。

```powershell
New-Item database/database.sqlite -ItemType File -Force
php artisan migrate --seed
```

フロントエンドを構築します。

```powershell
corepack enable
pnpm install --frozen-lockfile
pnpm run build
```

## 6. 起動とログイン

```powershell
php artisan serve
```

`http://127.0.0.1:8000` を開きます。

- メールアドレス: `test@example.com`
- パスワード: `password`

これはローカル研修専用の初期利用者です。本番環境では使用しません。

画面を変更しながら開発するときは、別のPowerShellで `pnpm run dev` を実行します。

## 7. 動作確認

1. 初期利用者でログインする
2. 社員一覧が表示される
3. 社員を登録する
4. 社員番号、氏名または部署で検索する
5. 登録した社員を編集する
6. 登録した社員を削除する

## 8. テスト

テストはSQLiteのインメモリDBを使うため、開発用データを変更しません。

```powershell
php artisan test
php artisan route:list
```

## 9. API確認

認証不要の疎通確認です。

```powershell
Invoke-RestMethod http://127.0.0.1:8000/api/ping
```

社員APIはSanctum tokenが必要です。ローカル研修用tokenを発行します。

```powershell
php artisan tinker
```

```php
$user = App\Models\User::where('email', 'test@example.com')->firstOrFail();
$token = $user->createToken('local-training')->plainTextToken;
$token;
```

表示されたtokenを一時的に使います。Git、Issue、チャットへ貼らないでください。

```powershell
$trainingToken = '<発行したtoken>'
Invoke-RestMethod -Headers @{ Authorization = "Bearer $trainingToken" } http://127.0.0.1:8000/api/v1/employees
```

不要になったtokenはTinkerで削除します。

```php
$user->tokens()->delete();
```

## 10. MySQLへ切り替える場合

MySQL 8系に空のDBと専用利用者を作り、`.env` を変更します。rootや共通固定パスワードをアプリから使わないでください。

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=training_app_2026
DB_USERNAME=training_app
DB_PASSWORD=<各自で設定したパスワード>
```

```powershell
php artisan config:clear
php artisan migrate --seed
```

## 11. よくある問題

### `could not find driver`

`php --ini` で使用中の設定を確認し、`pdo_sqlite` と `sqlite3` を有効にします。

### `Vite manifest not found`

```powershell
pnpm install --frozen-lockfile
pnpm run build
```

### `No application encryption key`

```powershell
Copy-Item .env.example .env
php artisan key:generate
```

### DBを作り直したい

ローカル研修データをすべて消してよい場合だけ実行します。

```powershell
php artisan migrate:fresh --seed
```

この操作は全テーブルを削除します。本番環境や共有DBでは実行しません。

## 12. セキュリティ上の注意

- `.env`、token、パスワードをcommitしない
- `phpinfo()` を公開ディレクトリへ置かない
- Issue、チャット、画面共有へ秘密情報を出さない
- 本番では `APP_DEBUG=false` にする
- 初期利用者を本番へ持ち込まない
- 環境構築では `composer update` ではなく `composer install` を使う
