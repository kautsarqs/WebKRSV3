<x-dashboard-layout title="Tambah User Baru">
    <x-admin-sidebar />

    <div class="max-w-2xl mx-auto py-6">

        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center text-sm font-bold text-zinc-500 hover:text-zinc-900 transition-colors mb-6 font-space group">
            <div class="w-8 h-8 rounded-full bg-white border border-zinc-200 flex items-center justify-center mr-2 shadow-sm group-hover:bg-zinc-100 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            </div>
            Kembali ke Daftar
        </a>

        <div class="relative bg-white/60 backdrop-blur-xl border border-zinc-200/60 rounded-3xl shadow-xl shadow-zinc-200/40 p-8">

            <div class="space-y-1 mb-8">
                <h3 class="text-2xl font-bold font-space text-zinc-900 tracking-tight">Buat Akun Baru</h3>
                <p class="text-zinc-500 text-sm font-inter">Isi formulir berikut untuk mendaftarkan user atau admin baru.</p>
            </div>

            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
                @csrf

                <div class="space-y-2">
                    <label for="name" class="block text-sm font-bold text-zinc-700 font-space ml-1">Nama Lengkap</label>
                    <input id="name" type="text" name="name" :value="old('name')" required autofocus
                        class="w-full px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm placeholder-zinc-400" placeholder="Masukkan nama lengkap" />
                    @error('name') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <label for="email" class="block text-sm font-bold text-zinc-700 font-space ml-1">Email Address</label>
                    <input id="email" type="email" name="email" :value="old('email')" required
                        class="w-full px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm placeholder-zinc-400" placeholder="nama@contoh.com" />
                    @error('email') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <label for="role" class="block text-sm font-bold text-zinc-700 font-space ml-1">Role Pengguna</label>
                    <div class="relative">
                        <select name="role" id="role" class="w-full px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm appearance-none cursor-pointer">
                            <option value="user">User Biasa</option>
                            <option value="admin">Administrator</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-zinc-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    @error('role') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-bold text-zinc-700 font-space ml-1">Password</label>
                        <input id="password" type="password" name="password" required
                            class="w-full px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm" />
                        @error('password') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-2">
                        <label for="password_confirmation" class="block text-sm font-bold text-zinc-700 font-space ml-1">Konfirmasi Password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required
                            class="w-full px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm" />
                    </div>
                </div>

                <div class="pt-6 flex justify-end">
                    <button type="submit" class="px-8 py-3.5 bg-zinc-900 hover:bg-zinc-800 text-white font-bold rounded-xl transition-all transform hover:scale-[1.02] active:scale-[0.98] font-space shadow-lg shadow-zinc-900/20">
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-dashboard-layout>