<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contact;

/**
 * Contacts テーブルシーダー
 * 
 * お問い合わせデータを35件作成（ContactFactory を使用）
 */
class ContactsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ContactFactory を使用して35件のダミーデータを作成
        Contact::factory(35)->create();
    }
}

