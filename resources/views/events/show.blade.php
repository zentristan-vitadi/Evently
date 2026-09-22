<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2 text-sm text-gray-500">
            <a href="{{ route('events.index') }}" class="hover:text-indigo-600 transition">Jelajah Event</a>
            <span>/</span>
            <span class="text-gray-900 font-medium truncate">{{ $event->title }}</span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content (2 cols) -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Event Main Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="p-6 sm:p-8">
                            <div class="flex flex-wrap items-center gap-2 mb-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                    {{ $event->category->name }}
                                </span>
                                @php
                                    $statusStyles = [
                                        'upcoming' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'ongoing' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'completed' => 'bg-gray-100 text-gray-700 border-gray-200',
                                        'draft' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'cancelled' => 'bg-rose-50 text-rose-700 border-rose-200',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $statusStyles[$event->status] ?? 'bg-gray-50 text-gray-700' }}">
                                    Status: {{ ucfirst($event->status) }}
                                </span>
                            </div>

                            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-6">
                                {{ $event->title }}
                            </h1>

                            <div class="prose max-w-none text-gray-700 leading-relaxed space-y-4">
                                <h3 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-2">Deskripsi Kegiatan</h3>
                                <p class="whitespace-pre-line text-base">{{ $event->description }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Event Schedule & Location Details -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">Informasi Pelaksanaan</h3>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="flex items-start">
                                <div class="p-3 bg-indigo-50 text-indigo-600 rounded-lg me-4">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-xs font-semibold uppercase tracking-wider text-gray-500">Waktu Mulai</h4>
                                    <p class="text-base font-semibold text-gray-900 mt-0.5">{{ $event->start_date->translatedFormat('l, d F Y') }}</p>
                                    <p class="text-sm text-gray-600">Pukul {{ $event->start_date->format('H:i') }} WIB</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="p-3 bg-indigo-50 text-indigo-600 rounded-lg me-4">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-xs font-semibold uppercase tracking-wider text-gray-500">Waktu Selesai</h4>
                                    <p class="text-base font-semibold text-gray-900 mt-0.5">{{ $event->end_date->translatedFormat('l, d F Y') }}</p>
                                    <p class="text-sm text-gray-600">Pukul {{ $event->end_date->format('H:i') }} WIB</p>
                                </div>
                            </div>

                            <div class="flex items-start sm:col-span-2">
                                <div class="p-3 bg-indigo-50 text-indigo-600 rounded-lg me-4 shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-xs font-semibold uppercase tracking-wider text-gray-500">Lokasi / Tempat</h4>
                                    <p class="text-base font-semibold text-gray-900 mt-0.5">{{ $event->location }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar (1 col) -->
                <div class="space-y-6">
                    <!-- Registration Action Box -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">Pendaftaran</h3>

                        <!-- Quota progress -->
                        <div class="mb-6">
                            @php
                                $percent = $event->capacity > 0 ? min(100, round(($event->approved_count / $event->capacity) * 100)) : 100;
                            @endphp
                            <div class="flex justify-between text-sm font-medium text-gray-700 mb-2">
                                <span>Kapasitas Terisi</span>
                                <span class="font-bold text-indigo-600">{{ $event->approved_count }} / {{ $event->capacity }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                                <div class="h-3 rounded-full {{ $percent >= 100 ? 'bg-rose-500' : ($percent >= 75 ? 'bg-amber-500' : 'bg-indigo-600') }}" style="width: {{ $percent }}%"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-2 text-right">
                                Sisa kuota: <span class="font-bold text-gray-800">{{ $event->remaining_quota }} peserta</span>
                            </p>
                        </div>

                        <!-- User registration status / Action buttons -->
                        @guest
                            <div class="p-4 bg-gray-50 rounded-lg text-center">
                                <p class="text-sm text-gray-600 mb-3">Silakan login terlebih dahulu untuk mendaftar ke kegiatan ini.</p>
                                <a href="{{ route('events.register', $event) }}" class="block w-full text-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-sm transition text-sm">
                                    Login untuk Mendaftar
                                </a>
                            </div>
                        @else
                            @if (Auth::user()->isPeserta())
                                @if ($userRegistration)
                                    <div class="p-4 rounded-lg border {{ $userRegistration->status === 'approved' ? 'bg-emerald-50 border-emerald-200' : ($userRegistration->status === 'rejected' ? 'bg-rose-50 border-rose-200' : 'bg-amber-50 border-amber-200') }}">
                                        <div class="flex items-center mb-2">
                                            @if ($userRegistration->status === 'approved')
                                                <svg class="w-5 h-5 text-emerald-600 me-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                <span class="font-bold text-emerald-800 text-sm">Pendaftaran Disetujui</span>
                                            @elseif ($userRegistration->status === 'rejected')
                                                <svg class="w-5 h-5 text-rose-600 me-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                </svg>
                                                <span class="font-bold text-rose-800 text-sm">Pendaftaran Ditolak</span>
                                            @else
                                                <svg class="w-5 h-5 text-amber-600 me-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                </svg>
                                                <span class="font-bold text-amber-800 text-sm">Menunggu Persetujuan</span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-gray-600">
                                            Terdaftar pada: {{ $userRegistration->registered_at ? $userRegistration->registered_at->translatedFormat('d M Y, H:i') : $userRegistration->created_at->translatedFormat('d M Y, H:i') }} WIB
                                        </p>

                                        @if ($userRegistration->status === 'pending')
                                            <form method="POST" action="{{ route('my.registrations.cancel', $userRegistration) }}" class="mt-4 confirm-action" data-title="Batalkan Pendaftaran?" data-text="Apakah Anda yakin ingin membatalkan pendaftaran event ini?">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full text-center px-3 py-1.5 bg-rose-100 hover:bg-rose-200 text-rose-700 text-xs font-semibold rounded transition">
                                                    Batalkan Pendaftaran
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @else
                                    @if ($event->canAcceptRegistrations())
                                        <a href="{{ route('events.register', $event) }}" class="block w-full text-center px-4 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md hover:shadow transition">
                                            Pesan Tiket / Daftar Sekarang
                                        </a>
                                    @elseif ($event->isFull())
                                        <div class="p-3 bg-rose-50 border border-rose-200 text-rose-700 text-sm text-center rounded-lg font-medium">
                                            Maaf, kuota pendaftaran event ini sudah penuh.
                                        </div>
                                    @else
                                        <div class="p-3 bg-gray-50 border border-gray-200 text-gray-600 text-sm text-center rounded-lg font-medium">
                                            Pendaftaran untuk event ini sedang ditutup (Status: {{ ucfirst($event->status) }}).
                                        </div>
                                    @endif
                                @endif
                            @else
                                <div class="space-y-2">
                                    <p class="text-xs text-gray-500 mb-2">Aksi Pengelola ({{ ucfirst(Auth::user()->role) }}):</p>
                                    <a href="{{ route('manage.registrations.index', ['event_id' => $event->id]) }}" class="block w-full text-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-sm transition text-sm">
                                        Kelola Peserta Event
                                    </a>
                                    @if (Auth::user()->isAdmin() || (Auth::user()->isPanitia() && $event->organizer_id === Auth::id()))
                                        <a href="{{ route('manage.events.edit', $event) }}" class="block w-full text-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold rounded-lg transition text-sm">
                                            Edit Event
                                        </a>
                                    @endif
                                </div>
                            @endif
                        @endguest
                    </div>

                    <!-- Organizer Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-3">Penyelenggara / Panitia</h3>
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm">
                                {{ strtoupper(substr($event->organizer->name, 0, 2)) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900">{{ $event->organizer->name }}</p>
                                <p class="text-xs text-gray-500">{{ $event->organizer->email }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
