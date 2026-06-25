# **環境構築**

## **GitHub**

### **アカウント登録**
- ハイクラウドメールアドレスで[GitHub](https://github.com)にアカウント登録
- 西川にUsernameを伝える
- 招待を受けたらログインする

### **Personal Access Token (PAT) 発行**
1. GitHubにログイン後、右上のプロフィール画像をクリック
2. **Settings** → **Developer settings** → **Personal access tokens** → **Tokens (classic)**
3. **Generate new token (classic)** をクリック
4. 以下の設定を行う：
   - **Note**: `training-app-access` (任意の名前)
   - **Expiration**: `No expiration` (有効期限なし)
   - **Select scopes**: `repo` (フルアクセス) にチェック
5. **Generate token** をクリック
6. **⚠️ 重要**: 表示されたトークンを安全な場所にコピー・保存（再表示不可）

### **Git for Windowsインストール**
1. [Git for Windows](https://git-scm.com/download/win)をダウンロード・インストール
2. インストール時はデフォルト設定で問題ありません

### **TortoiseGitインストール**
1. [TortoiseGit](https://tortoisegit.org/download/)をダウンロード・インストール
2. インストール時はデフォルト設定で問題ありません
3. Language PacksのJapaneseもインストール
4. Settingsから日本語を選択


## **リポジトリクローン**

### **作業ディレクトリへ移動**
```bash
# Windowsの自分のユーザディレクトリに移動
cd ~
```

### **リポジトリをクローン**
```bash
git clone https://github.com/Hicloud-co-jp/training-app.git
```

認証情報の入力を求められた場合：
- **Username**: GitHubのアカウント名
- **Password**: 上記で生成したPersonal Access Token


## **インストール**

### **PHP 8.3インストール**
1. [PHPのサイト](https://windows.php.net/download#php-8.3)からWindows用のzipをダウンロード
   1. PHP 8.3 → VS16 x64 Thread Safe → Zip
   2. 8.3系の最新バージョン
2. ユーザのホームディレクトリ配下に配置ダウンロードしたPHPファイル一式を配置
3. 環境変数の設定
   1. 変数：Path
   2. 値：C:\Users\{user}\php-8.3.xx
4. `ini\php.ini` を C:\Users\{user}\php-8.3.xx の `php.ini` に上書き

### **Composerインストール**
1. [Composer](https://getcomposer.org/download/)をダウンロード・インストール
2. コマンドプロンプトで`composer --version`を実行して確認

### **MySQL 8.0インストール**
- **参考ページ**: https://qiita.com/emily-08/items/882f9532989948f1d1d8
- **データベース認証情報**: 
  - ユーザー名: `root`
  - パスワード: `admin`


## **実行環境セットアップ**

### **プロジェクトフォルダに移動**
```bash
cd training-app
```

### **Composer依存関係のインストール**
```bash
composer install
```

### **アプリケーションキーの生成**
```bash
php artisan key:generate
```

### **データベース作成**
1. **MySQL 8.0 Command Line Client**を起動
2. 認証情報で接続：
   - Username: `root`
   - Password: `admin`
3. データベースを作成：
```sql
CREATE DATABASE training_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### **環境設定ファイルの確認**
`.env`ファイルのデータベース設定が以下になっていることを確認：
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=training_app
DB_USERNAME=root
DB_PASSWORD=admin
```

### **マイグレーション・シーダー実行**
```bash
php artisan migrate --seed
```

### **ストレージシンボリックリンク作成**
画像アップロード機能を使用するため、ストレージディレクトリへのシンボリックリンクを作成：
```bash
php artisan storage:link
```

このコマンドにより、`storage/app/public` が `public/storage` にリンクされ、アップロードした画像にブラウザからアクセスできるようになります。

### **開発サーバー起動**
```bash
php artisan serve
```

ブラウザで [http://localhost:8000](http://localhost:8000) にアクセスして動作確認
- メールアドレス: test@example.com
- パスワード: password


## **VSCodeデバッグ環境構築**

### **VSCode拡張機能のインストール**
1. VSCodeを起動
2. 拡張機能タブ（Ctrl+Shift+X）を開く
3. 以下の拡張機能をインストール：
   - **PHP Debug** (xdebug.php-debug)
   - **PHP Intelephense** (bmewburn.vscode-intelephense-client) ※推奨

### **Xdebugの設定**

#### **1. Xdebug拡張モジュールのダウンロード**
1. [Xdebug公式サイト](https://xdebug.org/download)にアクセス
2. **PHP 8.3 VC15 TS (64 bit)** 用のDLLファイルをダウンロード
   - ファイル名例：`php_xdebug-3.3.x-8.3-vs16-ts-x86_64.dll`
3. ダウンロードしたファイルを `C:\Users\{user}\php-8.3.xx\ext\` フォルダに配置
4. ファイル名を `php_xdebug.dll` にリネーム

#### **2. php.iniの設定**
PHPの設定ファイル（`C:\Users\{user}\php-8.3.xx\php.ini`）に以下を追加：

```ini
[XDebug]
zend_extension=php_xdebug.dll
xdebug.mode=debug
xdebug.start_with_request=yes
xdebug.client_host=localhost
xdebug.client_port=9003
xdebug.log_level=0
```

#### **3. 設定確認**
コマンドプロンプトでXdebugが有効になっているか確認：
```bash
php -m | findstr xdebug
```

正常に設定されていれば `xdebug` と表示されます。

#### **4. トラブルシューティング**
**エラーが出る場合の確認点：**
- ダウンロードしたXdebugのバージョンがPHP 8.3対応か確認
- Thread Safe (TS) 版をダウンロードしているか確認
- DLLファイルが正しいパス（`ext`フォルダ）に配置されているか確認
- `php.ini` のパス設定が正しいか確認（`php --ini` で確認可能）

### **VSCodeのデバッグ設定**
1. VSCodeでプロジェクトフォルダを開く
2. `.vscode/launch.json` ファイルを作成（存在しない場合）
3. 以下の設定を記述：
```json
{
    "version": "0.2.0",
    "configurations": [
        {
            "name": "Listen for Xdebug",
            "type": "php",
            "request": "launch",
            "port": 9003
        },
        {
            "name": "Launch currently open script",
            "type": "php",
            "request": "launch",
            "program": "${file}",
            "cwd": "${fileDirname}",
            "port": 0,
            "runtimeArgs": [
                "-dxdebug.start_with_request=yes"
            ],
            "env": {
                "XDEBUG_MODE": "debug,develop",
                "XDEBUG_CONFIG": "client_port=${port}"
            }
        }
    ]
}
```

**重要**: Windows環境では `pathMappings` は不要です。Linux/Docker環境とは異なり、VSCodeとPHPが同じファイルシステム上で動作するため、パスマッピングを設定するとブレークポイントが正常に動作しない場合があります。

### **デバッグの実行方法**
1. **開発サーバーを起動**：
```bash
php artisan serve
```

2. **VSCodeでデバッグを開始**：
   - F5キーを押すか、デバッグタブ（Ctrl+Shift+D）から「Listen for Xdebug」を選択して実行

3. **ブレークポイントの設定**：
   - デバッグしたいPHPファイルの行番号左側をクリックしてブレークポイントを設定

4. **デバッグ実行**：
   - ブラウザで [http://localhost:8000](http://localhost:8000) にアクセス
   - ブレークポイントで処理が停止し、変数の値やステップ実行が可能

### **トラブルシューティング**
- **Xdebugが動作しない場合**：
  - `php -v` でXdebugが読み込まれているか確認
  - ファイアウォールでポート9003が開放されているか確認
  
- **ブレークポイントで停止しない場合**：
  - `php.ini` の設定を再確認
  - PHPサーバーを再起動（Ctrl+Cで停止後、再度 `php artisan serve`）

### **おすすめの設定**
VSCodeの設定（`settings.json`）に以下を追加すると開発効率が向上します：
```json
{
    "php.validate.executablePath": "C:\\Users\\{user}\\php-8.3.xx\\php.exe",
    "php.debug.executablePath": "C:\\Users\\{user}\\php-8.3.xx\\php.exe"
}
```
※ `{user}` は実際のユーザー名に置き換えてください