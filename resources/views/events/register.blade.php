<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2 text-sm text-gray-500">
            <a href="{{ route('events.index') }}" class="hover:text-indigo-600 transition">Jelajah Event</a>
            <span>/</span>
            <a href="{{ route('events.show', $event) }}" class="hover:text-indigo-600 transition truncate max-w-xs">{{ $event->title }}</a>
            <span>/</span>
            <span class="text-gray-900 font-medium">Konfirmasi Pendaftaran</span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Event Summary (Left Column) -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100 mb-3">
                            {{ $event->category->name }}
                        </span>

                        <h3 class="text-lg font-bold text-gray-900 mb-4">
                            {{ $event->title }}
                        </h3>

                        <div class="space-y-3 text-xs text-gray-600 border-t border-gray-100 pt-4">
                            <div class="flex items-start">
                                <svg class="w-4 h-4 text-indigo-600 me-2 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <div>
                                    <span class="font-semibold text-gray-800">Tanggal:</span>
                                    <p>{{ $event->start_date->translatedFormat('l, d F Y') }}</p>
                                    <p class="text-gray-500">{{ $event->start_date->format('H:i') }} - {{ $event->end_date->format('H:i') }} WIB</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <svg class="w-4 h-4 text-indigo-600 me-2 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <div>
                                    <span class="font-semibold text-gray-800">Lokasi:</span>
                                    <p>{{ $event->location }}</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <svg class="w-4 h-4 text-indigo-600 me-2 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <div>
                                    <span class="font-semibold text-gray-800">Penyelenggara:</span>
                                    <p>{{ $event->organizer->name }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between text-xs">
                            <span class="text-gray-500">Sisa Kuota Tiket:</span>
                            <span class="font-bold text-indigo-600">{{ $event->remaining_quota }} peserta</span>
                        </div>
                    </div>
                </div>

                <!-- Registration Form (Right Column) -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8">
                        <div class="mb-6 pb-4 border-b border-gray-100">
                            <h2 class="text-xl font-bold text-gray-900">Konfirmasi Data Diri Peserta</h2>
                            <p class="text-xs text-gray-500 mt-1">Lengkapi atau sesuaikan informasi data diri Anda untuk penerbitan tiket event.</p>
                        </div>

                        <form method="POST" action="{{ route('events.register.store') }}" class="space-y-5">
                            @csrf
                            <input type="hidden" name="event_id" value="{{ $event->id }}">

                            <!-- Name -->
                            <div>
                                <x-input-label for="name" value="Nama Lengkap *" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus placeholder="Masukkan nama lengkap" />
                                <x-input-error class="mt-1" :messages="$errors->get('name')" />
                            </div>

                            <!-- Email -->
                            <div>
                                <x-input-label for="email" value="Alamat Email (Untuk Pengiriman Tiket) *" />
                                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required placeholder="nama@email.com" />
                                <p class="text-xs text-gray-400 mt-1">Bukti pendaftaran dan tiket akan dikirim ke alamat email ini.</p>
                                <x-input-error class="mt-1" :messages="$errors->get('email')" />
                            </div>

                            <!-- Phone Number -->
                            <div>
                                <x-input-label for="phone_number" value="Nomor WhatsApp / HP *" />
                                <x-text-input id="phone_number" name="phone_number" type="tel" class="mt-1 block w-full" :value="old('phone_number')" required placeholder="Contoh: 081234567890" />
                                <x-input-error class="mt-1" :messages="$errors->get('phone_number')" />
                            </div>

                            <!-- Info Box -->
                            <div class="p-4 bg-indigo-50/60 rounded-xl border border-indigo-100 text-xs text-indigo-900 space-y-1">
                                <div class="flex items-center font-semibold">
                                    <svg class="w-4 h-4 text-indigo-600 me-1.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                    Informasi Penting:
                                </div>
                                <p class="text-indigo-800">
                                    Setelah mengirimkan pendaftaran, status tiket Anda akan menjadi <strong>Pending (Menunggu Persetujuan Panitia)</strong>. Notifikasi konfirmasi tiket akan dikirimkan ke email Anda.
                                </p>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100">
                                <a href="{{ route('events.show', $event) }}" class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                                    Batal
                                </a>
                                <button type="submit" class="px-6 py-2.5 bg-indigo-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-indigo-700 shadow-sm transition">
                                    Konfirmasi & Pesan Tiket
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
