<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Kelola Pendaftaran & Peserta Event') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Verifikasi, setujui (approve), atau tolak (reject) pendaftaran calon peserta kegiatan.
                </p>
            </div>
            @if ($selectedEvent)
                <div class="text-right">
                    <span class="inline-flex items-center px-3 py-1 bg-indigo-50 text-indigo-700 text-xs font-semibold rounded-lg border border-indigo-100">
                        Event: {{ $selectedEvent->title }}
                    </span>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Filter Bar -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
                <form method="GET" action="{{ route('manage.registrations.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Event Selector -->
                    <div>
                        <label for="event_id" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Pilih Event</label>
                        <select name="event_id" id="event_id" class="block w-full py-2 px-3 text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Semua Event</option>
                            @foreach ($events as $ev)
                                <option value="{{ $ev->id }}" {{ (request('event_id') == $ev->id || ($selectedEvent && $selectedEvent->id == $ev->id)) ? 'selected' : '' }}>
                                    {{ $ev->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Search Participant -->
                    <div>
                        <label for="search" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Nama / Email Peserta</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Cari nama atau email..." class="block w-full py-2 px-3 text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Status</label>
                        <select name="status" id="status" class="block w-full py-2 px-3 text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved (Disetujui)</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected (Ditolak)</option>
                        </select>
                    </div>

                    <!-- Submit & Reset -->
                    <div class="flex items-end gap-2">
                        <button type="submit" class="w-full py-2 px-4 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm font-medium transition shadow-sm">
                            Terapkan Filter
                        </button>
                        @if (request()->hasAny(['search', 'status', 'event_id']))
                            <a href="{{ route('manage.registrations.index') }}" class="py-2 px-3 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 text-sm font-medium transition">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                        <thead class="bg-gray-50 text-gray-600 font-semibold uppercase text-xs tracking-wider">
                            <tr>
                                <th scope="col" class="px-6 py-3.5">Peserta</th>
                                <th scope="col" class="px-6 py-3.5">Event Kegiatan</th>
                                <th scope="col" class="px-6 py-3.5">Waktu Daftar</th>
                                <th scope="col" class="px-6 py-3.5">Status Saat Ini</th>
                                <th scope="col" class="px-6 py-3.5 text-right">Aksi Persetujuan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($registrations as $reg)
                                @php
                                    $statusBadge = [
                                        'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
                                        'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    ];
                                @endphp
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900">{{ $reg->name ?? $reg->user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $reg->email ?? $reg->user->email }}</div>
                                        @if ($reg->phone_number)
                                            <div class="text-xs text-indigo-600 font-mono mt-0.5">📞 {{ $reg->phone_number }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900 mb-0.5">
                                            <a href="{{ route('events.show', $reg->event) }}" class="hover:text-indigo-600 transition">
                                                {{ $reg->event->title }}
                                            </a>
                                        </div>
                                        <div class="text-xs text-gray-500 flex items-center space-x-2">
                                            <span class="inline-flex px-2 py-0.5 rounded bg-gray-100 text-gray-700">{{ $reg->event->category->name }}</span>
                                            <span>Sisa Kuota: {{ $reg->event->remaining_quota }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-gray-500">
                                        {{ $reg->registered_at ? $reg->registered_at->translatedFormat('d M Y H:i') : $reg->created_at->translatedFormat('d M Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $statusBadge[$reg->status] ?? 'bg-gray-50 text-gray-700' }}">
                                            @if ($reg->status === 'approved')
                                                Approved
                                            @elseif ($reg->status === 'rejected')
                                                Rejected
                                            @else
                                                Pending
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right whitespace-nowrap space-x-1">
                                        @if ($reg->status !== 'approved')
                                            <form method="POST" action="{{ route('manage.registrations.update-status', $reg) }}" class="inline-block">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="inline-flex items-center px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded text-xs font-semibold shadow-sm transition">
                                                    <svg class="w-3.5 h-3.5 me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    Setujui
                                                </button>
                                            </form>
                                        @endif

                                        @if ($reg->status !== 'rejected')
                                            <form method="POST" action="{{ route('manage.registrations.update-status', $reg) }}" class="inline-block confirm-action" data-title="Tolak Pendaftaran?" data-text="Apakah Anda yakin ingin menolak pendaftaran calon peserta ini?" data-confirm-color="#e11d48">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="inline-flex items-center px-2.5 py-1 bg-rose-600 hover:bg-rose-700 text-white rounded text-xs font-semibold shadow-sm transition">
                                                    <svg class="w-3.5 h-3.5 me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                    Tolak
                                                </button>
                                            </form>
                                        @endif

                                        @if ($reg->status !== 'pending')
                                            <form method="POST" action="{{ route('manage.registrations.update-status', $reg) }}" class="inline-block">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="pending">
                                                <button type="submit" class="inline-flex items-center px-2 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded text-xs font-semibold transition" title="Reset ke pending">
                                                    Pending
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                        Tidak ada data pendaftaran yang sesuai dengan filter.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($registrations->hasPages())
                    <div class="p-4 border-t border-gray-200">
                        {{ $registrations->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
