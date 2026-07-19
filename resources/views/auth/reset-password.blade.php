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
                <h1 class="text-3xl font-bold tracking-tight font-space text-white">Buat Sandi Baru</h1>
                <p class="text-zinc-300 mt-2 text-sm leading-relaxed">
                    Silakan masukkan kata sandi baru untuk akun Anda. Pastikan kombinasi sandi kuat dan aman.
                </p>
            </div>

            <form method="POST" action="{{ route('password.update') }}" class="space-y-5" novalidate>
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                @if ($errors->any())
                    <div class="p-4 bg-red-500/10 border border-red-500/20 rounded-2xl text-red-400 text-sm mb-4">
                        <div class="flex items-center gap-2 font-semibold mb-1">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-red-400 flex-shrink-0">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                            </svg>
                            <span>Terdapat Kesalahan</span>
                        </div>
                        <ul class="list-disc list-inside space-y-0.5 text-xs opacity-90 pl-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="space-y-2">
                    <label for="email" class="block text-sm font-bold text-zinc-300 font-space pl-1">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required readonly
                        class="w-full px-5 py-3.5 bg-white/5 border border-white/10 rounded-2xl outline-none text-white/70 shadow-inner cursor-not-allowed" />
                </div>

                <div class="space-y-2" x-data="{ show: false }">
                    <label for="password" class="block text-sm font-bold text-zinc-300 font-space pl-1">Kata Sandi Baru</label>
                    <div class="relative">
                        <input id="password" x-bind:type="show ? 'text' : 'password'" name="password" required
                            class="w-full px-5 py-3.5 bg-white/5 border @error('password') border-red-500/80 focus:ring-red-500 focus:border-red-500 @else border-white/10 focus:ring-emerald-500 focus:border-emerald-500 @enderror rounded-2xl transition-all outline-none text-white shadow-inner pr-12" />

                        <button type="button" @click="show = !show" class="absolute right-4 top-3.5 text-zinc-400 hover:text-zinc-200 transition-colors focus:outline-none">
                            <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <svg x-show="show" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="space-y-2" x-data="{ show: false }">
                    <label for="password_confirmation" class="block text-sm font-bold text-zinc-300 font-space pl-1">Konfirmasi Sandi Baru</label>
                    <div class="relative">
                        <input id="password_confirmation" x-bind:type="show ? 'text' : 'password'" name="password_confirmation" required
                            class="w-full px-5 py-3.5 bg-white/5 border border-white/10 focus:ring-emerald-500 focus:border-emerald-500 rounded-2xl transition-all outline-none text-white shadow-inner pr-12" />

                        <button type="button" @click="show = !show" class="absolute right-4 top-3.5 text-zinc-400 hover:text-zinc-200 transition-colors focus:outline-none">
                            <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <svg x-show="show" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="w-full py-3.5 px-4 bg-emerald-800 hover:bg-emerald-700 text-white font-bold rounded-2xl transition-all transform hover:scale-[1.01] active:scale-[0.99] font-space shadow-xl shadow-emerald-950/40 focus:outline-none">
                        Simpan Sandi Baru
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
