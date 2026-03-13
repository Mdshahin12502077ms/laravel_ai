<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">
<div class="d-flex min-vh-100 bg-light">
    <!-- Sidebar -->
    <nav class="sidebar bg-dark text-white p-3" style="width: 220px;">
        <div class="mb-4 text-center">
            <span class="fw-bold fs-4">My Routes</span>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item mb-2"><a class="nav-link text-white active" href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="nav-item mb-2"><a class="nav-link text-white" href="{{ route('profile.edit') }}">Profile Edit</a></li>
            <li class="nav-item mb-2"><a class="nav-link text-white" href="{{ route('chatbot') }}">Chatbot</a></li>
            <li class="nav-item mb-2"><a class="nav-link text-white" href="{{ route('cv-generator') }}">CV Generator</a></li>
            <li class="nav-item mb-2"><a class="nav-link text-white" href="{{ route('transcribe') }}">Transcribe</a></li>
            <li class="nav-item mb-2"><a class="nav-link text-white" href="{{ route('register') }}">Register</a></li>
            <li class="nav-item mb-2"><a class="nav-link text-white" href="{{ route('login') }}">Login</a></li>
            <li class="nav-item mb-2"><a class="nav-link text-white" href="{{ route('password.request') }}">Forgot Password</a></li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="flex-grow-1">
        <div class="container py-5">
            @yield('page-content')
        </div>
    </div>
</div>
</body>
</html>
