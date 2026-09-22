<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Manajemen User & Role') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Kelola data seluruh akun pengguna dan tentukan hak akses peran (Admin, Panitia, Peserta).</p>
            </div>
            <div>
                <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 shadow-sm transition">
                    <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    Tambah User
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Filter Bar -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
                <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="sm:col-span-2">
                        <label for="search" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Cari Nama / Email</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Cari pengguna berdasarkan nama atau email..." class="block w-full py-2 px-3 text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label for="role" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Role</label>
                        <div class="flex gap-2">
                            <select name="role" id="role" class="block w-full py-2 px-3 text-sm border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Semua Role</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="panitia" {{ request('role') == 'panitia' ? 'selected' : '' }}>Panitia</option>
                                <option value="peserta" {{ request('role') == 'peserta' ? 'selected' : '' }}>Peserta</option>
                            </select>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm font-medium transition shadow-sm">
                                Filter
                            </button>
                            @if (request()->hasAny(['search', 'role']))
                                <a href="{{ route('admin.users.index') }}" class="py-2 px-3 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 text-sm font-medium transition">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                        <thead class="bg-gray-50 text-gray-600 font-semibold uppercase text-xs tracking-wider">
                            <tr>
                                <th scope="col" class="px-6 py-3.5">Nama & Email</th>
                                <th scope="col" class="px-6 py-3.5">Peran / Role</th>
                                <th scope="col" class="px-6 py-3.5">Event Dibuat</th>
                                <th scope="col" class="px-6 py-3.5">Pendaftaran Diikuti</th>
                                <th scope="col" class="px-6 py-3.5">Terdaftar Sejak</th>
                                <th scope="col" class="px-6 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($users as $u)
                                @php
                                    $roleBadge = [
                                        'admin' => 'bg-purple-100 text-purple-800 border-purple-200',
                                        'panitia' => 'bg-blue-100 text-blue-800 border-blue-200',
                                        'peserta' => 'bg-green-100 text-green-800 border-green-200',
                                    ];
                                @endphp
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900 flex items-center">
                                            {{ $u->name }}
                                            @if ($u->id === Auth::id())
                                                <span class="ms-2 px-1.5 py-0.5 bg-indigo-50 text-indigo-700 text-xs rounded font-normal">Anda</span>
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-500">{{ $u->email }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $roleBadge[$u->role] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($u->role) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-gray-600">
                                        {{ $u->events_count }} event
                                    </td>
                                    <td class="px-6 py-4 text-xs text-gray-600">
                                        {{ $u->registrations_count }} kegiatan
                                    </td>
                                    <td class="px-6 py-4 text-xs text-gray-500">
                                        {{ $u->created_at->translatedFormat('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-right whitespace-nowrap space-x-2">
                                        <a href="{{ route('admin.users.edit', $u) }}" class="inline-flex items-center px-2.5 py-1 bg-amber-50 text-amber-700 hover:bg-amber-100 rounded text-xs font-semibold transition">
                                            Edit
                                        </a>

                                        @if ($u->id !== Auth::id())
                                            <form method="POST" action="{{ route('admin.users.destroy', $u) }}" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
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
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                        Tidak ada data user yang sesuai.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($users->hasPages())
                    <div class="p-4 border-t border-gray-200">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
