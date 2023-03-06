# Infra

## ローカル環境

Docker および Docker Compose を使用します。

- イメージはビルドしますが， PHP 拡張のインストールなどランタイム面以外の操作は行いません。
- 設定ファイルおよびソースコードは，イメージにバンドルせずにマウントして使います。
- `npm run watch` を実行し，フロントエンドのコードをコンパイルする必要があります。

[compose.yaml](../compose.yaml) にハードコーディングされたもの以外に関しては，環境変数の用意が必要です。 [.envrc.example](../.envrc.example) に書かれているものを用意してください。

| 環境変数                                  | デフォルト値        | 必須  | 備考                                            |
|:--------------------------------------|:--------------|:---:|:----------------------------------------------|
| `APP_KEY`                             |               |  ✅  | `php artisan key:generate --show` で生成した値を貼り付け |
| `WEB_PUBLISHED_PORT`                  | `8000`        |     | ホスト側に Nginx が公開するポート                          |
| `DB_PUBLISHED_PORT`                   | `3306`        |     | ホスト側に MySQL が公開するポート                          |
| `NEW_RELIC_LICENSE_KEY`               | `""`          |     | New Relic のライセンスキー                            |
| `NEW_RELIC_ENABLED`                   | `false`       |     | New Relic を有効化するかどうか                          |
| `NEW_RELIC_TRANSACTION_TRACER_DETAIL` | `1`           |     | New Relic で全ての関数呼び出しの自動トレースを行うか（負荷あり）         |
| `XDEBUG_ENABLED`                      | `false`       |     | XDebug を有効化するかどうか                             |
| `PHP_IDE_CONFIG`                      | `serverName=` |     | XDebug 用の IDE の設定。サーバ名を設定する必要がある              |
| `VITE_GITHUB_URL`                     | (省略)          |     | Vite でビルドしたフロントエンドに仕込まれる GitHub リポジトリへのリンク    |
| `VITE_NEW_RELIC_URL`                  | (省略)          |     | Vite でビルドしたフロントエンドに仕込まれる New Relic コンソールへのリンク |

## クラウド環境

Docker でビルドしたイメージを Kubernetes 環境にて使用します。最初のクラスタ作成は手動で別途行い，その後のインテグレーションは GitHub Actions で完結させることを前提としています。

**注意点: マイグレーションを自動反映する都合上， MySQL のデータは立ち上げのたびにリセットされます！**

- PHP イメージはビルドし，その際に必要なものは全てバンドルします。
- 立ち上げ時に PHP コンテナ から Nginx コンテナに向けて `public` ディレクトリのコピーを行うため， Nginx イメージはビルドする必要はありません。
- 環境変数に応じて動的にマニフェストを生成できるように， [interpolate.sh](./k8s/interpolate.sh) というシェルスクリプトを整備してあります。この結果を `kubectl apply -f -` に流し込むとデプロイできるようになっています。

イメージのビルドのために，以下の環境変数が必要です。すべて必須です。

| 環境変数                 | 備考                                            |
|:---------------------|:----------------------------------------------|
| `VITE_GITHUB_URL`    | Vite でビルドしたフロントエンドに仕込まれる GitHub リポジトリへのリンク    |
| `VITE_NEW_RELIC_URL` | Vite でビルドしたフロントエンドに仕込まれる New Relic コンソールへのリンク |

マニフェスト出力のために，以下の環境変数の用意が必要です。すべて必須です。

| 環境変数                                       | 備考                                                                                               |
|:-------------------------------------------|:-------------------------------------------------------------------------------------------------|
| `K8S_IMAGE_REPOSITORY_PHP_LARAVEL`         | Laravel をバンドルした PHP イメージを管理するリポジトリ                                                               |
| `K8S_LARAVEL_APP_URL`                      | Web に公開後にアクセスできる実際のドメインを用いた URL (Origin)                                                         |
| `K8S_SECRET_BASE64_MYSQL_PASSWORD`         | BASE 64 エンコードされた MySQL のパスワード                                                                    | 
| `K8S_SECRET_BASE64_MYSQL_ROOT_PASSWORD`    | BASE 64 エンコードされた MySQL のルートパスワード                                                                 |
| `K8S_SECRET_BASE64_LARAVEL_ENCRYPTION_KEY` | BASE 64 エンコードされた Laravel の `APP_KEY` 相当の値<br>**（`base64:xxxxx` という文字列をさらに BASE 64 エンコードする必要あり）** |
| `K8S_SECRET_BASE64_NEW_RELIC_LICENSE_KEY`  | BASE 64 エンコードされた New Relic のライセンスキー                                                              |
