<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Dashboard Peserta') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Selamat datang, {{ Auth::user()->name }}. Lihat status pendaftaran dan temukan event menarik lainnya.</p>
            </div>
            <div>
                <a href="{{ route('events.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 shadow-sm transition">
                    <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Jelajah Event Baru
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Registered -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center">
                    <div class="p-3.5 rounded-xl bg-indigo-50 text-indigo-600 me-4">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Terdaftar</div>
                        <div class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total_registrations'] }}</div>
                    </div>
                </div>

                <!-- Approved -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center">
                    <div class="p-3.5 rounded-xl bg-emerald-50 text-emerald-600 me-4">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Disetujui (Approved)</div>
                        <div class="text-2xl font-bold text-emerald-600 mt-1">{{ $stats['approved'] }}</div>
                    </div>
                </div>

                <!-- Pending -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center">
                    <div class="p-3.5 rounded-xl bg-amber-50 text-amber-600 me-4">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Menunggu (Pending)</div>
                        <div class="text-2xl font-bold text-amber-600 mt-1">{{ $stats['pending'] }}</div>
                    </div>
                </div>

                <!-- Rejected -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center">
                    <div class="p-3.5 rounded-xl bg-rose-50 text-rose-600 me-4">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Ditolak (Rejected)</div>
                        <div class="text-2xl font-bold text-rose-600 mt-1">{{ $stats['rejected'] }}</div>
                    </div>
                </div>
            </div>

            <!-- Two Columns -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- My Recent Registrations -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4 pb-2 border-b border-gray-100">
                        <h3 class="font-bold text-base text-gray-900">Pendaftaran Terakhir Saya</h3>
                        <a href="{{ route('my.registrations') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">Lihat Semua →</a>
                    </div>

                    <div class="divide-y divide-gray-100">
                        @forelse ($myRegistrations as $reg)
                            @php
                                $statusBadge = [
                                    'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
                                    'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                ];
                            @endphp
                            <div class="py-3 flex items-center justify-between">
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900 hover:text-indigo-600">
                                        <a href="{{ route('events.show', $reg->event) }}">{{ $reg->event->title }}</a>
                                    </h4>
                                    <div class="text-xs text-gray-500 mt-0.5">
                                        {{ $reg->event->category->name }} • {{ $reg->event->start_date->translatedFormat('d M Y, H:i') }}
                                    </div>
                                </div>
                                <div class="text-right shrink-0 ps-3">
                                    <span class="inline-flex px-2.5 py-0.5 text-xs font-semibold rounded-full border {{ $statusBadge[$reg->status] ?? 'bg-gray-100' }}">
                                        {{ ucfirst($reg->status) }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="py-6 text-center text-sm text-gray-500">Anda belum mendaftar kegiatan apapun.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Upcoming Events you might like -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4 pb-2 border-b border-gray-100">
                        <h3 class="font-bold text-base text-gray-900">Event Rekomendasi</h3>
                        <a href="{{ route('events.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">Semua Event →</a>
                    </div>

                    <div class="divide-y divide-gray-100">
                        @forelse ($upcomingEvents as $event)
                            <div class="py-3 flex items-center justify-between">
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900 hover:text-indigo-600">
                                        <a href="{{ route('events.show', $event) }}">{{ $event->title }}</a>
                                    </h4>
                                    <div class="text-xs text-gray-500 mt-0.5">
                                        {{ $event->category->name }} • Sisa Kuota: {{ $event->remaining_quota }}
                                    </div>
                                </div>
                                <div class="text-right shrink-0 ps-3">
                                    <a href="{{ route('events.show', $event) }}" class="inline-flex items-center px-3 py-1 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-lg transition">
                                        Daftar
                                    </a>
                                </div>
                            </div>
                        @empty
                            <p class="py-6 text-center text-sm text-gray-500">Tidak ada rekomendasi event saat ini.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
