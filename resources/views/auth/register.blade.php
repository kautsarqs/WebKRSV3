<x-app-layout>
    <div class="min-h-screen flex items-center justify-center bg-zinc-50 relative overflow-hidden font-inter selection:bg-zinc-900 selection:text-white py-10">
        
        <div class="relative w-full max-w-md p-6">
            
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold tracking-tight font-space text-zinc-900">Daftar Akun</h1>
                <p class="text-zinc-500 mt-2 text-base">Bergabung dengan Kebun Raya Sambas</p>
            </div>

            <form method="POST" action="{{ route('register.store') }}" class="space-y-5">
                @csrf

                <div class="space-y-2">
                    <label for="name" class="block text-sm font-bold text-zinc-700 font-space pl-1">Nama Lengkap</label>
                    <input id="name" type="text" name="name" :value="old('name')" required autofocus placeholder="Nama Anda"
                        class="w-full px-5 py-4 bg-white/80 backdrop-blur-sm border border-zinc-300/80 rounded-2xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 placeholder-zinc-400 shadow-sm hover:shadow-md" />
                </div>

                <div class="space-y-2">
                    <label for="email" class="block text-sm font-bold text-zinc-700 font-space pl-1">Email</label>
                    <input id="email" type="email" name="email" :value="old('email')" required placeholder="nama@contoh.com"
                        class="w-full px-5 py-4 bg-white/80 backdrop-blur-sm border border-zinc-300/80 rounded-2xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 placeholder-zinc-400 shadow-sm hover:shadow-md" />
                </div>

                <div class="space-y-2" x-data="{ show: false }">
                    <div class="flex items-center justify-between pl-1">
                        <label for="password" class="block text-sm font-bold text-zinc-700 font-space">Password</label>
                    </div>
                    
                    <div class="relative">
                        <input 
                            id="password" 
                            x-bind:type="show ? 'text' : 'password'" 
                            name="password" 
                            required 
                            autocomplete="new-password"
                            class="w-full px-5 py-4 bg-white/80 backdrop-blur-sm border border-zinc-300/80 rounded-2xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm hover:shadow-md pr-12"
                        />
                        
                        <button type="button" @click="show = !show" class="absolute right-4 top-4 text-zinc-400 hover:text-zinc-700 transition-colors focus:outline-none">
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

                <div class="space-y-2" x-data="{ showConfirm: false }">
                    <div class="flex items-center justify-between pl-1">
                        <label for="password_confirmation" class="block text-sm font-bold text-zinc-700 font-space">Ulangi Password</label>
                    </div>
                    
                    <div class="relative">
                        <input 
                            id="password_confirmation" 
                            x-bind:type="showConfirm ? 'text' : 'password'" 
                            name="password_confirmation" 
                            required 
                            class="w-full px-5 py-4 bg-white/80 backdrop-blur-sm border border-zinc-300/80 rounded-2xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm hover:shadow-md pr-12"
                        />
                         <button type="button" @click="showConfirm = !showConfirm" class="absolute right-4 top-4 text-zinc-400 hover:text-zinc-700 transition-colors focus:outline-none">
                            <svg x-show="!showConfirm" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <svg x-show="showConfirm" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit" 
                    class="w-full mt-2 py-4 px-4 bg-zinc-900 hover:bg-zinc-800 text-white font-bold rounded-2xl transition-all transform hover:scale-[1.01] active:scale-[0.99] font-space shadow-xl shadow-zinc-900/10">
                    Daftar Sekarang
                </button>
            </form>

            <div class="relative my-8">
                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-zinc-300"></div></div>
                <div class="relative flex justify-center text-xs uppercase"><span class="bg-transparent px-2 text-zinc-500 font-bold backdrop-blur-sm rounded">Atau</span></div>
            </div>

            <a href="{{ route('auth.google') }}" 
               class="flex items-center justify-center w-full py-3.5 px-4 bg-white/80 backdrop-blur-sm hover:bg-white border border-zinc-300 text-zinc-700 font-semibold rounded-2xl transition-all font-space group mb-8 shadow-sm hover:shadow-md">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" alt="Google">
                Daftar dengan Google
            </a>

            <div class="text-center text-sm text-zinc-500">
                Sudah punya akun? <a href="{{ route('login') }}" class="font-bold text-zinc-900 hover:underline font-space decoration-2 underline-offset-4">Masuk disini</a>
            </div>
        </div>
    </div>
</x-app-layout>