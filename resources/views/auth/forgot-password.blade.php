<x-guest-layout>
    <div class="mb-6 text-center">
        <h1 class="text-xl font-bold text-gray-900">Lupa Password?</h1>
        <p class="text-xs text-gray-500 mt-1">Masukkan alamat email Anda untuk menerima link reset password.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Alamat Email" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                Kirim Link Reset Password
            </button>
        </div>

        <div class="text-center pt-4 border-t border-gray-100">
            <a href="{{ route('login') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition">
                ← Kembali ke Halaman Login
            </a>
        </div>
    </form>
</x-guest-layout>
