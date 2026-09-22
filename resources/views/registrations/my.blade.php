<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Riwayat & Status Pendaftaran Saya') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Pantau status persetujuan pendaftaran kegiatan sekolah yang Anda ikuti.</p>
            </div>
            <div>
                <a href="{{ route('events.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 shadow-sm transition">
                    <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Cari Event Lainnya
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Filter Bar -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
                <form method="GET" action="{{ route('my.registrations') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="sm:col-span-2">
                        <label for="search" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Cari Judul Event</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Cari nama kegiatan..." class="block w-full py-2 px-3 text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label for="status" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Status Pendaftaran</label>
                        <div class="flex gap-2">
                            <select name="status" id="status" class="block w-full py-2 px-3 text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved (Disetujui)</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected (Ditolak)</option>
                            </select>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm font-medium transition shadow-sm">
                                Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Registrations List -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                        <thead class="bg-gray-50 text-gray-600 font-semibold uppercase text-xs tracking-wider">
                            <tr>
                                <th scope="col" class="px-6 py-3.5">Kegiatan / Event</th>
                                <th scope="col" class="px-6 py-3.5">Kategori & Lokasi</th>
                                <th scope="col" class="px-6 py-3.5">Waktu Pelaksanaan</th>
                                <th scope="col" class="px-6 py-3.5">Tanggal Daftar</th>
                                <th scope="col" class="px-6 py-3.5">Status</th>
                                <th scope="col" class="px-6 py-3.5 text-right">Aksi</th>
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
                                        <div class="font-bold text-gray-900 mb-0.5">
                                            <a href="{{ route('events.show', $reg->event) }}" class="hover:text-indigo-600 transition">
                                                {{ $reg->event->title }}
                                            </a>
                                        </div>
                                        <div class="text-xs text-gray-500">Panitia: {{ $reg->event->organizer->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-gray-600">
                                        <div class="font-medium text-gray-900 mb-0.5">{{ $reg->event->category->name }}</div>
                                        <div class="text-gray-500 truncate max-w-xs">{{ $reg->event->location }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-gray-700">
                                        {{ $reg->event->start_date->translatedFormat('d M Y, H:i') }} WIB
                                    </td>
                                    <td class="px-6 py-4 text-xs text-gray-500">
                                        {{ $reg->registered_at ? $reg->registered_at->translatedFormat('d M Y H:i') : $reg->created_at->translatedFormat('d M Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $statusBadge[$reg->status] ?? 'bg-gray-50 text-gray-700' }}">
                                            @if($reg->status === 'approved')
                                                Disetujui
                                            @elseif($reg->status === 'rejected')
                                                Ditolak
                                            @else
                                                Menunggu Persetujuan
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right whitespace-nowrap space-x-2">
                                        <a href="{{ route('events.show', $reg->event) }}" class="inline-flex items-center px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded text-xs font-semibold transition">
                                            Detail
                                        </a>

                                        @if ($reg->status === 'pending')
                                            <form method="POST" action="{{ route('my.registrations.cancel', $reg) }}" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pendaftaran ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center px-2.5 py-1 bg-rose-50 text-rose-700 hover:bg-rose-100 rounded text-xs font-semibold transition">
                                                    Batalkan
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                        Anda belum mendaftar ke event manapun.
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
