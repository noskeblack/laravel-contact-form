# laravel-contact-form

## 環境構築

### Dockerビルド

```bash
git clone github.com:noskeblack/laravel-contact-form.git
docker-compose up -d --build
```

### Laravel環境構築

```bash
docker-compose exec php bash
composer install
cp .env.example .env
# 環境変数を適宜変更
php artisan key:generate
php artisan migrate
php artisan db:seed
```

## 開発環境

- お問い合わせ: http://localhost/
- ユーザー登録: http://localhost/register
- ログイン: http://localhost/login
- 管理画面: http://localhost/admin (認証必須)
- phpMyAdmin: http://localhost:8080/

## 使用技術(実行環境)

- PHP 8.1
- Laravel 8.x (^8.75)
- Laravel Fortify (認証システム)
- Jquery 3.7.1.min.js
- MySQL 8.0.26
- nginx 1.21.1

## 認証機能

本プロジェクトはLaravel Fortifyを使用した認証システムを実装しています。

- ユーザー登録: `/register`
- ログイン: `/login`
- ログアウト: `/logout` (POST)

### 管理画面

管理画面（`/admin`）は認証が必要です。未認証の場合はログインページにリダイレクトされます。

以下の機能は認証後に利用可能です：
- お問い合わせ一覧表示
- お問い合わせ検索
- お問い合わせ詳細表示
- お問い合わせ削除
- CSVエクスポート

## 日本語化対応

日本語翻訳ファイルを `resources/lang/ja/` に配置しています。
- 認証関連メッセージ
- バリデーションメッセージ
- ページネーション
- パスワードリセット

## ER図
![ER図](./images/ER_DIAGRAM.png)


### categories テーブル

- `id`: bigint, Primary Key (PK)
- `content`: varchar, NOT NULL
- `created_at`: timestamp
- `updated_at`: timestamp

### users テーブル

- `id`: bigint, Primary Key (PK)
- `name`: varchar, NOT NULL
- `email`: varchar, NOT NULL, UNIQUE
- `email_verified_at`: timestamp, NULL
- `password`: varchar, NOT NULL
- `remember_token`: varchar, NULL
- `two_factor_secret`: text, NULL (2要素認証用)
- `two_factor_recovery_codes`: text, NULL (2要素認証用)
- `two_factor_confirmed_at`: timestamp, NULL (2要素認証用)
- `created_at`: timestamp
- `updated_at`: timestamp

### contacts テーブル

- `id`: bigint, Primary Key (PK)
- `category_id`: bigint, Foreign Key (FK) → categories.id
- `last_name`: varchar(8), NOT NULL
- `first_name`: varchar(8), NOT NULL
- `gender`: tinyint, NOT NULL
- `email`: varchar, NOT NULL
- `tel_part1`: varchar(5), NOT NULL
- `tel_part2`: varchar(5), NOT NULL
- `tel_part3`: varchar(5), NOT NULL
- `address`: varchar, NOT NULL
- `building`: varchar, NULL
- `detail`: text, NOT NULL
- `created_at`: timestamp
- `updated_at`: timestamp

### リレーション

- `categories` テーブルと `contacts` テーブルは1対多の関係
- `contacts.category_id` は `categories.id` を参照する外部キー

## 開発者向け情報

### データベースファイル

MySQLデータファイル（`docker/mysql/data/`）は `.gitignore` で除外されています。
データベースのスキーマとデータは以下の方法で管理してください：

- **スキーマ**: `database/migrations/` のマイグレーションファイル
- **初期データ**: `database/seeders/` のシーダーファイル

各環境で `php artisan migrate` と `php artisan db:seed` を実行してデータベースを構築してください。
