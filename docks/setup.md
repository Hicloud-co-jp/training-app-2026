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

