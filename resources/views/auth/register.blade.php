<x-app-layout>

    <div class="fixed inset-0 z-0 overflow-hidden">
        <video autoplay loop muted playsinline class="absolute z-0 w-auto min-w-full min-h-full max-w-none object-cover">
            <source src="{{ asset('videos/hero.mp4') }}" type="video/mp4" />
        </video>
        <div class="absolute inset-0 bg-black/60 z-1"></div>
    </div>

    <div class="relative z-10 min-h-[85vh] flex items-center justify-center font-inter selection:bg-emerald-800 selection:text-white py-10">

        <div class="relative w-full max-w-md p-8 bg-zinc-950/45 backdrop-blur-xl border border-white/10 rounded-3xl shadow-2xl text-white">

            <div class="text-center mb-6">

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
                <h1 class="text-3xl font-bold tracking-tight font-space text-white">Daftar Akun</h1>
                <p class="text-zinc-300 mt-2 text-sm">Bergabung dengan Kebun Raya Sambas</p>
            </div>

            <form method="POST" action="{{ route('register.store') }}" class="space-y-4" novalidate>
                @csrf

                @if ($errors->any())
                    <div class="p-4 bg-red-500/20 border border-red-500/40 rounded-2xl text-red-300 text-sm shadow-md mb-2">
                        <div class="flex items-center gap-2 font-bold mb-1">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-red-400 flex-shrink-0">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                            </svg>
                            <span>Gagal Mendaftar</span>
                        </div>
                        <p class="text-xs opacity-90 pl-1">Silakan periksa pesan peringatan di bawah setiap kolom input.</p>
                    </div>
                @endif

                <div class="space-y-1.5">
                    <label for="name" class="block text-sm font-bold text-zinc-300 font-space pl-1">Nama Lengkap</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="Nama Anda"
                        class="w-full px-5 py-3 bg-white/5 border @error('name') border-red-500 ring-1 ring-red-500 focus:ring-red-500 focus:border-red-500 @else border-white/10 focus:ring-emerald-500 focus:border-emerald-500 @enderror rounded-2xl transition-all outline-none text-white placeholder-zinc-500 shadow-inner" />
                    @error('name')
                        <div class="flex items-center gap-1.5 text-xs text-red-400 font-medium pl-1 mt-1 font-inter">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <div class="space-y-1.5">
                    <label for="email" class="block text-sm font-bold text-zinc-300 font-space pl-1">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="nama@contoh.com"
                        class="w-full px-5 py-3 bg-white/5 border @error('email') border-red-500 ring-1 ring-red-500 focus:ring-red-500 focus:border-red-500 @else border-white/10 focus:ring-emerald-500 focus:border-emerald-500 @enderror rounded-2xl transition-all outline-none text-white placeholder-zinc-500 shadow-inner" />
                    @error('email')
                        <div class="flex items-center gap-1.5 text-xs text-red-400 font-medium pl-1 mt-1 font-inter">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <div class="space-y-1.5" x-data="{ show: false }">
                    <div class="flex items-center justify-between pl-1">
                        <label for="password" class="block text-sm font-bold text-zinc-300 font-space">Password</label>
                    </div>

                    <div class="relative">
                        <input
                            id="password"
                            x-bind:type="show ? 'text' : 'password'"
                            name="password"
                            required
                            autocomplete="new-password"
                            class="w-full px-5 py-3 bg-white/5 border @error('password') border-red-500 ring-1 ring-red-500 focus:ring-red-500 focus:border-red-500 @else border-white/10 focus:ring-emerald-500 focus:border-emerald-500 @enderror rounded-2xl transition-all outline-none text-white placeholder-zinc-550 shadow-inner pr-12"
                        />

                        <button type="button" @click="show = !show" class="absolute right-4 top-3 text-zinc-400 hover:text-zinc-200 transition-colors focus:outline-none">
                            <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <svg x-show="show" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                    <p class="text-[11px] text-zinc-400 mt-1 pl-1">Minimal 8 karakter, harus mengandung kombinasi huruf dan angka.</p>
                    @error('password')
                        <div class="flex items-center gap-1.5 text-xs text-red-400 font-medium pl-1 mt-1 font-inter">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <div class="space-y-1.5" x-data="{ showConfirm: false }">
                    <div class="flex items-center justify-between pl-1">
                        <label for="password_confirmation" class="block text-sm font-bold text-zinc-300 font-space">Ulangi Password</label>
                    </div>

                    <div class="relative">
                        <input
                            id="password_confirmation"
                            x-bind:type="showConfirm ? 'text' : 'password'"
                            name="password_confirmation"
                            required
                            class="w-full px-5 py-3 bg-white/5 border @error('password_confirmation') border-red-500 ring-1 ring-red-500 focus:ring-red-500 focus:border-red-500 @else border-white/10 focus:ring-emerald-500 focus:border-emerald-500 @enderror rounded-2xl transition-all outline-none text-white shadow-inner pr-12"
                        />
                         <button type="button" @click="showConfirm = !showConfirm" class="absolute right-4 top-3 text-zinc-400 hover:text-zinc-200 transition-colors focus:outline-none">
                            <svg x-show="!showConfirm" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <svg x-show="showConfirm" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <div class="flex items-center gap-1.5 text-xs text-red-400 font-medium pl-1 mt-1 font-inter">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-currentColor stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <div class="text-xs text-zinc-400 mt-2 pl-1">
                    Dengan mendaftar, Anda menyetujui <a href="{{ route('syarat') }}" target="_blank" class="text-emerald-400 hover:text-emerald-300 hover:underline">Syarat & Ketentuan</a> serta <a href="{{ route('privasi') }}" target="_blank" class="text-emerald-400 hover:text-emerald-300 hover:underline">Kebijakan Privasi</a> kami.
                </div>

                <button type="submit"
                    class="w-full mt-2 py-3.5 px-4 bg-emerald-800 hover:bg-emerald-700 text-white font-bold rounded-2xl transition-all transform hover:scale-[1.01] active:scale-[0.99] font-space shadow-xl shadow-emerald-950/40 focus:outline-none">
                    Daftar Sekarang
                </button>
            </form>

            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-white/10"></div></div>
                <div class="relative flex justify-center text-xs uppercase"><span class="bg-transparent px-2 text-zinc-400 font-bold backdrop-blur-sm rounded">Atau</span></div>
            </div>

            <a href="{{ route('auth.google') }}"
               class="flex items-center justify-center w-full py-3 px-4 bg-white/5 hover:bg-white/10 border border-white/10 text-white font-semibold rounded-2xl transition-all font-space group mb-6 shadow-sm hover:shadow-md">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" alt="Google">
                Daftar dengan Google
            </a>

            <div class="text-center text-sm text-zinc-300">
                Sudah punya akun? <a href="{{ route('login') }}" class="font-bold text-emerald-400 hover:text-emerald-300 hover:underline font-space decoration-2 underline-offset-4">Masuk disini</a>
            </div>
        </div>
    </div>
</x-app-layout>