# ER 図 - 論理設計書

## テーブル一覧

本システムは以下の 3 つの主要テーブルで構成されています。

1. **categories** - お問い合わせの種類を管理
2. **users** - ユーザー認証情報を管理
3. **contacts** - お問い合わせデータを管理

---

## 1. categories テーブル

お問い合わせの種類（カテゴリ）を管理するマスタテーブルです。

| カラム名   | データ型        | 制約                        | 説明                     |
| ---------- | --------------- | --------------------------- | ------------------------ |
| id         | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | カテゴリ ID（主キー）    |
| content    | VARCHAR(255)    | NOT NULL                    | お問い合わせの種類の内容 |
| created_at | TIMESTAMP       | NULL                        | レコード作成日時         |
| updated_at | TIMESTAMP       | NULL                        | レコード更新日時         |

### インデックス

- PRIMARY KEY: `id`

### リレーション

- **1 対多**: `categories` ← `contacts` (1 つのカテゴリに複数のお問い合わせが紐づく)

---

## 2. users テーブル

ユーザー認証情報を管理するテーブルです（Laravel 標準の認証機能で使用）。

| カラム名          | データ型        | 制約                        | 説明                       |
| ----------------- | --------------- | --------------------------- | -------------------------- |
| id                | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | ユーザー ID（主キー）      |
| name              | VARCHAR(255)    | NOT NULL                    | ユーザー名                 |
| email             | VARCHAR(255)    | NOT NULL, UNIQUE            | メールアドレス（一意制約） |
| email_verified_at | TIMESTAMP       | NULL                        | メールアドレス確認日時     |
| password          | VARCHAR(255)    | NOT NULL                    | パスワード（ハッシュ化）   |
| remember_token    | VARCHAR(100)    | NULL                        | ログイン状態保持用トークン |
| created_at        | TIMESTAMP       | NULL                        | レコード作成日時           |
| updated_at        | TIMESTAMP       | NULL                        | レコード更新日時           |

### インデックス

- PRIMARY KEY: `id`
- UNIQUE: `email`

### リレーション

- 現在、他のテーブルとの外部キーリレーションは定義されていません

---

## 3. contacts テーブル

お問い合わせデータを管理するテーブルです。

| カラム名    | データ型        | 制約                        | 説明                              |
| ----------- | --------------- | --------------------------- | --------------------------------- |
| id          | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | お問い合わせ ID（主キー）         |
| category_id | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY       | お問い合わせの種類 ID（外部キー） |
| last_name   | VARCHAR(8)      | NOT NULL                    | 姓                                |
| first_name  | VARCHAR(8)      | NOT NULL                    | 名                                |
| gender      | TINYINT         | NOT NULL                    | 性別（1=男性, 2=女性, 3=その他）  |
| email       | VARCHAR(255)    | NOT NULL                    | メールアドレス                    |
| tel_part1   | VARCHAR(5)      | NOT NULL                    | 電話番号（1 つ目）                |
| tel_part2   | VARCHAR(5)      | NOT NULL                    | 電話番号（2 つ目）                |
| tel_part3   | VARCHAR(5)      | NOT NULL                    | 電話番号（3 つ目）                |
| address     | VARCHAR(255)    | NOT NULL                    | 住所                              |
| building    | VARCHAR(255)    | NULL                        | 建物名                            |
| detail      | TEXT            | NOT NULL                    | お問い合わせ内容                  |
| created_at  | TIMESTAMP       | NULL                        | レコード作成日時                  |
| updated_at  | TIMESTAMP       | NULL                        | レコード更新日時                  |

### インデックス

- PRIMARY KEY: `id`
- FOREIGN KEY: `category_id` → `categories.id`

### 外部キー制約

- `category_id` → `categories.id` (ON DELETE 制約: デフォルト設定に従う)

### リレーション

- **多対 1**: `contacts` → `categories` (複数のお問い合わせが 1 つのカテゴリに紐づく)

---

## ER 図のリレーション概要

```
┌─────────────┐
│  categories │
│             │
│ id (PK)     │
│ content     │
└──────┬──────┘
       │
       │ 1
       │
       │ 多
       │
┌──────▼──────┐
│   contacts  │
│             │
│ id (PK)     │
│ category_id │───FK───┐
│ last_name   │        │
│ first_name  │        │
│ gender      │        │
│ email       │        │
│ tel_part1   │        │
│ tel_part2   │        │
│ tel_part3   │        │
│ address     │        │
│ building    │        │
│ detail      │        │
└─────────────┘        │
                       │
┌─────────────┐        │
│    users    │        │
│             │        │
│ id (PK)     │        │
│ name        │        │
│ email       │        │
│ password    │        │
└─────────────┘        │
                       │
                       └─── (categories.id)
```

### リレーション詳細

1. **categories ↔ contacts**

   - 関係: **1 対多（One-to-Many）**
   - 説明: 1 つのカテゴリに複数のお問い合わせが紐づく
   - 外部キー: `contacts.category_id` → `categories.id`
   - カーディナリティ: `categories(1) ──< contacts(多)`

2. **users テーブル**
   - 現在、他のテーブルとの外部キーリレーションは定義されていません
   - 独立したテーブルとして管理されています

---

## データ型の詳細

### 数値型

- **BIGINT UNSIGNED**: 0 から 18,446,744,073,709,551,615 までの整数（主キーや外部キーに使用）
- **TINYINT**: -128 から 127 までの整数（性別などに使用）

### 文字列型

- **VARCHAR(n)**: 最大 n 文字の可変長文字列
- **TEXT**: 最大 65,535 文字の可変長文字列

### 日時型

- **TIMESTAMP**: 日時を格納（タイムゾーン情報を含む）

---

## 制約の詳細

### NOT NULL 制約

- 必須項目として設定されているカラムには NOT NULL 制約が設定されています
- `contacts.building`のみ NULL 許可（建物名は任意項目）

### UNIQUE 制約

- `users.email`: メールアドレスの重複を防止

### 外部キー制約

- `contacts.category_id` → `categories.id`: 参照整合性を保証

---

## 備考

- すべてのテーブルに`created_at`と`updated_at`が自動的に設定されます（Laravel の`timestamps()`機能）
- `users`テーブルは Laravel 標準の認証機能で使用されます
- `contacts`テーブルの電話番号は 3 分割（`tel_part1`, `tel_part2`, `tel_part3`）で管理されています
- 性別は`TINYINT`型で、1=男性、2=女性、3=その他として定義されています
