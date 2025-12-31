@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
@endsection

@section('content')
    <div class="login-container">
        <div class="login-card">
            <h1 class="login-title">Login</h1>
            
            <form action="/login" method="POST" class="login-form">
                @csrf

                <!-- メールアドレス -->
                <div class="login-form-group">
                    <label for="email" class="login-label">
                        メールアドレス
                    </label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email"
                        class="login-input"
                        value="{{ old('email') }}"
                        placeholder="test@example.com"
                        required
                        autofocus
                    >
                    @error('email')
                        <div class="login-error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- パスワード -->
                <div class="login-form-group">
                    <label for="password" class="login-label">
                        パスワード
                    </label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password"
                        class="login-input"
                        placeholder="coachtech06"
                        required
                    >
                    @error('password')
                        <div class="login-error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="login-form-group">
                    <label class="login-checkbox-label">
                        <input 
                            type="checkbox" 
                            name="remember" 
                            class="login-checkbox"
                        >
                        <span class="login-checkbox-text">ログイン状態を保持する</span>
                    </label>
                </div>

                <!-- エラーメッセージ（全般） -->
                @if($errors->has('email') || $errors->has('password'))
                    <div class="login-error-general">
                        @foreach($errors->all() as $error)
                            <div class="login-error-message">{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <!-- ログインボタン -->
                <div class="login-button-group">
                    <button type="submit" class="login-button">
                        ログイン
                    </button>
                </div>
            </form>

            <!-- 登録リンク -->
                <div class="login-register-link">
                    <a href="/register" class="login-register-text">register</a>
                </div>            
        </div>
    </div>
@endsection

