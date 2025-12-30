<?php

namespace Database\Factories;

use App\Models\Contact;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Contact ファクトリ
 * 
 * お問い合わせデータのダミーデータを生成
 */
class ContactFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Contact::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // 日本の姓名を生成
        $lastNames = ['山田', '佐藤', '鈴木', '田中', '渡辺', '伊藤', '中村', '小林', '加藤', '吉田'];
        $firstNames = ['太郎', '花子', '一郎', '次郎', '三郎', '美咲', 'さくら', '大輔', '健太', 'あゆみ'];
        
        // 電話番号の3分割（各項目最大5桁まで）
        // 各項目は1桁から5桁のランダムな数値を生成
        $telPart1 = $this->faker->numberBetween(0, 99999); // 0から99999まで（最大5桁）
        $telPart2 = $this->faker->numberBetween(0, 99999); // 0から99999まで（最大5桁）
        $telPart3 = $this->faker->numberBetween(0, 99999); // 0から99999まで（最大5桁）
        
        return [
            'last_name' => $this->faker->randomElement($lastNames),
            'first_name' => $this->faker->randomElement($firstNames),
            'gender' => $this->faker->numberBetween(1, 3),
            'email' => $this->faker->unique()->safeEmail(),
            'tel_part1' => (string)$telPart1,
            'tel_part2' => (string)$telPart2,
            'tel_part3' => (string)$telPart3,
            'address' => $this->faker->address(),
            'building' => $this->faker->optional()->secondaryAddress(),
            'category_id' => Category::inRandomOrder()->first()->id ?? 1,
            'detail' => $this->faker->realText(100, 2),
        ];
    }
}

