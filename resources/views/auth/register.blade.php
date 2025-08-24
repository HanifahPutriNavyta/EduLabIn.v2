<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Daftar</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="{{ asset('css/daftar.css') }}" rel="stylesheet">
</head>

<body class="daftar-page">
  <div class="daftar-container">
    <!-- Logo -->
    <img class="logo" src="/img/LogoEduLabIn.png" alt="Edu LabIn Logo">

    <!-- Register Form -->
    <div class="form-container">
  @if(Route::has('register'))
  <form action="{{ route('register') }}" method="POST">
  @else
  <form action="#" method="POST">
  @endif
        @csrf

        <!-- Nama Lengkap -->
        <div class="mb-3">
          <input type="text" name="name" class="form-control" placeholder="Nama Lengkap" required>
        </div>

        <!-- Email -->
        <div class="mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email" required>
        </div>

        <!-- Password -->
        <div class="mb-3">
          <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>

        <!-- Confirm Password -->
        <div class="mb-3">
          <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" required>
        </div>

        <!-- Register Button -->
        <div class="text-center mb-4">
          <button class="daftar-btn" type="submit">Daftar</button>
        </div>

        <!-- Link Login -->
        <div class="login-text-container text-center">
          <span class="text-secondary">Sudah punya akun?</span>
          @if(Route::has('login'))
            <a href="{{ route('login') }}" class="login-link">Login</a>
          @else
            <span class="login-link text-muted">Login (route missing)</span>
          @endif
        </div>
      </form>
    </div>
  </div>

  <!-- JS Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>