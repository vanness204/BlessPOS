
<x-guest-layout>

    {{-- ========================================= --}}
    {{-- LOGIN BLESS POS                          --}}
    {{-- Halaman untuk autentikasi pengguna        --}}
    {{-- ========================================= --}}

    <div class="mb-6 text-center">

        {{-- Nama aplikasi --}}
        <h1 class="text-3xl font-bold text-gray-800">
            BLESS POS
        </h1>

        {{-- Deskripsi aplikasi --}}
        <p class="mt-2 text-sm text-gray-500">
            Point of Sale & Business Management System
        </p>

        <p class="mt-4 text-sm text-gray-600">
            Silakan masuk menggunakan akun Anda.
        </p>

    </div>

    {{-- Pesan status dari proses autentikasi --}}
    <x-auth-session-status
        class="mb-4"
        :status="session('status')"
    />

    {{-- Form login diproses oleh Laravel Breeze --}}
    <form method="POST" action="{{ route('login') }}">

        {{-- Perlindungan CSRF --}}
        @csrf

        {{-- INPUT EMAIL --}}
        <div>

            <x-input-label
                for="email"
                :value="__('Email')"
            />

            <x-text-input
                id="email"
                class="block mt-1 w-full"
                type="email"
                name="email"
                :value="old('email')"
                required
                autofocus
                autocomplete="username"
                placeholder="Masukkan email"
            />

            {{-- Pesan kesalahan email --}}
            <x-input-error
                :messages="$errors->get('email')"
                class="mt-2"
            />

        </div>

        {{-- INPUT PASSWORD --}}
        <div class="mt-4">

            <x-input-label
                for="password"
                :value="__('Password')"
            />

            <x-text-input
                id="password"
                class="block mt-1 w-full"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                placeholder="Masukkan password"
            />

            {{-- Pesan kesalahan password --}}
            <x-input-error
                :messages="$errors->get('password')"
                class="mt-2"
            />

        </div>

        {{-- OPSI INGAT SAYA --}}
        <div class="block mt-4">

            <label
                for="remember_me"
                class="inline-flex items-center"
            >

                <input
                    id="remember_me"
                    type="checkbox"
                    name="remember"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                >

                <span class="ms-2 text-sm text-gray-600">
                    Ingat saya
                </span>

            </label>

        </div>

        {{-- TOMBOL LOGIN DAN LUPA PASSWORD --}}
        <div class="flex items-center justify-between mt-6">

            @if (Route::has('password.request'))

                <a
                    class="text-sm text-indigo-600 hover:text-indigo-800"
                    href="{{ route('password.request') }}"
                >
                    Lupa password?
                </a>

            @endif

            <x-primary-button>
                Masuk
            </x-primary-button>

        </div>

    </form>

    {{-- COPYRIGHT APLIKASI --}}
    <div class="mt-8 text-center text-xs text-gray-400">
        © {{ date('Y') }} BLESS POS. All Rights Reserved.
    </div>

</x-guest-layout>
