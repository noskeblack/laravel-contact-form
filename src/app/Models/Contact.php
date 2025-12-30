<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Contact モデル
 * 
 * お問い合わせデータを管理するモデル
 */
class Contact extends Model
{
    use HasFactory;

    /**
     * 一括代入可能な属性
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'last_name',
        'first_name',
        'gender',
        'email',
        'tel_part1',
        'tel_part2',
        'tel_part3',
        'address',
        'building',
        'category_id',
        'detail',
    ];

    /**
     * お問い合わせの種類とのリレーション
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

