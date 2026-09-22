<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Kelola Event') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    {{ Auth::user()->isAdmin() ? 'Manajemen seluruh event lintas panitia dan penyelenggara sekolah.' : 'Manajemen event yang Anda selenggarakan.' }}
                </p>
            </div>
            <div>
                <a href="{{ route('manage.events.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 shadow-sm transition">
                    <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Event
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Filter Form -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
                <form method="GET" action="{{ route('manage.events.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label for="search" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Cari Judul / Lokasi</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Ketik kata kunci..." class="block w-full py-2 px-3 text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

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

                    <div>
                        <label for="status" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Status</label>
                        <select name="status" id="status" class="block w-full py-2 px-3 text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Semua Status</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                            <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit" class="w-full py-2 px-4 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm font-medium transition shadow-sm">
                            Terapkan Filter
                        </button>
                        @if (request()->hasAny(['search', 'category_id', 'status', 'organizer_id']))
                            <a href="{{ route('manage.events.index') }}" class="py-2 px-3 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 text-sm font-medium transition">
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
                                <th scope="col" class="px-6 py-3.5">Event & Kategori</th>
                                @if (Auth::user()->isAdmin())
                                    <th scope="col" class="px-6 py-3.5">Panitia</th>
                                @endif
                                <th scope="col" class="px-6 py-3.5">Jadwal & Lokasi</th>
                                <th scope="col" class="px-6 py-3.5">Kapasitas / Peserta</th>
                                <th scope="col" class="px-6 py-3.5">Status</th>
                                <th scope="col" class="px-6 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($events as $event)
                                @php
                                    $statusStyles = [
                                        'upcoming' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'ongoing' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'completed' => 'bg-gray-100 text-gray-700 border-gray-200',
                                        'draft' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'cancelled' => 'bg-rose-50 text-rose-700 border-rose-200',
                                    ];
                                @endphp
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900 mb-1">
                                            <a href="{{ route('events.show', $event) }}" class="hover:text-indigo-600 transition">
                                                {{ $event->title }}
                                            </a>
                                        </div>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-50 text-indigo-700">
                                            {{ $event->category->name }}
                                        </span>
                                    </td>
                                    @if (Auth::user()->isAdmin())
                                        <td class="px-6 py-4 text-xs text-gray-700">
                                            <div class="font-medium">{{ $event->organizer->name }}</div>
                                            <div class="text-gray-400">{{ $event->organizer->email }}</div>
                                        </td>
                                    @endif
                                    <td class="px-6 py-4 text-xs text-gray-600 space-y-1">
                                        <div><span class="font-medium text-gray-800">Mulai:</span> {{ $event->start_date->translatedFormat('d M Y H:i') }}</div>
                                        <div class="text-gray-500 truncate max-w-xs">{{ $event->location }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-xs">
                                        <div class="font-semibold text-gray-800">
                                            Approved: {{ $event->approved_registrations_count }} / {{ $event->capacity }}
                                        </div>
                                        @if ($event->pending_registrations_count > 0)
                                            <div class="text-amber-600 font-medium">
                                                {{ $event->pending_registrations_count }} pending approval
                                            </div>
                                        @else
                                            <div class="text-gray-400">Total: {{ $event->registrations_count }} pendaftar</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $statusStyles[$event->status] ?? 'bg-gray-50 text-gray-700' }}">
                                            {{ ucfirst($event->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                        <a href="{{ route('manage.registrations.index', ['event_id' => $event->id]) }}" class="inline-flex items-center px-2.5 py-1 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 rounded text-xs font-semibold transition" title="Kelola Peserta">
                                            Peserta ({{ $event->registrations_count }})
                                        </a>

                                        @if (Auth::user()->isAdmin() || (Auth::user()->isPanitia() && $event->organizer_id === Auth::id()))
                                            <a href="{{ route('manage.events.edit', $event) }}" class="inline-flex items-center px-2.5 py-1 bg-amber-50 text-amber-700 hover:bg-amber-100 rounded text-xs font-semibold transition">
                                                Edit
                                            </a>

                                            <form method="POST" action="{{ route('manage.events.destroy', $event) }}" class="inline-block confirm-action" data-title="Hapus Event?" data-text="Apakah Anda yakin ingin menghapus event ini beserta seluruh data pendaftarannya?" data-confirm-color="#e11d48">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center px-2.5 py-1 bg-rose-50 text-rose-700 hover:bg-rose-100 rounded text-xs font-semibold transition">
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ Auth::user()->isAdmin() ? '6' : '5' }}" class="px-6 py-12 text-center text-gray-500">
                                        Belum ada data event yang tersedia.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($events->hasPages())
                    <div class="p-4 border-t border-gray-200">
                        {{ $events->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
