<x-app-layout>
    <div class="flex items-center justify-center min-h-[60vh]">
        <x-ui.glass-card class="w-full max-w-md text-center">
            <h2 class="text-2xl font-bold text-zinc-900 mb-4">Verifikasi Email Anda</h2>
            <p class="text-zinc-600 mb-6 text-sm">
                Terima kasih telah mendaftar! Sebelum memulai, mohon verifikasi alamat email Anda dengan mengklik link yang baru saja kami kirimkan ke email Anda.
            </p>

            @if (session('message'))
                <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 p-3 rounded-lg border border-green-200">
                    {{ session('message') }}
                </div>
            @endif

            <div class="flex flex-col gap-3">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <x-ui.button>Kirim Ulang Email Verifikasi</x-ui.button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-zinc-500 hover:text-zinc-900 underline">
                        Logout
                    </button>
                </form>
            </div>
        </x-ui.glass-card>
    </div>
</x-app-layout>