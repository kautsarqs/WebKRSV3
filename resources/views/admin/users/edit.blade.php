<x-dashboard-layout title="Edit User">
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
                <h3 class="text-2xl font-bold font-space text-zinc-900 tracking-tight">Edit Data User</h3>
                <p class="text-zinc-500 text-sm font-inter">Perbarui informasi dan role pengguna.</p>
            </div>

            <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="space-y-2">
                    <label for="name" class="block text-sm font-bold text-zinc-700 font-space ml-1">Nama Lengkap</label>
                    <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required
                        class="w-full px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm placeholder-zinc-400" />
                    @error('name') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <label for="email" class="block text-sm font-bold text-zinc-700 font-space ml-1">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required
                        class="w-full px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm placeholder-zinc-400" />
                    @error('email') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <label for="role" class="block text-sm font-bold text-zinc-700 font-space ml-1">Role Pengguna</label>
                    <div class="relative">
                        @if($user->id === auth()->id())
                            <input type="hidden" name="role" value="{{ $user->role }}" />
                            <select disabled id="role" class="w-full px-4 py-3 bg-zinc-100 border border-zinc-300 rounded-xl text-zinc-500 shadow-sm appearance-none cursor-not-allowed">
                                <option value="admin" selected>Administrator (Akun Anda)</option>
                            </select>
                            <p class="text-xs text-amber-600 font-medium ml-1 mt-1">Anda tidak dapat mengubah role akun Anda sendiri.</p>
                        @else
                            <select name="role" id="role" class="w-full px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm appearance-none cursor-pointer">
                                <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User Biasa</option>
                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Administrator</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-zinc-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="pt-6 border-t border-zinc-200/60 mt-6">
                    <div class="mb-4">
                        <h4 class="text-base font-bold text-zinc-900 font-space">Ganti Password (Opsional)</h4>
                        <p class="text-xs text-zinc-500 mt-0.5">Biarkan kosong jika tidak ingin mengubah password user ini.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-2">
                            <label for="password" class="block text-sm font-bold text-zinc-700 font-space ml-1">Password Baru</label>
                            <input id="password" type="password" name="password"
                                class="w-full px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm" />
                            @error('password') <span class="text-xs text-red-500 font-medium ml-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="password_confirmation" class="block text-sm font-bold text-zinc-700 font-space ml-1">Konfirmasi Password</label>
                            <input id="password_confirmation" type="password" name="password_confirmation"
                                class="w-full px-4 py-3 bg-white/50 border border-zinc-300 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all outline-none text-zinc-800 shadow-sm" />
                        </div>
                    </div>
                </div>

                <div class="pt-6 flex justify-end gap-3">
                    <a href="{{ route('admin.users.index') }}" class="px-6 py-3 bg-white border-2 border-zinc-200 text-zinc-700 font-bold rounded-xl hover:bg-zinc-50 hover:border-zinc-300 transition-all font-space">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-3 bg-zinc-900 hover:bg-zinc-800 text-white font-bold rounded-xl transition-all transform hover:scale-[1.02] active:scale-[0.98] font-space shadow-lg shadow-zinc-900/20">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-dashboard-layout>