@php
$user = Auth::user();
$role = $user ? $user->role : 'guest';

$menuSections = [];

if ($role === 'admin') {
$menuSections = [
[
'label' => 'Utama',
'items' => [
[
'title' => 'Dashboard',
'route' => 'dashboard',
'active' => request()->routeIs('dashboard'),
'icon' => '
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />',
],
[
'title' => 'Jelajah Event',
'route' => 'events.index',
'active' => request()->routeIs('events.index') || request()->routeIs('events.show'),
'icon' => '
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />',
],
],
],
[
'label' => 'Manajemen',
'items' => [
[
'title' => 'Kelola Event',
'route' => 'manage.events.index',
'active' => request()->routeIs('manage.events.*'),
'icon' => '
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />',
],
[
'title' => 'Kategori',
'route' => 'admin.categories.index',
'active' => request()->routeIs('admin.categories.*'),
'icon' => '
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />',
],
[
'title' => 'User & Role',
'route' => 'admin.users.index',
'active' => request()->routeIs('admin.users.*'),
'icon' => '
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />',
],
],
],
[
'label' => 'Peserta',
'items' => [
[
'title' => 'Kelola Peserta',
'route' => 'manage.registrations.index',
'active' => request()->routeIs('manage.registrations.*'),
'icon' => '
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />',
],
],
],
];
} elseif ($role === 'panitia') {
$menuSections = [
[
'label' => 'Utama',
'items' => [
[
'title' => 'Dashboard',
'route' => 'dashboard',
'active' => request()->routeIs('dashboard'),
'icon' => '
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />',
],
[
'title' => 'Jelajah Event',
'route' => 'events.index',
'active' => request()->routeIs('events.index') || request()->routeIs('events.show'),
'icon' => '
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />',
],
],
],
[
'label' => 'Event Saya',
'items' => [
[
'title' => 'Kelola Event',
'route' => 'manage.events.index',
'active' => request()->routeIs('manage.events.*'),
'icon' => '
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />',
],
],
],
[
'label' => 'Peserta',
'items' => [
[
'title' => 'Kelola Peserta & Approval',
'route' => 'manage.registrations.index',
'active' => request()->routeIs('manage.registrations.*'),
'icon' => '
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />',
],
],
],
];
} elseif ($role === 'peserta') {
$menuSections = [
[
'label' => 'Utama',
'items' => [
[
'title' => 'Dashboard',
'route' => 'dashboard',
'active' => request()->routeIs('dashboard'),
'icon' => '
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />',
],
[
'title' => 'Jelajah Event',
'route' => 'events.index',
'active' => request()->routeIs('events.index') || request()->routeIs('events.show'),
'icon' => '
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />',
],
],
],
[
'label' => 'Tiket Saya',
'items' => [
[
'title' => 'Riwayat Pendaftaran',
'route' => 'my.registrations',
'active' => request()->routeIs('my.registrations*'),
'icon' => '
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />',
],
],
],
];
} else {
$menuSections = [
[
'label' => 'Utama',
'items' => [
[
'title' => 'Jelajah Event',
'route' => 'events.index',
'active' => request()->routeIs('events.index') || request()->routeIs('events.show'),
'icon' => '
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />',
],
],
],
];
}
@endphp

<aside class="w-64 bg-white border-r border-gray-200 flex flex-col justify-between shrink-0 min-h-screen">
    <div>
        <!-- Brand Header -->
        <div class="h-16 px-6 border-b border-gray-200 flex items-center justify-between">
            <a href="{{ route('events.index') }}" class="flex items-center space-x-2.5">
                <img src="{{ asset('images/SingleLogo.png') }}" alt="Logo" class="w-10 h-10">
                <span class="font-bold text-xl text-gray-800 tracking-tight">Evently<span class="text-indigo-600"></span>
            </a>
        </div>

        <!-- Categorized Nav Menu -->
        <nav class="p-4 space-y-6">
            @foreach ($menuSections as $section)
            <div>
                <h3 class="px-3 text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2">
                    {{ $section['label'] }}
                </h3>

                <div class="space-y-1">
                    @foreach ($section['items'] as $item)
                    <a href="{{ route($item['route']) }}"
                        class="flex items-center px-3 py-2 text-sm font-medium rounded-xl transition {{ $item['active'] ? 'bg-indigo-50 text-indigo-700 font-semibold shadow-xs' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 me-3 shrink-0 {{ $item['active'] ? 'text-indigo-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            {!! $item['icon'] !!}
                        </svg>
                        <span>{{ $item['title'] }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
            @endforeach
        </nav>
    </div>

    <!-- User Mini Profile / Guest Info Bottom -->
    <div class="p-4 border-t border-gray-200">
        @auth
        <div class="flex items-center justify-between p-2 rounded-xl bg-gray-50 border border-gray-100">
            <div class="flex items-center space-x-3 overflow-hidden">
                <div class="w-9 h-9 rounded-full bg-indigo-100 text-indigo-700 font-bold flex items-center justify-center text-xs shrink-0">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
                <div class="truncate">
                    <div class="text-xs font-bold text-gray-900 truncate">{{ Auth::user()->name }}</div>
                    <div class="text-[10px] text-gray-500 capitalize">{{ Auth::user()->role }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" title="Log Out" class="text-gray-400 hover:text-rose-600 p-1.5 rounded-lg hover:bg-rose-50 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </button>
            </form>
        </div>
        @else
        <div class="space-y-2">
            <a href="{{ route('login') }}" class="block w-full text-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl text-xs transition shadow-sm">
                Log In
            </a>
            <a href="{{ route('register') }}" class="block w-full text-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-xs transition">
                Daftar Akun
            </a>
        </div>
        @endauth
    </div>
</aside>