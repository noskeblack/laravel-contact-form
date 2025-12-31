# マイグレーションとシーダー実行ガイド

## マイグレーション確認

### 実行コマンド
```bash
php artisan migrate
```

### 作成されるテーブル

#### 1. categories テーブル
- **ファイル**: `2024_01_01_000000_create_categories_table.php`
- **カラム**:
  - `id` (bigint, primary key, auto increment)
  - `content` (string) - お問い合わせの種類の内容
  - `created_at` (timestamp)
  - `updated_at` (timestamp)

#### 2. contacts テーブル
- **ファイル**: `2024_01_01_000001_create_contacts_table.php`
- **カラム**:
  - `id` (bigint, primary key, auto increment)
  - `last_name` (string, max:8) - 姓
  - `first_name` (string, max:8) - 名
  - `gender` (tinyInteger) - 性別: 1=男性, 2=女性, 3=その他
  - `email` (string) - メールアドレス
  - `tel_part1` (string, max:4) - 電話番号（1つ目）
  - `tel_part2` (string, max:4) - 電話番号（2つ目）
  - `tel_part3` (string, max:4) - 電話番号（3つ目）
  - `address` (string) - 住所
  - `building` (string, nullable) - 建物名
  - `category_id` (bigint, foreign key → categories.id) - お問い合わせの種類ID
  - `detail` (text) - お問い合わせ内容
  - `created_at` (timestamp)
  - `updated_at` (timestamp)

#### 3. users テーブル
- **ファイル**: `2014_10_12_000000_create_users_table.php` (既存)
- **カラム**: 標準のLaravel usersテーブル構造

### マイグレーション実行順序
1. `create_categories_table` (先に実行)
2. `create_contacts_table` (categoriesテーブル作成後に実行、外部キー制約のため)

---

## ダミーデータ作成

### 1. categories テーブル（5件）

#### 実行コマンド
```bash
php artisan db:seed --class=CategoriesTableSeeder
```

#### 作成されるデータ
1. 商品のお届けについて
2. 商品の交換について
3. 商品トラブル
4. ショップへのお問い合わせ
5. その他

#### シーダーファイル
- **ファイル**: `database/seeders/CategoriesTableSeeder.php`
- **役割**: 固定データ（5件）を直接作成

---

### 2. contacts テーブル（35件）

#### 実行コマンド
```bash
php artisan db:seed --class=ContactsTableSeeder
```

または、tinkerを使用:
```bash
php artisan tinker
>>> App\Models\Contact::factory(35)->create();
```

#### 作成されるデータ
- **件数**: 35件
- **データ内容**: ContactFactory により生成されるリアルなダミーデータ
  - お名前: 日本の姓名（山田、佐藤、鈴木など）
  - 性別: 1（男性）、2（女性）、3（その他）のいずれか
  - メールアドレス: 一意のメールアドレス
  - 電話番号: 3分割入力、合計5桁以内
  - 住所: リアルな住所
  - 建物名: オプション（null の可能性あり）
  - お問い合わせの種類: categories テーブルからランダムに選択
  - お問い合わせ内容: リアルなテキスト（最大120文字）

#### ファクトリファイル
- **ファイル**: `database/factories/ContactFactory.php`
- **役割**: リアルなダミーデータを生成

#### シーダーファイル
- **ファイル**: `database/seeders/ContactsTableSeeder.php`
- **役割**: ContactFactory を使用して35件のデータを作成

---

## 一括実行

### 全マイグレーション + 全シーダーを実行
```bash
php artisan migrate --seed
```

### 個別実行
```bash
# マイグレーションのみ
php artisan migrate

# カテゴリシーダーのみ
php artisan db:seed --class=CategoriesTableSeeder

# お問い合わせシーダーのみ
php artisan db:seed --class=ContactsTableSeeder
```

---

## 確認方法

### テーブル構造の確認
```bash
php artisan migrate:status
```

### データの確認
```bash
php artisan tinker
>>> App\Models\Category::count();  // 5件
>>> App\Models\Contact::count();   // 35件
```

---

## 注意事項

1. **実行順序**: categories テーブルを先に作成してから contacts テーブルを作成する必要があります（外部キー制約のため）
2. **電話番号**: 合計5桁以内の制約を満たすように ContactFactory で生成しています
3. **外部キー**: contacts.category_id は categories.id を参照します

