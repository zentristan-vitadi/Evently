<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Evently') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 bg-gray-50 antialiased">
    <div class="min-h-screen flex flex-col justify-center items-center py-12 px-4 sm:px-6 lg:px-8">
        <!-- Logo -->
        <div class="mb-6 text-center">
            <a href="{{ route('events.index') }}" class="inline-flex items-center space-x-3.5">
                <img src="{{ asset('images/EventlyLOGO.png') }}" alt="Logo" class="w-10 h-10">

            </a>
        </div>

        <!-- Auth Card -->
        <div class="w-full sm:max-w-md bg-white border border-gray-200 shadow-sm rounded-2xl p-6 sm:p-8">
            {{ $slot }}
        </div>

        <!-- Back to home -->
        <div class="mt-6 text-center">
            <a href="{{ route('events.index') }}" class="text-xs font-medium text-gray-500 hover:text-indigo-600 transition inline-flex items-center">
                <svg class="w-3.5 h-3.5 me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Jelajah Event
            </a>
        </div>
    </div>
</body>

</html>
