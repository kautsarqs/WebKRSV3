<x-app-layout>

    <div class="fixed inset-0 z-0 overflow-hidden">
        <video autoplay loop muted playsinline class="absolute z-0 w-auto min-w-full min-h-full max-w-none object-cover">
            <source src="{{ asset('videos/hero.mp4') }}" type="video/mp4" />
        </video>
        <div class="absolute inset-0 bg-black/60 z-1"></div>
    </div>

    <div class="relative z-10 min-h-[85vh] flex items-center justify-center font-inter selection:bg-emerald-800 selection:text-white py-10">

        <div class="relative w-full max-w-md p-8 bg-zinc-950/45 backdrop-blur-xl border border-white/10 rounded-3xl shadow-2xl text-white">

            <div class="text-center mb-8">

                <div class="flex items-center justify-center gap-2 mb-3">
                    <span class="block h-px w-6 bg-white/20 rounded-full"></span>
                    <svg class="w-4 h-4 text-emerald-400 opacity-80" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17 8C8 10 5.9 16.17 3.82 19.34L5.71 21C8.15 17.4 10.55 14.32 17 8Z"/>
                        <path d="M21 3C21 3 8 5 7 18L9 20C9.2 15.97 11.5 9.35 21 3Z" opacity="0.6"/>
                    </svg>
                    <span class="block h-px w-6 bg-white/20 rounded-full"></span>
                </div>

                <div class="flex justify-center mb-4">
                    <img src="{{ asset('images/logoKRS.png') }}" alt="Logo" class="h-16 w-auto drop-shadow-md">
                </div>
                <h1 class="text-3xl font-bold tracking-tight font-space text-white">Lupa Kata Sandi</h1>
                <p class="text-zinc-300 mt-2 text-sm leading-relaxed">
                    Masukkan email akun Anda dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi Anda.
                </p>
            </div>

            @if (session('status'))
                <!-- Popup Modal Cek Email -->
                <div x-data="{ open: true }" x-show="open" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-md">
                    <div @click.away="open = false" class="bg-zinc-900 border border-emerald-500/30 rounded-3xl p-6 sm:p-8 max-w-sm w-full text-center shadow-2xl space-y-4">
                        <div class="w-16 h-16 bg-emerald-500/10 border border-emerald-500/30 rounded-full flex items-center justify-center mx-auto text-emerald-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold font-space text-white">Silakan Cek Email Anda</h3>
                        <p class="text-xs text-zinc-300 font-inter leading-relaxed">
                            {{ session('status') }}
                        </p>
                        <div class="pt-2">
                            <button type="button" @click="open = false" class="w-full py-3 px-4 bg-emerald-800 hover:bg-emerald-700 text-white font-bold rounded-2xl transition-all font-space shadow-lg shadow-emerald-950/40">
                                Saya Mengerti
                            </button>
                        </div>
                    </div>
                </div>

                <div class="mb-6 p-4 bg-emerald-500/20 border border-emerald-500/40 rounded-2xl text-emerald-300 text-sm shadow-md">
                    <div class="flex items-center gap-2 font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 flex-shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Berhasil Terkirim</span>
                    </div>
                    <p class="mt-1 opacity-90 pl-1 text-xs">{{ session('status') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-6" novalidate>
                @csrf

                @if ($errors->any())
                    <div class="p-4 bg-red-500/20 border border-red-500/40 rounded-2xl text-red-300 text-sm shadow-md">
                        <div class="flex items-center gap-2 font-bold mb-1">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-red-400 flex-shrink-0">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                            </svg>
                            <span>Terdapat Kesalahan</span>
                        </div>
                        <ul class="list-disc list-inside space-y-1 text-xs opacity-95 pl-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="space-y-2">
                    <label for="email" class="block text-sm font-bold text-zinc-300 font-space pl-1">Alamat Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="nama@contoh.com"
                        class="w-full px-5 py-3.5 bg-white/5 border @error('email') border-red-500/80 focus:ring-red-500 focus:border-red-500 @else border-white/10 focus:ring-emerald-500 focus:border-emerald-500 @enderror rounded-2xl transition-all outline-none text-white placeholder-zinc-500 shadow-inner" />
                    @error('email')
                        <p class="text-xs text-red-400 mt-1 pl-1 font-medium font-inter">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full py-3.5 px-4 bg-emerald-800 hover:bg-emerald-700 text-white font-bold rounded-2xl transition-all transform hover:scale-[1.01] active:scale-[0.99] font-space shadow-xl shadow-emerald-950/40 focus:outline-none flex items-center justify-center gap-2">
                    <span>Kirim Tautan Reset</span>
                </button>

                <div class="text-center pt-2">
                    <p class="text-sm text-zinc-400">
                        <a href="{{ route('login') }}" class="text-emerald-400 hover:text-emerald-300 font-semibold transition-colors inline-flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                <path fill-rule="evenodd" d="M17 10a.75.75 0 01-.75.75H5.612l4.158 3.96a.75.75 0 11-1.04 1.08l-5.5-5.25a.75.75 0 010-1.08l5.5-5.25a.75.75 0 111.04 1.08L5.612 9.25H16.25A.75.75 0 0117 10z" clip-rule="evenodd" />
                            </svg>
                            Kembali ke halaman Login
                        </a>
                    </p>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
