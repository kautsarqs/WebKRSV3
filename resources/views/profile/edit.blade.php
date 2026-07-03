<x-dashboard-layout title="Edit Profile">
    <x-admin-sidebar />

    <div class="space-y-8 py-4">
        
        @if (session('status') === 'profile-updated' || session('status') === 'password-updated')
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-90"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-90"
                 class="bg-green-100/80 backdrop-blur-sm border border-green-200 text-green-800 px-6 py-4 rounded-2xl text-sm font-bold font-space shadow-lg shadow-green-900/5 flex items-center gap-3">
                 <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                  </svg>
                Perubahan berhasil disimpan!
            </div>
        @endif

        <div class="grid gap-8 md:grid-cols-2">
            
            <div class="relative bg-white/60 backdrop-blur-xl border border-zinc-200/60 rounded-3xl shadow-xl shadow-zinc-200/40 p-8 h-fit">
                <header class="mb-8 border-b border-zinc-200/60 pb-4">
                    <h2 class="text-xl font-bold text-zinc-900 font-space tracking-tight">Informasi Profil</h2>
                    <p class="mt-1 text-sm text-zinc-500 font-inter">Update nama, email, dan identitas akun Anda.</p>
                </header>

                <div class="flex items-center gap-4 mb-8 bg-white/40 p-3 rounded-2xl border border-zinc-100">
                    <img 
                        src="{{ Auth::user()->avatar_url }}" 
                        alt="{{ Auth::user()->name }}" 
                        class="h-14 w-14 rounded-full border-2 border-white object-cover shadow-md"
                    >
                    <div>
                        <p class="font-bold text-base text-zinc-900 font-space">{{ Auth::user()->name }}</p>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-zinc-100 text-zinc-800">
                            {{ Auth::user()->role === 'admin' ? 'Administrator' : 'Pengguna Biasa' }}
                        </span>
                    </div>
                </div>

                <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    @method('patch')

                    <div class="space-y-2">
                        <label for="avatar" class="block text-sm font-bold text-zinc-700 font-space ml-1">Foto Profil</label>
                        <input id="avatar" name="avatar" type="file" accept="image/*"
                            class="w-full px-4 py-2 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm text-sm file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-zinc-900 file:text-white hover:file:bg-zinc-800 cursor-pointer" />
                        @error('avatar') <span class="text-red-500 text-xs ml-1 font-medium">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="name" class="block text-sm font-bold text-zinc-700 font-space ml-1">Nama Lengkap</label>
                        <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autocomplete="name" 
                            class="w-full px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm placeholder-zinc-400" />
                        @error('name') <span class="text-red-500 text-xs ml-1 font-medium">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-bold text-zinc-700 font-space ml-1">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username" 
                            class="w-full px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm placeholder-zinc-400" />
                        @error('email') <span class="text-red-500 text-xs ml-1 font-medium">{{ $message }}</span> @enderror
                        
                        @if($user->google_id)
                            <div class="flex items-center gap-2 mt-2 text-zinc-500 text-xs bg-zinc-50 p-2 rounded-lg border border-zinc-100">
                                <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-4 h-4" alt="Google">
                                Akun ini terhubung dengan Google.
                            </div>
                        @endif
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full py-3 px-4 bg-zinc-900 hover:bg-zinc-800 text-white font-bold rounded-xl transition-all transform hover:scale-[1.01] active:scale-[0.99] font-space shadow-lg shadow-zinc-900/20">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            <div class="relative bg-white/60 backdrop-blur-xl border border-zinc-200/60 rounded-3xl shadow-xl shadow-zinc-200/40 p-8 h-fit">
                <header class="mb-8 border-b border-zinc-200/60 pb-4">
                    <h2 class="text-xl font-bold text-zinc-900 font-space tracking-tight">Ganti Password</h2>
                    <p class="mt-1 text-sm text-zinc-500 font-inter">Pastikan akun Anda aman dengan password yang kuat.</p>
                </header>

                @if($user->google_id && is_null($user->password))
                    <div class="p-4 bg-blue-50/80 backdrop-blur-sm text-blue-800 rounded-2xl text-sm border border-blue-200 mb-6 font-medium">
                        Info: Anda login menggunakan Google. Password tidak wajib diisi, kecuali Anda ingin bisa login manual.
                    </div>
                @endif

                <form method="post" action="{{ route('password.update') }}" class="space-y-5">
                    @csrf
                    @method('put')

                    @if(!is_null($user->password))
                    <div class="space-y-2">
                        <label for="current_password" class="block text-sm font-bold text-zinc-700 font-space ml-1">Password Saat Ini</label>
                        <input id="current_password" name="current_password" type="password" autocomplete="current-password" 
                            class="w-full px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm" />
                        @error('current_password') <span class="text-red-500 text-xs ml-1 font-medium">{{ $message }}</span> @enderror
                    </div>
                    @else
                        <input type="hidden" name="current_password" value="password_acak"> 
                    @endif

                    <div class="space-y-2">
                        <label for="update_password_password" class="block text-sm font-bold text-zinc-700 font-space ml-1">Password Baru</label>
                        <input id="update_password_password" name="password" type="password" autocomplete="new-password" 
                            class="w-full px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm" />
                        @error('password') <span class="text-red-500 text-xs ml-1 font-medium">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="update_password_password_confirmation" class="block text-sm font-bold text-zinc-700 font-space ml-1">Ulangi Password Baru</label>
                        <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" 
                            class="w-full px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm" />
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full py-3 px-4 bg-white border-2 border-zinc-200 text-zinc-900 hover:bg-zinc-50 hover:border-zinc-300 font-bold rounded-xl transition-all transform hover:scale-[1.01] active:scale-[0.99] font-space shadow-sm">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-dashboard-layout>