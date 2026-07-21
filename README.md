# 社員管理アプリ 2026

PHP 8.3、Laravel 12、Bootstrap 5で作成した、新入社員研修用の社員管理アプリです。

既存アプリの調査と改修を通して、MVC、Eloquent、DB、外部API、ファイル出力、画像アップロードを段階的に学びます。新入社員はPowerShellからDocker Composeを使い、講師はLaravel Sailを体系的に学びます。

## 最初に読む資料

### 新入社員

1. [環境構築](docs/新入社員向け/環境構築.md)
2. [起動・終了手順](docs/新入社員向け/起動・終了手順.md)
3. [初級課題](docs/新入社員向け/課題/初級課題.md)
4. [中級課題](docs/新入社員向け/課題/中級課題.md)
5. [上級課題](docs/新入社員向け/課題/上級課題.md)

### 講師

1. [技術スタック・設計・フォルダ構成](docs/講師向け/技術スタック・設計・フォルダ構成.md)
2. [初級・中級・上級課題対応表](docs/講師向け/初級・中級・上級課題対応表.md)
3. [Laravel Sail入門](docs/講師向け/Laravel%20Sail入門.md)
4. [全資料一覧](docs/README.md)

## 現在のアプリ機能

- 利用者登録、ログイン、ログアウト
- 社員一覧、登録、編集、論理削除
- 社員番号、氏名、部署による検索
- 部署による絞り込み
- ページネーション
- Form Requestによる最低限の入力検証
- Sanctum認証付き読み取りAPI
- Factory・Seederによる研修データ作成
- PHPUnitによる認証、Web CRUD、APIテスト

## 研修課題

| レベル | 主な改修 | 学習内容 |
| --- | --- | --- |
| 初級 | 入社年数、検索条件保持、ソート、検証強化 | 既存MVCの調査と一覧改修 |
| 中級 | 郵便番号APIによる住所検索画面 | 新規画面、MVC、外部API |
| 上級A | 部署マスター化 | DB正規化、migration、リレーション |
| 上級B | 社員CSV出力 | WHERE、文字コード、Flysystem |
| 上級C | プロフィール画像 | upload、Storage、Bootstrapモーダル |

このリポジトリのmainは研修開始用です。上記の初級・中級・上級課題は未実装です。受講者はmainから課題用branchを作成して改修します。

## 技術スタック

| 分類 | 技術 |
| --- | --- |
| Backend | PHP 8.3、Laravel 12 |
| Frontend | Blade、Bootstrap 5、Vite |
| Database | MySQL 8.4 |
| Web認証 | Laravel Session認証 |
| API認証 | Laravel Sanctum |
| 開発環境 | Laravel Sail、Docker Compose |
| テスト | PHPUnit |
| パッケージ管理 | Composer、pnpm |

固定された詳細バージョンは `composer.lock` と `pnpm-lock.yaml` を確認してください。

## 新入社員向け初回構築

WindowsではDocker DesktopとPowerShellを使用します。Ubuntuの追加インストールは不要です。詳しい操作は [新入社員向け環境構築](docs/新入社員向け/環境構築.md) を参照してください。

PowerShellで実行します。

```powershell
git clone <研修用リポジトリURL> C:\mywork\training-app-2026
cd C:\mywork\training-app-2026
.\scripts\docker-setup.bat
```

初回処理にはDocker imageの取得、Composer、migration、Seeder、画面buildが含まれます。

完了後、[http://localhost:8080](http://localhost:8080) を開きます。

```text
メールアドレス: test@example.com
パスワード: password
```

この利用者はローカル研修専用です。本番環境では使用しません。

## 毎日の起動と終了

PowerShellでプロジェクトへ移動します。

```powershell
cd C:\mywork\training-app-2026
```

起動します。

```bash
docker compose up -d
docker compose ps
```

終了します。

```bash
docker compose stop
```

詳細は [起動・終了手順](docs/新入社員向け/起動・終了手順.md) を参照してください。

## テスト

```powershell
docker compose exec laravel.test php artisan test
```

現在の基準では13テスト、44アサーションが成功します。テスト追加により件数は増える場合があります。

コード形式も確認できます。

```powershell
docker compose exec laravel.test ./vendor/bin/pint --test
```

## API

認証不要の疎通確認です。

```text
GET /api/ping
```

社員APIはSanctum tokenが必要です。

```text
GET /api/v1/employees
GET /api/v1/employees/{employee}
```

API tokenをGit、Issue、チャットへ貼らないでください。

## 主なフォルダ

```text
app/                 LaravelのModel、Controller、Request、Resource
database/            migration、Factory、Seeder
docker/              SailのPHP 8.3・MySQL設定
docs/新入社員向け/    環境構築、起動手順、研修課題
docs/講師向け/        技術設計、課題対応表、講師用資料
resources/           Blade、CSS、JavaScript
routes/              Web・APIルート
scripts/             Docker・Sail・PC直接環境の初回セットアップ
tests/               Unit・Feature Test
```

詳しい階層と各ファイルの役割は [技術スタック・設計・フォルダ構成](docs/講師向け/技術スタック・設計・フォルダ構成.md) に記載しています。

## セキュリティ上の注意

- `.env`、API token、パスワードをGitへ登録しない
- 初期利用者を本番環境へ持ち込まない
- `phpinfo()`などの診断ページをpublicへ置かない
- 個人情報をIssue、ログ、チャットへ貼らない
- `sail down -v` はDBデータを削除するため、講師へ確認してから実行する
