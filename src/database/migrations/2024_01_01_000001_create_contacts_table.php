<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * contacts テーブル作成マイグレーション
 * 
 * お問い合わせデータを管理するテーブル
 */
class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('last_name', 8)->comment('姓');
            $table->string('first_name', 8)->comment('名');
            $table->tinyInteger('gender')->comment('性別: 1=男性, 2=女性, 3=その他');
            $table->string('email')->comment('メールアドレス');
            $table->string('tel_part1', 5)->comment('電話番号（1つ目）');
            $table->string('tel_part2', 5)->comment('電話番号（2つ目）');
            $table->string('tel_part3', 5)->comment('電話番号（3つ目）');
            $table->string('address')->comment('住所');
            $table->string('building')->nullable()->comment('建物名');
            $table->foreignId('category_id')->constrained('categories')->comment('お問い合わせの種類ID');
            $table->text('detail')->comment('お問い合わせ内容');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contacts');
    }
}

