@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/contact/index.css') }}">
@endsection

@section('content')
    <!-- フォームコンテナ -->
    <div class="form-container">
        <form action="{{ route('contact.store') }}" method="POST">
            @csrf

            <!-- お名前 -->
            <div class="form-group">
                <label class="form-label">
                    お名前<span class="required">※</span>
                </label>
                <div class="form-input-group">
                    <div class="name-inputs">
                        <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" placeholder="例:山田">
                        <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" placeholder="例:太郎">
                    </div>
                    @error('last_name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                    @error('first_name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- 性別 -->
            <div class="form-group">
                <label class="form-label">
                    性別<span class="required">※</span>
                </label>
                <div class="form-input-group">
                    <div class="radio-group">
                        <div class="radio-item">
                            <input type="radio" name="gender" id="gender_male" value="1" {{ old('gender') == '1' ? 'checked' : '' }}>
                            <label for="gender_male">男性</label>
                        </div>
                        <div class="radio-item">
                            <input type="radio" name="gender" id="gender_female" value="2" {{ old('gender') == '2' ? 'checked' : '' }}>
                            <label for="gender_female">女性</label>
                        </div>
                        <div class="radio-item">
                            <input type="radio" name="gender" id="gender_other" value="3" {{ old('gender') == '3' ? 'checked' : '' }}>
                            <label for="gender_other">その他</label>
                        </div>
                    </div>
                    @error('gender')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- メールアドレス -->
            <div class="form-group">
                <label class="form-label">
                    メールアドレス<span class="required">※</span>
                </label>
                <div class="form-input-group">
                    <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="例:test@example.com">
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- 電話番号 -->
            <div class="form-group">
                <label class="form-label">
                    電話番号<span class="required">※</span>
                </label>
                <div class="form-input-group">
                    <div class="tel-inputs">
                        <input type="tel" name="tel_part1" id="tel_part1" value="{{ old('tel_part1') }}" placeholder="例:080" maxlength="5">
                        <span class="tel-separator">-</span>
                        <input type="tel" name="tel_part2" id="tel_part2" value="{{ old('tel_part2') }}" placeholder="例:1234" maxlength="5">
                        <span class="tel-separator">-</span>
                        <input type="tel" name="tel_part3" id="tel_part3" value="{{ old('tel_part3') }}" placeholder="例:5678" maxlength="5">
                    </div>
                    @error('tel')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                    @error('tel_part1')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                    @error('tel_part2')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                    @error('tel_part3')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- 住所 -->
            <div class="form-group">
                <label class="form-label">
                    住所<span class="required">※</span>
                </label>
                <div class="form-input-group">
                    <input type="text" name="address" id="address" value="{{ old('address') }}" placeholder="例:東京都渋谷区千駄ヶ谷1-2-3">
                    @error('address')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- 建物名 -->
            <div class="form-group">
                <label class="form-label">
                    建物名
                </label>
                <div class="form-input-group">
                    <input type="text" name="building" id="building" value="{{ old('building') }}" placeholder="例:千駄ヶ谷マンション101">
                    @error('building')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- お問い合わせの種類 -->
            <div class="form-group">
                <label class="form-label">
                    お問い合わせの種類<span class="required">※</span>
                </label>
                <div class="form-input-group">
                    <select name="category_id" id="category_id" required>
                        <option value="">選択してください</option>
                        @if(isset($categories))
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->content }}</option>
                            @endforeach
                        @endif
                    </select>
                    @error('category_id')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- お問い合わせ内容 -->
            <div class="form-group">
                <label class="form-label">
                    お問い合わせ内容<span class="required">※</span>
                </label>
                <div class="form-input-group">
                    <textarea name="detail" id="detail" placeholder="お問い合わせ内容をご記載ください">{{ old('detail') }}</textarea>
                    @error('detail')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- 送信ボタン -->
            <div class="submit-button">
                <button type="submit">確認画面</button>
            </div>
        </form>
    </div>
@endsection