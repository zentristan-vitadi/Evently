<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Dashboard Panitia Penyelenggara') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Pantau event yang Anda kelola dan proses pendaftaran calon peserta.</p>
            </div>
            <div>
                <a href="{{ route('manage.events.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 shadow-sm transition">
                    + Buat Event Baru
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- My Events -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center">
                    <div class="p-3.5 rounded-xl bg-indigo-50 text-indigo-600 me-4">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Event Saya</div>
                        <div class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total_events'] }}</div>
                    </div>
                </div>

                <!-- Total Registrations -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center">
                    <div class="p-3.5 rounded-xl bg-blue-50 text-blue-600 me-4">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Pendaftar</div>
                        <div class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total_registrations'] }}</div>
                    </div>
                </div>

                <!-- Pending Approval -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center">
                    <div class="p-3.5 rounded-xl bg-amber-50 text-amber-600 me-4">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Menunggu Persetujuan</div>
                        <div class="text-2xl font-bold text-amber-600 mt-1">{{ $stats['pending_registrations'] }}</div>
                    </div>
                </div>

                <!-- Approved Participants -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center">
                    <div class="p-3.5 rounded-xl bg-emerald-50 text-emerald-600 me-4">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Peserta Disetujui</div>
                        <div class="text-2xl font-bold text-emerald-600 mt-1">{{ $stats['approved_registrations'] }}</div>
                    </div>
                </div>
            </div>

            <!-- Pending Approvals Action Queue -->
            @if ($pendingApprovals->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-amber-200 overflow-hidden">
                    <div class="p-4 sm:px-6 bg-amber-50 border-b border-amber-200 flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <h3 class="font-bold text-amber-900 text-sm">Pendaftaran Memerlukan Verifikasi (Pending)</h3>
                        </div>
                        <a href="{{ route('manage.registrations.index', ['status' => 'pending']) }}" class="text-xs font-semibold text-amber-900 underline hover:text-amber-700">
                            Lihat Semua Pending →
                        </a>
                    </div>

                    <div class="divide-y divide-gray-100">
                        @foreach ($pendingApprovals as $reg)
                            <div class="p-4 sm:px-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 hover:bg-gray-50">
                                <div>
                                    <div class="font-bold text-gray-900">{{ $reg->user->name }} <span class="text-xs font-normal text-gray-500">({{ $reg->user->email }})</span></div>
                                    <div class="text-xs text-gray-600 mt-0.5">
                                        Mendaftar pada: <span class="font-medium text-gray-800">{{ $reg->event->title }}</span> • {{ $reg->registered_at ? $reg->registered_at->diffForHumans() : $reg->created_at->diffForHumans() }}
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <form method="POST" action="{{ route('manage.registrations.update-status', $reg) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold shadow-sm transition">
                                            Setujui
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('manage.registrations.update-status', $reg) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="px-3 py-1 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-semibold shadow-sm transition">
                                            Tolak
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- My Events List -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4 pb-2 border-b border-gray-100">
                    <h3 class="font-bold text-base text-gray-900">Daftar Event yang Dikelola</h3>
                    <a href="{{ route('manage.events.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">Kelola Semua Event →</a>
                </div>

                <div class="divide-y divide-gray-100">
                    @forelse ($myEvents as $event)
                        <div class="py-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div>
                                <h4 class="text-sm font-bold text-gray-900 hover:text-indigo-600">
                                    <a href="{{ route('events.show', $event) }}">{{ $event->title }}</a>
                                </h4>
                                <div class="text-xs text-gray-500 mt-1 flex flex-wrap items-center gap-2">
                                    <span class="inline-flex px-2 py-0.5 rounded bg-indigo-50 text-indigo-700 font-medium">{{ $event->category->name }}</span>
                                    <span>•</span>
                                    <span>{{ $event->start_date->translatedFormat('d M Y, H:i') }} WIB</span>
                                    <span>•</span>
                                    <span>Kapasitas: {{ $event->approved_registrations_count }} / {{ $event->capacity }}</span>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2 shrink-0">
                                <a href="{{ route('manage.registrations.index', ['event_id' => $event->id]) }}" class="px-3 py-1 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-lg transition">
                                    Peserta ({{ $event->registrations_count }})
                                </a>
                                <a href="{{ route('manage.events.edit', $event) }}" class="px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-lg transition">
                                    Edit
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="py-8 text-center text-sm text-gray-500">Anda belum membuat event.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
