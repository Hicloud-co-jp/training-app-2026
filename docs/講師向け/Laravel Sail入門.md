# Laravel Sail入門

## この資料の目的

この資料では、Laravel Sailが何をしているかを、Dockerを使い始めたばかりの人にも分かる言葉で説明します。

実際の作業手順は別資料に分けています。

- 初回準備: [新入社員向け環境構築](../新入社員向け/環境構築.md)
- 毎日の操作: [新入社員向け起動・終了手順](../新入社員向け/起動・終了手順.md)

この資料は仕組みを理解するための読み物です。初めて起動する人は、先に「新入社員向け環境構築」を上から順に実施してください。

## 最初に押さえる3つのポイント

まず、次の3点を押さえます。

1. SailはLaravel専用サーバーではなく、Docker Composeを使いやすくする操作スクリプトである
2. PHPやMySQLはWindowsへ直接入れるのではなく、Dockerコンテナ内で動く
3. WindowsではSailコマンドをPowerShellではなく、WSL2のUbuntuターミナルで実行する

最初からDockerの全用語を暗記する必要はありません。次の流れを実際に操作し、「Ubuntuからコンテナを起動するとブラウザでLaravelが開く」ことを確認してから、各用語を読み進めます。

```bash
cd /mnt/c/mywork/training-app-2026
./vendor/bin/sail up -d
./vendor/bin/sail ps
```

