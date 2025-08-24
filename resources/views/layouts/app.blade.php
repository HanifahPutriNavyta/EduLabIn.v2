<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'EduLabIn')</title>
    <link rel="icon" href="{{ asset('img/LogoEduLabIn.png') }}" type="image/x-icon">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --primary: rgba(0, 40, 67, 1);
            --secondary: rgba(237, 139, 0, 1);
            --based-50: rgba(255, 255, 255, 1);
            --neutral-600: rgba(93, 93, 93, 1);
            --secondary-orange800: rgba(161, 56, 11, 1);
        }

        body {
            font-family: 'Montserrat', sans-serif;
            min-height: 100vh;
        }

        .form-control, .form-select {
            height: 50px;
            border-radius: 15px;
            background-color: var(--based-50);
            color: var(--neutral-600);
            box-shadow: 0px 4px 4px rgba(117, 117, 117, 0.5);
            border: none;
            font-family: 'Montserrat', sans-serif;
            padding: 10px 20px;
        }

        .form-control::placeholder {
            opacity: 0.5;
            color: var(--neutral-600);
        }

        .form-control:focus, .form-select:focus {
            box-shadow: 0px 4px 4px rgba(117, 117, 117, 0.5);
            border-color: var(--secondary);
        }

        .form-select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%235d5d5d' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
        }

        textarea {
            min-height: 200px !important;
        }

        .form-select option {
            font-family: 'Montserrat', sans-serif;
            color: var(--neutral-600);
            padding: 10px;
        }

        .form-select option:disabled {
            color: var(--neutral-600);
            opacity: 0.5;
        }

        .text-secondary {
            color: var(--secondary) !important;
        }

        .page-title {
            font-size: 20px;
            font-weight: 700;
            color: #333;
            margin-top: 20px;
            text-align: left;
        }
    </style>

    <!-- Page Specific Styles -->
    @stack('styles')
</head>

<body>
    @include('partials.navbar')
    <main class="container py-4">
        @yield('content')
    </main>

    <!-- Common Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>

    <!-- Page Specific Scripts -->
    @stack('scripts')
</body>

</html>
