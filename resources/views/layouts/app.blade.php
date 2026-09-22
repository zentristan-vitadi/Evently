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

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-gray-900 bg-gray-50" x-data="{ sidebarOpen: false }">
    <div class="min-h-screen flex">
        <!-- Desktop Sidebar -->
        <div class="hidden md:flex md:w-64 md:flex-col md:fixed md:inset-y-0 z-30">
            <x-sidebar />
        </div>

        <!-- Mobile Sidebar Drawer / Backdrop -->
        <div x-show="sidebarOpen" class="relative z-50 md:hidden" style="display: none;" role="dialog" aria-modal="true">
            <div x-show="sidebarOpen"
                 x-transition:enter="transition-opacity ease-linear duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="sidebarOpen = false"
                 class="fixed inset-0 bg-gray-900/60 backdrop-blur-xs"></div>

            <div class="fixed inset-0 flex">
                <div x-show="sidebarOpen"
                     x-transition:enter="transition ease-in-out duration-300 transform"
                     x-transition:enter-start="-translate-x-full"
                     x-transition:enter-end="translate-x-0"
                     x-transition:leave="transition ease-in-out duration-300 transform"
                     x-transition:leave-start="translate-x-0"
                     x-transition:leave-end="-translate-x-full"
                     class="relative mr-16 flex w-full max-w-xs flex-1">
                    <div class="absolute left-full top-0 flex w-16 justify-center pt-5">
                        <button type="button" @click="sidebarOpen = false" class="-m-2.5 p-2.5 text-white">
                            <span class="sr-only">Close sidebar</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <x-sidebar />
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 md:pl-64">
            <!-- Top Navigation Bar -->
            <header class="sticky top-0 z-20 bg-white/90 backdrop-blur-md border-b border-gray-200 h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8">
                <div class="flex items-center space-x-3">
                    <!-- Mobile Hamburger -->
                    <button @click="sidebarOpen = true" type="button" class="md:hidden p-2 rounded-xl text-gray-500 hover:text-gray-900 hover:bg-gray-100 focus:outline-none transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <!-- Mobile Brand Icon -->
                    <a href="{{ route('events.index') }}" class="md:hidden flex items-center space-x-2">
                        <img src="{{ asset('images/SingleLogo.png') }}" alt="Logo" class="w-10 h-10">
                        <span class="font-bold text-xl text-gray-800 tracking-tight">Evently<span class="text-indigo-600"></span>
                    </a>
                </div>

                <!-- Right Topbar Profile / Actions -->
                <div class="flex items-center space-x-4">
                    @auth
                        @php
                            $roleBadges = [
                                'admin' => 'bg-purple-100 text-purple-800 border-purple-200',
                                'panitia' => 'bg-blue-100 text-blue-800 border-blue-200',
                                'peserta' => 'bg-green-100 text-green-800 border-green-200',
                            ];
                        @endphp
                        <span class="hidden sm:inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $roleBadges[Auth::user()->role] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst(Auth::user()->role) }}
                        </span>

                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-1.5 border border-gray-200 text-sm leading-4 font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 focus:outline-none transition ease-in-out duration-150">
                                    <div class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 font-bold flex items-center justify-center text-[11px] me-2">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                    <div class="max-w-[120px] truncate text-xs font-semibold">{{ Auth::user()->name }}</div>
                                    <svg class="fill-current h-4 w-4 ms-1.5 text-gray-400" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="px-4 py-2 border-b border-gray-100">
                                    <p class="text-xs text-gray-500">Masuk sebagai:</p>
                                    <p class="text-xs font-bold text-gray-900 truncate">{{ Auth::user()->email }}</p>
                                </div>

                                <x-dropdown-link :href="route('profile.edit')">
                                    {{ __('Pengaturan Profil') }}
                                </x-dropdown-link>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    @else
                        <a href="{{ route('login') }}" class="text-xs font-semibold text-gray-700 hover:text-indigo-600 transition">Log In</a>
                        <a href="{{ route('register') }}" class="inline-flex items-center px-3.5 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow-sm transition">Daftar</a>
                    @endauth
                </div>
            </header>

            <!-- Page Header (if any) -->
            @isset($header)
                <div class="bg-white border-b border-gray-200 py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            @endisset

            <!-- Page Content -->
            <main class="flex-1">
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- SweetAlert2 Trigger Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    confirmButtonColor: '#4f46e5',
                    timer: 5000,
                    timerProgressBar: true
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#4f46e5'
                });
            @endif

            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    html: '<ul class="text-left text-sm list-disc pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                    confirmButtonColor: '#4f46e5'
                });
            @endif

            // Global Confirmation Interceptor for forms with .confirm-action
            document.addEventListener('submit', function (e) {
                const form = e.target;
                if (form.classList.contains('confirm-action')) {
                    e.preventDefault();
                    const title = form.getAttribute('data-title') || 'Apakah Anda yakin?';
                    const text = form.getAttribute('data-text') || 'Tindakan ini tidak dapat dibatalkan.';
                    const confirmBtnText = form.getAttribute('data-confirm-text') || 'Ya, Lanjutkan';
                    const confirmBtnColor = form.getAttribute('data-confirm-color') || '#4f46e5';

                    Swal.fire({
                        title: title,
                        text: text,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: confirmBtnColor,
                        cancelButtonColor: '#9ca3af',
                        confirmButtonText: confirmBtnText,
                        cancelButtonText: 'Batal',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.classList.remove('confirm-action');
                            form.submit();
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>
