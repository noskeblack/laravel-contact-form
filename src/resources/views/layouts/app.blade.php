<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FashionablyLate</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
</head>
<body>
    <header class="header">
        <div class="brand-name">FashionablyLate</div>
        <a class="contact-link" href="/contact">お問い合わせ</a>
    </header>
    <main class="main-content">
        @yield('content')
    </main>
    <!-- jQuery（モーダル制御用） -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @yield('js')
</body>
</html>