ブラウザで [http://localhost:8080](http://localhost:8080) を開きます。終了時は次を実行します。

```bash
./vendor/bin/sail stop
```

## 1. Sailとは

Laravel Sailは、Laravel公式のDocker開発環境です。Sailそのものがサーバーなのではなく、Docker ComposeをLaravel開発者向けの短いコマンドで操作する仕組みです。

このアプリでは2つのコンテナを利用します。

```text
ブラウザ
  │
  ▼
laravel.test コンテナ
  ├─ PHP 8.3
  ├─ Composer
  ├─ Node.js / pnpm
  └─ Laravel 12
  │
  ▼
mysql コンテナ
  └─ MySQL 8.4
```

PHP、Composer、Node.js、MySQLのバージョンをDocker側でそろえるため、受講者ごとのPC環境差を小さくできます。

## 2. Dockerの基本用語

| 用語 | 意味 | このアプリでの例 |
| --- | --- | --- |
| image | コンテナを作るための設計済みのひな形 | `sail-8.3/app`、`mysql:8.4` |
| container | imageから起動した実行環境 | `laravel.test`、`mysql` |
| volume | コンテナを削除しても残せるデータ領域 | `sail-mysql` |
| network | コンテナ同士が通信するための内部ネットワーク | `sail` |
| port | PCからコンテナへ接続する入口 | PCの8080番からLaravelへ接続 |

imageは設計図、containerは設計図から作って実際に動いている環境、と考えると理解しやすくなります。

## 3. 関係するファイル

| ファイル | 役割 |
| --- | --- |
| `compose.yaml` | コンテナ、ポート、volume、networkを定義する |
| `docker/8.3/Dockerfile` | PHP 8.3、Composer、Node.js等を含むimageを作る |
| `docker/mysql/` | MySQLのテストDBを初期化する |
| `.env.sail.example` | Sail用の環境設定見本 |
| `vendor/bin/sail` | Docker ComposeをLaravel向けに操作するスクリプト |
| `scripts/sail-setup.sh` | 初回構築をまとめて実行する研修用スクリプト |

研修で設定を変更するときは、どのファイルが担当しているかを先に確認します。

## 4. Sailコマンドの正体

次の2つは、ほぼ同じ処理です。

```bash
./vendor/bin/sail artisan migrate
```

```bash
docker compose exec laravel.test php artisan migrate
```

Sailは、対象コンテナ名や実行方法を補い、Laravelで普段使うコマンドを短くしています。

| Sail | コンテナ内で行われること |
| --- | --- |
| `sail artisan test` | PHPで `artisan test` を実行する |
| `sail composer install` | ComposerでPHP依存関係を入れる |
| `sail pnpm run dev` | Node.jsでViteを起動する |
| `sail mysql` | MySQLクライアントを起動する |
| `sail shell` | アプリコンテナのシェルを開く |

普段の起動・停止コマンドは [新入社員向け起動・終了手順](../新入社員向け/起動・終了手順.md) にまとめています。

### Windowsではどのターミナルを使うか

Sailコマンドは、WSL2にインストールしたUbuntuから実行します。

```text
Windows PowerShell
  └─ Docker DesktopやWSLの状態確認に使う

Ubuntuターミナル（WSL2）
  └─ ./vendor/bin/sail ... を実行する
```

PowerShellで `./vendor/bin/sail stop` などを実行し、次のエラーが表示される場合があります。

```text
execvpe(/bin/bash) failed: No such file or directory
```

これはLaravelのエラーではありません。Sailが必要とするUbuntuの`/bin/bash`を実行できていない状態です。PowerShellで次を確認します。

```powershell
wsl --list --verbose
```

一覧にUbuntuがなければ、管理者PowerShellからインストールします。

```powershell
wsl --install -d Ubuntu
```

Ubuntuを使わずPowerShellからコンテナを操作するときは、SailではなくDocker Composeコマンドを使います。

```powershell
docker compose up -d
docker compose ps
docker compose stop
```

## 5. LaravelからMySQLへ接続できる理由

Sailの内部networkでは、サービス名が接続先の名前になります。

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
```

`DB_HOST=mysql` の `mysql` はPC名ではなく、`compose.yaml` に定義したMySQLサービス名です。LaravelコンテナからMySQLコンテナへ、Sailのnetwork内で接続します。

PCからMySQLへ接続する場合は、PC側へ公開した3307番ポートを使います。接続する場所によりポートが異なる点に注意します。

## 6. ソースコードとDBデータの保存場所

ソースコードはPCとLaravelコンテナで共有します。

```yaml
volumes:
  - '.:/var/www/html'
```

PCで編集したBladeやPHPがコンテナ内でも同じファイルとして見えるため、編集のたびにimageを作り直す必要はありません。

MySQLデータはDocker volumeへ保存します。

```yaml
volumes:
  sail-mysql:
```

この違いにより、コンテナを作り直してもソースコードと通常のDBデータは残ります。

## 7. `stop`、`down`、`down -v` の違い

| 操作 | コンテナ | MySQLデータ | 用途 |
| --- | --- | --- | --- |
| `sail stop` | 停止する | 残る | 毎日の終了 |
| `sail down` | 停止して削除する | 残る | コンテナを作り直したいとき |
| `sail down -v` | 停止して削除する | 削除する | DBを完全に初期化するとき |

`down -v` は研修データを消します。実行してよいか分からない場合は講師へ確認してください。

## 8. buildが必要な変更

通常のPHPやBladeの編集では、Docker imageの再buildは不要です。

次のような環境自体の変更では再buildします。

- Dockerfileを変更した
- PHP拡張を追加した
- PHPのバージョンを変更した
- OSパッケージを追加した

```bash
./vendor/bin/sail build --no-cache
./vendor/bin/sail up -d
```

Composerパッケージの追加だけなら、通常はimageの再buildではなく `sail composer` を使います。

## 9. 問題を切り分ける考え方

Sailで問題が起きた場合は、次の順で確認します。

1. Docker Desktopが動いているか
2. コンテナが起動しているか
3. LaravelとMySQLのどちらでエラーが出ているか
4. Laravelの設定・ログに原因があるか
5. ソースコードやDBの内容に原因があるか

代表的な確認コマンドです。

```bash
./vendor/bin/sail ps
docker compose logs --tail=50 laravel.test
docker compose logs --tail=50 mysql
./vendor/bin/sail artisan about
```

具体的な起動トラブルへの対応は [新入社員向け起動・終了手順](../新入社員向け/起動・終了手順.md) を参照します。

### トラブル切り分け表

問題が起きたときは、エラーメッセージだけでなく「どのターミナルで、どのフォルダから、何を実行したか」を確認します。

| 状況 | 最初に確認すること | 主な対応 |
| --- | --- | --- |
| `/bin/bash`がない | PowerShellでSailを実行していないか | Ubuntuから実行する |
| `command not found` | 現在のフォルダとコマンドの綴り | プロジェクト直下へ移動する |
| Dockerへ接続できない | Docker Desktopが起動済みか | Docker Desktopを起動する |
| 8080番を使用できない | 別アプリが8080番を使用していないか | 使用中プロセスまたはポート設定を確認する |
| Laravel画面が500になる | LaravelログとDBコンテナ | `storage/logs/laravel.log`とMySQLログを確認する |
| DBへ接続できない | `DB_HOST`とMySQLの状態 | Sailでは`DB_HOST=mysql`、コンテナがhealthyか確認する |
| 画面の見た目が反映されない | Viteのbuild状況 | `./vendor/bin/sail pnpm run build`を実行する |

問題が解決したら、実行したコマンドだけでなく、原因と確認方法も記録します。同じ問題が再発した際に、自分で切り分けられる状態が目標です。

## 10. 学習時の確認ポイント

次を自分の言葉で説明できれば、Sail入門の目標達成です。

- SailとDocker Composeの関係
- imageとcontainerの違い
- LaravelとMySQLが別コンテナである理由
- `DB_HOST=mysql` の `mysql` が何を表すか
- ソースコードとDBデータがどこへ保存されるか
- `sail artisan` がどのPHPを使うか
- `stop`、`down`、`down -v` の違い
- どの変更でimageの再buildが必要か

### 理解度チェック（応用）

基本を理解したら、さらに次を説明できることを目標にします。

- PowerShellとUbuntuのどちらで何を実行するか
- SailコマンドとDocker Composeコマンドの対応
- コンテナ、Laravel、MySQLのどこで問題が起きているかの切り分け方
- 研修用DBデータを消さずに停止・再起動する方法
- `down -v`を実行する前に確認が必要な理由

参考: [Laravel 12 Sail公式ドキュメント](https://laravel.com/docs/12.x/sail)
