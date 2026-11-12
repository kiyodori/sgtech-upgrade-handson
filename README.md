# 備品貸出管理（SGTech 勉強会 #1 ハンズオン）

古いフレームワークで動くアプリを、新しいバージョンに上げてみるハンズオンの題材です。

社内のノート PC やプロジェクター、カメラなどの備品を、社員が借りて返すための小さな業務アプリです。CakePHP 3.10 と PHP 7.4 で動いています。

2026年10月、PHP に、認証なしで外部から任意のプログラムを実行される脆弱性（CVE-2026-109618、CVE-2026-109619）が公表されました。PHP 7.4 には修正版が出ていません。対応するには、修正版の出ている PHP 8.3.36 以上に上げる必要があります。

- [PHP 8.3.36 Release Announcement](https://www.php.net/releases/8_3_36.php)
- [PHP 8 ChangeLog（8.3.36）](https://www.php.net/ChangeLog-8.php#8.3.36)

ハンズオンでは、このアプリを PHP 8.3.36 で動くところまで上げてみます。

## 始め方

1. このリポジトリの「Code」ボタンから「Codespaces」タブを開き、「Create codespace on main」を押します。
2. 起動すると、依存パッケージのインストールとデータベースの作成が自動で始まります。終わるまで数分待ちます。
3. ターミナルで次のコマンドを実行します。

   ```bash
   bin/serve
   ```

4. 右下に出る通知から、ポート 8765 をブラウザで開きます。
5. 次のアカウントでログインします。

   - メールアドレス：taro@example.com
   - パスワード：password

## アプリの画面

| 画面 | URL | 内容 |
|---|---|---|
| ログイン | `/users/login` | ログインしていないと、ほかの画面には入れません |
| ダッシュボード | `/` | 貸出中と延滞中の件数、延滞中の貸出の一覧 |
| 備品一覧 | `/items` | 状態で絞り込めます。Excel に出力できます |
| 備品詳細 | `/items/view/{id}` | 備品の情報と貸出履歴。貸出と返却はここから |
| 備品の登録・編集 | `/items/add`、`/items/edit/{id}` | 管理番号、名前、分類、備考 |
| 貸出登録 | `/loans/add/{備品のid}` | 返却予定日と付属品を入れて貸し出します |
| 月次レポート | `/reports/monthly` | その月の貸出件数、延滞件数、よく借りられた備品 |

## 便利なコマンド

| コマンド | 内容 |
|---|---|
| `bin/serve` | アプリを起動します（止めるときは Ctrl+C） |
| `bin/setup` | 依存パッケージを入れ、データベースを初期データで作り直します |
| `php-switch 7.4` / `php-switch 8.3` | 使う PHP のバージョンを切り替えます |

## ブランチ

| ブランチ | 内容 |
|---|---|
| `main` | 出発点。CakePHP 3.10 + PHP 7.4 で動きます |
| `step2-cakephp4` | ステップ2の開始地点。CakePHP 4 の土台に入れ替えてあります |
| `step3-php8` | ステップ3の開始地点。CakePHP 4 + PHP 7.4 で動きます |
| `answer` | 解答例。CakePHP 4 + PHP 8.3 で動きます |

ブランチを切り替えたら、`bin/setup` を実行してから `bin/serve` で起動し直してください。自分の変更を捨てて切り替えるときは `git switch -f <ブランチ名>` を使います。

## ハンズオンの流れ

### ステップ1：PHP だけを上げてみる

```bash
php-switch 8.3
```

```bash
composer install
```

何が起きるかを確かめたら、PHP を 7.4 に戻します。

```bash
php-switch 7.4
```

### ステップ2：CakePHP 4 に上げる

```bash
git switch step2-cakephp4
```

```bash
bin/setup
```

```bash
bin/serve
```

画面を開くとエラーになります。エラーを読みながら、すべての画面が動くまで直します。

- ログイン、ダッシュボード、備品一覧、備品詳細、備品の登録・編集、貸出登録、返却、月次レポート、Excel 出力を一通り試します。
- エラーにならなくても、表示がおかしくなっている画面があります。

<details>
<summary>ヒント</summary>

- CakePHP 4 では、テンプレートの置き場所が `src/Template/` から `templates/` に変わりました。拡張子は `.ctp` から `.php` になり、`Layout`、`Element`、`Flash` のフォルダ名は小文字になりました。
- CakePHP 4 では、多くのメソッドに型宣言が付きました。親クラスのメソッドと宣言を合わせます。
- CakePHP 3 で非推奨だったメソッドの一部は、CakePHP 4 で削除されています。
- `Cache::read()` は、データがないときに返す値が変わりました。

</details>

### ステップ3：PHP 8.3 に上げる

ステップ2が終わらなかった人は、`step3-php8` に切り替えてから始めます。

```bash
git switch -f step3-php8
```

```bash
php-switch 8.3
```

```bash
bin/setup
```

`composer install` が失敗します。原因を読み、動く状態にしてから `bin/serve` で起動し、すべての画面を試します。

- エラーにならなくても、表示が変わっている画面があります。

<details>
<summary>ヒント</summary>

- PHPExcel は開発が終わっていて、PHP 8 に対応していません。後継の PhpSpreadsheet に置き換えます。`composer.json` を書き換えたあと、`composer update` で依存パッケージをまとめて更新します。
- PHP 8 では、警告で済んでいた書き方の一部がエラーになりました。
- PHP 8 では、数値と文字列を `==` で比べたときの結果が変わりました。

</details>

最後に、隠していた警告を見てみます。`config/app.php` の `errorLevel` を `E_ALL` に変え、画面を一通り開いてから `logs/debug.log` を見ます。警告は直さずに、一覧で見るだけで構いません。

## Claude Code に手伝ってもらうとき

Codespace のターミナルで Claude Code を動かすと、Claude が直したコードをそのまま `bin/serve` で確かめられます。Claude Code を使うには、Claude の有料プラン（Pro、Max など）か、Anthropic Console の API キーが必要です。

1. ターミナルで Claude Code を入れます。

   ```bash
   curl -fsSL https://claude.ai/install.sh | bash
   ```

2. 新しいターミナルを開き、リポジトリのフォルダで起動します。

   ```bash
   claude
   ```

3. 初回はログインを求められます。表示されたリンクを手元のブラウザで開いてログインします。ブラウザにコードが表示されたら、ターミナルの `Paste code here if prompted` に貼り付けます。

アプリを動かしながら Claude を使うときは、ターミナルを2つ開き、片方で `bin/serve`、もう片方で `claude` を動かします。

詳しくは [Claude Code のセットアップ手順](https://code.claude.com/docs/en/setup) を見てください。
