@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/contact/confirm.css') }}">
@endsection

@section('content')
    <!-- 確認コンテナ -->
    <div class="confirm-container">
        <form action="{{ route('contact.send') }}" method="POST">
            @csrf

            @php
                $contactData = session('contact_data', []);
            @endphp

            <!-- お名前 -->
            <div class="confirm-group">
                <div class="confirm-label">
                    お名前<span class="required">※</span>
                </div>
                <div class="confirm-value">
                    {{ $contactData['last_name'] ?? '' }} {{ $contactData['first_name'] ?? '' }}
                </div>
                <input type="hidden" name="last_name" value="{{ $contactData['last_name'] ?? '' }}">
                <input type="hidden" name="first_name" value="{{ $contactData['first_name'] ?? '' }}">
            </div>

            <!-- 性別 -->
            <div class="confirm-group">
                <div class="confirm-label">
                    性別<span class="required">※</span>
                </div>
                <div class="confirm-value">
                    @if(($contactData['gender'] ?? '') == '1')
                        男性
                    @elseif(($contactData['gender'] ?? '') == '2')
                        女性
                    @elseif(($contactData['gender'] ?? '') == '3')
                        その他
                    @endif
                </div>
                <input type="hidden" name="gender" value="{{ $contactData['gender'] ?? '' }}">
            </div>

            <!-- メールアドレス -->
            <div class="confirm-group">
                <div class="confirm-label">
                    メールアドレス<span class="required">※</span>
                </div>
                <div class="confirm-value">
                    {{ $contactData['email'] ?? '' }}
                </div>
                <input type="hidden" name="email" value="{{ $contactData['email'] ?? '' }}">
            </div>

            <!-- 電話番号 -->
            <div class="confirm-group">
                <div class="confirm-label">
                    電話番号<span class="required">※</span>
                </div>
                <div class="confirm-value">
                    {{ $contactData['tel_part1'] ?? '' }}-{{ $contactData['tel_part2'] ?? '' }}-{{ $contactData['tel_part3'] ?? '' }}
                </div>
                <input type="hidden" name="tel_part1" value="{{ $contactData['tel_part1'] ?? '' }}">
                <input type="hidden" name="tel_part2" value="{{ $contactData['tel_part2'] ?? '' }}">
                <input type="hidden" name="tel_part3" value="{{ $contactData['tel_part3'] ?? '' }}">
            </div>

            <!-- 住所 -->
            <div class="confirm-group">
                <div class="confirm-label">
                    住所<span class="required">※</span>
                </div>
                <div class="confirm-value">
                    {{ $contactData['address'] ?? '' }}
                </div>
                <input type="hidden" name="address" value="{{ $contactData['address'] ?? '' }}">
            </div>

            <!-- 建物名 -->
            <div class="confirm-group">
                <div class="confirm-label">
                    建物名
                </div>
                <div class="confirm-value">
                    {{ !empty($contactData['building']) ? $contactData['building'] : '（未入力）' }}
                </div>
                <input type="hidden" name="building" value="{{ $contactData['building'] ?? '' }}">
            </div>

            <!-- お問い合わせの種類 -->
            <div class="confirm-group">
                <div class="confirm-label">
                    お問い合わせの種類<span class="required">※</span>
                </div>
                <div class="confirm-value">
                    @if(isset($categories))
                        @foreach($categories as $category)
                            @if(($contactData['category_id'] ?? '') == $category->id)
                                {{ $category->content }}
                            @endif
                        @endforeach
                    @endif
                </div>
                <input type="hidden" name="category_id" value="{{ $contactData['category_id'] ?? '' }}">
            </div>

            <!-- お問い合わせ内容 -->
            <div class="confirm-group">
                <div class="confirm-label">
                    お問い合わせ内容<span class="required">※</span>
                </div>
                <div class="confirm-value confirm-detail">
                    {{ $contactData['detail'] ?? '' }}
                </div>
                <input type="hidden" name="detail" value="{{ $contactData['detail'] ?? '' }}">
            </div>

            <!-- ボタン -->
            <div class="confirm-buttons">
                <a href="{{ route('contact.edit') }}" class="btn-back">修正</a>
                <button type="submit" class="btn-submit">送信</button>
            </div>
        </form>
    </div>
@endsection

