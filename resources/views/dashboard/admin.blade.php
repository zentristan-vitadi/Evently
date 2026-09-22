<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Dashboard Administrator') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Ringkasan statistik sistem pengelolaan event sekolah.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('manage.events.create') }}" class="inline-flex items-center px-3 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 shadow-sm transition">
                    + Event Baru
                </a>
                <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center px-3 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 shadow-sm transition">
                    + Kategori
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Events -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center">
                    <div class="p-3.5 rounded-xl bg-indigo-50 text-indigo-600 me-4">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Event</div>
                        <div class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total_events'] }}</div>
                    </div>
                </div>

                <!-- Total Categories -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center">
                    <div class="p-3.5 rounded-xl bg-purple-50 text-purple-600 me-4">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Kategori</div>
                        <div class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total_categories'] }}</div>
                    </div>
                </div>

                <!-- Total Users -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center">
                    <div class="p-3.5 rounded-xl bg-blue-50 text-blue-600 me-4">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total User</div>
                        <div class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total_users'] }}</div>
                        <div class="text-xs text-gray-400 mt-0.5">{{ $stats['users_panitia'] }} Panitia, {{ $stats['users_peserta'] }} Peserta</div>
                    </div>
                </div>

                <!-- Total Registrations -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center">
                    <div class="p-3.5 rounded-xl bg-emerald-50 text-emerald-600 me-4">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Pendaftaran</div>
                        <div class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total_registrations'] }}</div>
                        <div class="text-xs text-amber-600 font-medium mt-0.5">{{ $stats['pending_registrations'] }} pending approval</div>
                    </div>
                </div>
            </div>

            <!-- Two Column Overview -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Events -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4 pb-2 border-b border-gray-100">
                        <h3 class="font-bold text-base text-gray-900">Event Terbaru</h3>
                        <a href="{{ route('manage.events.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">Lihat Semua →</a>
                    </div>

                    <div class="divide-y divide-gray-100">
                        @forelse ($recentEvents as $event)
                            <div class="py-3 flex items-center justify-between">
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900 hover:text-indigo-600">
                                        <a href="{{ route('events.show', $event) }}">{{ $event->title }}</a>
                                    </h4>
                                    <div class="text-xs text-gray-500 mt-0.5">
                                        <span class="font-medium text-gray-700">{{ $event->category->name }}</span> • 
                                        Oleh: {{ $event->organizer->name }} • 
                                        {{ $event->start_date->translatedFormat('d M Y') }}
                                    </div>
                                </div>
                                <div class="text-right shrink-0 ps-3">
                                    <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                        {{ ucfirst($event->status) }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="py-6 text-center text-sm text-gray-500">Belum ada event.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Registrations -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4 pb-2 border-b border-gray-100">
                        <h3 class="font-bold text-base text-gray-900">Pendaftaran Terbaru</h3>
                        <a href="{{ route('manage.registrations.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">Kelola Peserta →</a>
                    </div>

                    <div class="divide-y divide-gray-100">
                        @forelse ($recentRegistrations as $reg)
                            @php
                                $badge = [
                                    'approved' => 'bg-emerald-50 text-emerald-700',
                                    'rejected' => 'bg-rose-50 text-rose-700',
                                    'pending' => 'bg-amber-50 text-amber-700',
                                ];
                            @endphp
                            <div class="py-3 flex items-center justify-between">
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900">{{ $reg->user->name }}</h4>
                                    <div class="text-xs text-gray-500 mt-0.5">
                                        {{ $reg->event->title }} • {{ $reg->registered_at ? $reg->registered_at->diffForHumans() : $reg->created_at->diffForHumans() }}
                                    </div>
                                </div>
                                <div class="text-right shrink-0 ps-3">
                                    <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full {{ $badge[$reg->status] ?? 'bg-gray-100' }}">
                                        {{ ucfirst($reg->status) }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="py-6 text-center text-sm text-gray-500">Belum ada pendaftaran.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
