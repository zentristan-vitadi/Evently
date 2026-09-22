<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2 text-sm text-gray-500">
            <a href="{{ route('manage.events.index') }}" class="hover:text-indigo-600 transition">Kelola Event</a>
            <span>/</span>
            <span class="text-gray-900 font-medium">Buat Event Baru</span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8">
                <div class="mb-6 pb-4 border-b border-gray-100">
                    <h2 class="text-xl font-bold text-gray-900">Formulir Tambah Event</h2>
                    <p class="text-sm text-gray-500 mt-1">Lengkapi informasi berikut untuk mempublikasikan event kegiatan baru.</p>
                </div>

                <form method="POST" action="{{ route('manage.events.store') }}" class="space-y-6">
                    @csrf

                    <!-- Title -->
                    <div>
                        <x-input-label for="title" value="Judul Event *" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" required autofocus placeholder="Contoh: Workshop UI/UX Design 2026" />
                        <x-input-error class="mt-2" :messages="$errors->get('title')" />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Category -->
                        <div>
                            <x-input-label for="category_id" value="Kategori Event *" />
                            <select id="category_id" name="category_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('category_id')" />
                        </div>

                        <!-- Status -->
                        <div>
                            <x-input-label for="status" value="Status Event *" />
                            <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" required>
                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft (Belum Dipublikasikan)</option>
                                <option value="upcoming" {{ old('status', 'upcoming') == 'upcoming' ? 'selected' : '' }}>Upcoming (Akan Datang)</option>
                                <option value="ongoing" {{ old('status') == 'ongoing' ? 'selected' : '' }}>Ongoing (Sedang Berlangsung)</option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed (Selesai)</option>
                                <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled (Dibatalkan)</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('status')" />
                        </div>
                    </div>

                    @if (Auth::user()->isAdmin())
                        <!-- Organizer (Admin only) -->
                        <div>
                            <x-input-label for="organizer_id" value="Panitia Penanggung Jawab (Organizer) *" />
                            <select id="organizer_id" name="organizer_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" required>
                                <option value="">-- Pilih Penanggung Jawab --</option>
                                @foreach ($organizers as $org)
                                    <option value="{{ $org->id }}" {{ old('organizer_id', Auth::id()) == $org->id ? 'selected' : '' }}>
                                        {{ $org->name }} ({{ $org->email }}) - [{{ ucfirst($org->role) }}]
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('organizer_id')" />
                        </div>
                    @endif

                    <!-- Description -->
                    <div>
                        <x-input-label for="description" value="Deskripsi Lengkap Event *" />
                        <textarea id="description" name="description" rows="5" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" required placeholder="Tuliskan detail acara, syarat peserta, agenda, dan manfaat kegiatan...">{{ old('description') }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('description')" />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Location -->
                        <div>
                            <x-input-label for="location" value="Lokasi / Tempat Pelaksanaan *" />
                            <x-text-input id="location" name="location" type="text" class="mt-1 block w-full" :value="old('location')" required placeholder="Contoh: Lab Komputer RPL 1 / Aula Utama" />
                            <x-input-error class="mt-2" :messages="$errors->get('location')" />
                        </div>

                        <!-- Capacity -->
                        <div>
                            <x-input-label for="capacity" value="Kapasitas Maksimal Peserta (Orang) *" />
                            <x-text-input id="capacity" name="capacity" type="number" min="1" class="mt-1 block w-full" :value="old('capacity', 50)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('capacity')" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Start Date -->
                        <div>
                            <x-input-label for="start_date" value="Waktu Mulai *" />
                            <x-text-input id="start_date" name="start_date" type="datetime-local" class="mt-1 block w-full" :value="old('start_date')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('start_date')" />
                        </div>

                        <!-- End Date -->
                        <div>
                            <x-input-label for="end_date" value="Waktu Selesai *" />
                            <x-text-input id="end_date" name="end_date" type="datetime-local" class="mt-1 block w-full" :value="old('end_date')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('end_date')" />
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-100">
                        <a href="{{ route('manage.events.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-indigo-700 shadow-sm transition">
                            Simpan & Publikasikan Event
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
