<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Jelajah Event Sekitar') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Temukan berbagai kegiatan seperti seminar, workshop, dan lomba.</p>
            </div>
            @auth
                @if (Auth::user()->isPanitia() || Auth::user()->isAdmin())
                    <div>
                        <a href="{{ route('manage.events.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 shadow-sm transition">
                            <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Buat Event Baru
                        </a>
                    </div>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Search & Filter Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-8">
                <form method="GET" action="{{ route('events.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search Query -->
                    <div class="md:col-span-2">
                        <label for="search" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Pencarian Event</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Cari judul, deskripsi, atau lokasi..." class="block w-full pl-10 pr-3 py-2 text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <label for="category_id" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Kategori</label>
                        <select name="category_id" id="category_id" class="block w-full py-2 px-3 text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Semua Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status Filter & Actions -->
                    <div class="flex items-end gap-2">
                        <div class="flex-1">
                            <label for="status" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Status</label>
                            <select name="status" id="status" class="block w-full py-2 px-3 text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Semua Status</option>
                                <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                @auth
                                    @if(Auth::user()->isAdmin() || Auth::user()->isPanitia())
                                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    @endif
                                @endauth
                            </select>
                        </div>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm font-medium transition shadow-sm">
                            Filter
                        </button>
                        @if (request()->hasAny(['search', 'category_id', 'status']))
                            <a href="{{ route('events.index') }}" class="px-3 py-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 text-sm font-medium transition">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Event Grid -->
            @if ($events->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($events as $event)
                        @php
                            $statusStyles = [
                                'upcoming' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'ongoing' => 'bg-emerald-50 text-emerald-700 border-emerald-200 animate-pulse',
                                'completed' => 'bg-gray-100 text-gray-700 border-gray-200',
                                'draft' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'cancelled' => 'bg-rose-50 text-rose-700 border-rose-200',
                            ];
                            $percent = $event->capacity > 0 ? min(100, round(($event->approved_count / $event->capacity) * 100)) : 100;
                        @endphp
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition duration-200 flex flex-col justify-between overflow-hidden">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                        {{ $event->category->name }}
                                    </span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $statusStyles[$event->status] ?? 'bg-gray-50 text-gray-700' }}">
                                        {{ ucfirst($event->status) }}
                                    </span>
                                </div>

                                <h3 class="text-lg font-bold text-gray-900 line-clamp-2 hover:text-indigo-600 transition mb-2">
                                    <a href="{{ route('events.show', $event) }}">
                                        {{ $event->title }}
                                    </a>
                                </h3>

                                <p class="text-sm text-gray-600 line-clamp-3 mb-4">
                                    {{ $event->description }}
                                </p>

                                <div class="space-y-2 text-xs text-gray-500 border-t border-gray-100 pt-3">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-gray-400 me-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span>{{ $event->start_date->translatedFormat('d M Y, H:i') }} WIB</span>
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-gray-400 me-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <span class="truncate">{{ $event->location }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-gray-400 me-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        <span class="truncate">Oleh: {{ $event->organizer->name }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Quota Progress & Action -->
                            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                                <div class="mb-3">
                                    <div class="flex justify-between text-xs text-gray-600 font-medium mb-1">
                                        <span>Kuota Peserta</span>
                                        <span>{{ $event->approved_count }} / {{ $event->capacity }} (Sisa: {{ $event->remaining_quota }})</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                        <div class="h-2 rounded-full {{ $percent >= 100 ? 'bg-rose-500' : ($percent >= 75 ? 'bg-amber-500' : 'bg-indigo-600') }}" style="width: {{ $percent }}%"></div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <a href="{{ route('events.show', $event) }}" class="inline-flex items-center text-sm font-semibold text-indigo-600 hover:text-indigo-800">
                                        Lihat Detail
                                        <svg class="w-4 h-4 ms-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>

                                    @if ($event->canAcceptRegistrations())
                                        <span class="inline-flex items-center text-xs font-semibold text-emerald-700 bg-emerald-50 px-2 py-1 rounded">
                                            Pendaftaran Buka
                                        </span>
                                    @elseif ($event->isFull())
                                        <span class="inline-flex items-center text-xs font-semibold text-rose-700 bg-rose-50 px-2 py-1 rounded">
                                            Kuota Penuh
                                        </span>
                                    @else
                                        <span class="inline-flex items-center text-xs font-medium text-gray-500">
                                            Tutup
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $events->links() }}
                </div>
            @else
                <div class="text-center bg-white rounded-xl shadow-sm border border-gray-200 p-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada event ditemukan</h3>
                    <p class="mt-1 text-sm text-gray-500">Coba ubah kata kunci pencarian atau bersihkan filter yang dipilih.</p>
                    <div class="mt-6">
                        <a href="{{ route('events.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Lihat Semua Event
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
