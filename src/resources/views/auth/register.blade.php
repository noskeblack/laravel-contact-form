@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
@endsection

@section('content')
    <div class="register-container">
        <div class="register-card">
            <h1 class="register-title">Register</h1>
            
            <form action="/register" method="POST" class="register-form">
                @csrf

                <!-- お名前 -->
                <div class="register-form-group">
                    <label for="name" class="register-label">
                        お名前
                    </label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name"
                        class="register-input"
                        value="{{ old('name') }}"
                        placeholder="例:山田 太郎"
                        required
                        autofocus
                    >
                    @error('name')
                        <div class="register-error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- メールアドレス -->
                <div class="register-form-group">
                    <label for="email" class="register-label">
                        メールアドレス
                    </label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email"
                        class="register-input"
                        value="{{ old('email') }}"
                        placeholder="test@example.com"
                        required
                    >
                    @error('email')
                        <div class="register-error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- パスワード -->
                <div class="register-form-group">
                    <label for="password" class="register-label">
                        パスワード
                    </label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password"
                        class="register-input"
                        placeholder="例: coachtechno6"
                        required
                    >
                    @error('password')
                        <div class="register-error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- パスワード確認 -->
                <div class="register-form-group">
                    <label for="password_confirmation" class="register-label">
                        パスワード（確認）
                    </label>
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        id="password_confirmation"
                        class="register-input"
                        placeholder="例: coachtechno6"
                        required
                    >
                </div>

                <!-- エラーメッセージ（全般） -->
                @if($errors->has('name') || $errors->has('email') || $errors->has('password'))
                    <div class="register-error-general">
                        @foreach($errors->all() as $error)
                            <div class="register-error-message">{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <!-- 登録ボタン -->
                <div class="register-button-group">
                    <button type="submit" class="register-button">
                        登録
                    </button>
                </div>
            </form>

            <!-- ログインリンク -->
                <div class="register-login-link">
                    <a href="/login" class="register-login-text">login</a>
                </div>
        </div>
    </div>
@endsection

