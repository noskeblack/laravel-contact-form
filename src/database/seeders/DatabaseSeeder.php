<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * データベースシーダー
 * 
 * 全シーダーを実行するメインシーダー
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // カテゴリを先に作成（contacts テーブルの外部キー制約のため）
        $this->call([
            CategoriesTableSeeder::class,
            ContactsTableSeeder::class,
        ]);
    }
}
