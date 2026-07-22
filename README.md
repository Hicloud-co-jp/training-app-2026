# 社員管理アプリ 2026

PHP 8.3、Laravel 12、Bootstrap 5で作成した、新入社員研修用の社員管理アプリです。

既存アプリの調査と改修を通して、MVC、Eloquent、DB、外部API、ファイル出力、画像アップロードを段階的に学びます。新入社員は、PowerShellからDocker Composeを使用して研修環境を操作します。

## 研修目的

Webアプリケーションの構造とMVCの役割を理解し、Laravelアプリの基本的な機能追加・修正・動作確認・報告を経験することを目的とします。

習熟度には個人差があるため、スケジュールどおりにすべての課題を完了することだけを目的とはしません。講師の支援を受けながら開発の流れを経験し、理解を深めることを重視します。

## 教材と研修環境

- Dotinstall（Laravel 11.10.0、PHP 8.3）は、Laravelの基本概念と操作を学ぶ入門教材として使用します。
- 研修アプリと提出する成果物は、PHP 8.3、Laravel 12、Bootstrap 5に統一します。
- 教材と研修アプリで記述や動作が異なる場合は、この研修アプリの環境と手順を優先します。
- 教材のコードをそのまま転記するのではなく、研修アプリの構成を確認して実装します。

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

PHPUnitの自動テストは、講師が研修アプリの初期状態を確認するためのものです。受講者がテストプログラムを追加・修正することは研修課題に含めません。

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
