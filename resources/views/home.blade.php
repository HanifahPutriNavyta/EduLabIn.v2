<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/landingPage.css') }}" rel="stylesheet">
</head>

<body class="landing-page">
    <div class="landing-container">
        <!-- Logo -->
        <img src="{{ asset('img/LogoEduLabIn.png') }}" alt="EduLabIn Logo" class="logo">

        <!-- Button Masuk Dengan Akun -->
        @if(Route::has('login'))
            <a href="{{ route('login') }}" class="btn btn-login landing-btn">
        @else
            <a href="#" class="btn btn-login landing-btn disabled" aria-disabled="true">
        @endif
            Masuk Dengan Akun
        </a>

        <!-- Button Masuk Dengan Akun -->
            @if(Route::has('calonAsprak.DashboardCasprak'))
                <a href="{{ route('calonAsprak.DashboardCasprak') }}" class="btn btn-register landing-btn">
            @else
                <a href="#" class="btn btn-register landing-btn disabled" aria-disabled="true">
            @endif
            Pendaftaran Calon Asisten Praktikum
        </a>
    </div>

    <!-- JS Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>