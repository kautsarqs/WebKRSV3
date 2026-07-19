<x-dashboard-layout title="Profil Saya">
    <x-admin-sidebar />

    <div class="max-w-3xl mx-auto py-8">

        <div class="relative bg-white/60 backdrop-blur-xl border border-zinc-200/60 rounded-3xl shadow-xl shadow-zinc-200/40 p-10 text-center overflow-hidden">

            <div class="absolute top-[-50px] right-[-50px] w-40 h-40 bg-zinc-200/50 rounded-full blur-[50px] pointer-events-none"></div>

            <div class="relative inline-block mb-6">
                <div class="relative w-32 h-32 mx-auto">
                    <img
                        src="{{ $user->avatar_url }}"
                        alt="{{ $user->name }}"
                        class="w-full h-full rounded-full border-4 border-white shadow-lg object-cover"
                    >
                    <span class="absolute bottom-1 right-1 bg-zinc-900 text-white text-[10px] uppercase tracking-wider px-2 py-1 rounded-full border-2 border-white font-bold font-space shadow-sm">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
            </div>

            <h1 class="text-3xl font-bold text-zinc-900 font-space tracking-tight mb-1">{{ $user->name }}</h1>
            <p class="text-zinc-500 font-medium text-sm">{{ $user->email }}</p>

            <div class="grid grid-cols-2 gap-6 border-t border-b border-zinc-200/60 py-8 my-8">
                <div class="text-center space-y-1">
                    <p class="text-xs text-zinc-400 uppercase tracking-widest font-bold font-space">Bergabung Sejak</p>
                    <p class="text-zinc-800 font-bold text-lg font-space">{{ $user->created_at->format('d F Y') }}</p>
                </div>

                <div class="text-center space-y-1 border-l border-zinc-200/60">
                    <p class="text-xs text-zinc-400 uppercase tracking-widest font-bold font-space">Status Akun</p>
                    <div class="flex items-center justify-center gap-2 pt-1">
                        @if($user->email_verified_at)
                            <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>
                                Terverifikasi
                            </div>
                        @else
                            <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 mr-1.5"></span>
                                Belum Verifikasi
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="flex justify-center">
                <a href="{{ route('profile.edit') }}">
                    <button class="inline-flex items-center px-6 py-3 bg-zinc-900 border border-transparent rounded-xl font-bold text-sm text-white uppercase tracking-widest hover:bg-zinc-800 active:bg-zinc-900 focus:outline-none focus:border-zinc-900 focus:ring ring-zinc-300 disabled:opacity-25 transition ease-in-out duration-150 font-space shadow-lg shadow-zinc-900/20 transform hover:-translate-y-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                        Edit Profil & Password
                    </button>
                </a>
            </div>

        </div>
    </div>

</x-dashboard-layout>