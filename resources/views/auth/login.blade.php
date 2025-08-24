<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="{{ asset('css/login.css') }}" rel="stylesheet">
</head>
<body class="login-page">

<div class="login-container">
  <img class="login-logo" src="/img/LogoEduLabIn.png" alt="EduLabInLogo">

  <div class="form-container">
    <form action="{{ route('login') }}" method="POST">
      @csrf

      @if ($errors->any())
        <div class="alert alert-danger mb-3">
          <ul class="mb-0">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      @if (session('error'))
        <div class="alert alert-danger mb-3">
          {{ session('error') }}
        </div>
      @endif

      <!-- Username -->
      <div class="mb-3">
        <input type="text" name="username" class="form-control login-input @error('username') is-invalid @enderror" id="Username" placeholder="Username" value="{{ old('username') }}" required>
        @error('username')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <!-- Password -->
      <div class="mb-3">
        <input type="password" name="password" class="form-control login-input @error('password') is-invalid @enderror" id="Password" placeholder="Password" required>
        @error('password')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
      

      <!-- Role Dropdown -->
      <div class="role-dropdown-container mb-3">
        <div class="btn-group w-100">
          <button type="button" class="btn role-display @error('role') is-invalid @enderror" id="roleDisplay" disabled>
            <span class="role-selected">{{ old('role', 'Role') }}</span>
          </button>
          <div class="split-line"></div> 
          <button type="button" class="btn dropdown-toggle dropdown-toggle-split" id="roleToggleBtn" aria-expanded="false">
            <img class="dropdown-arrow" src="/img/IconDropdownLogin.png" alt="Dropdown">
            <span class="visually-hidden">Toggle Dropdown</span>
          </button>
          <ul class="role-dropdown-menu w-100">
            <li><a class="dropdown-item role-dropdown-item" href="#" data-role="asprak">Asisten Praktikum</a></li>
            <li><a class="dropdown-item role-dropdown-item" href="#" data-role="dosen">Dosen</a></li>
            <li><a class="dropdown-item role-dropdown-item" href="#" data-role="laboran">Laboran</a></li>
          </ul>
        </div>
        <input type="hidden" name="role" id="selectedRole" value="{{ old('role') }}">
        @error('role')
          <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
      </div>

      <!-- Forgot Password -->
      <!-- <div class="text-center mb-3">
        <a href="#" class="forgot-password">Forgot Password</a>
      </div> -->

      <!-- Login Button -->
      <div class="text-center mb-3">
        <button class="login-btn primary-btn" type="submit">Login</button>
      </div>

      <!-- Register Link -->
      <!-- If enabled, show link only when route exists -->
      @if(Route::has('register'))
      <div class="login-text-container text-center">
        <span class="text-secondary">Belum punya akun?</span>
        <a href="{{ route('register') }}" class="login-link">Daftar</a>
      </div>
      @endif
    </form>
  </div>
</div>

<!-- JS Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>

<!-- Role Dropdown Script -->
<script>
  document.addEventListener("DOMContentLoaded", function() {
    const dropdownToggle = document.querySelector("#roleToggleBtn");
    const dropdownMenu = document.querySelector(".role-dropdown-menu");
    const dropdownItems = document.querySelectorAll(".role-dropdown-item");
    const roleDisplay = document.querySelector("#roleDisplay .role-selected");
    const hiddenInput = document.querySelector("#selectedRole");

    dropdownToggle.addEventListener("click", function(e) {
      e.preventDefault();
      dropdownMenu.classList.toggle("show");
      this.classList.toggle("active");
    });

    window.addEventListener("click", function(e) {
      if (!dropdownToggle.contains(e.target) && !dropdownMenu.contains(e.target)) {
        dropdownMenu.classList.remove("show");
        dropdownToggle.classList.remove("active");
      }
    });

    dropdownItems.forEach(function(item) {
      item.addEventListener("click", function(e) {
        e.preventDefault();
        const selectedRole = this.getAttribute("data-role");
        const displayText = this.textContent;
        roleDisplay.textContent = displayText;
        hiddenInput.value = selectedRole;
        dropdownMenu.classList.remove("show");
        dropdownToggle.classList.remove("active");
      });
    });
  });
</script>
</body>
</html>