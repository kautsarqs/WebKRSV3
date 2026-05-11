<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pendaftaran Pengunjung - Kebun Raya Sambas</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, .font-heading { font-family: 'Space Grotesk', sans-serif; }
    </style>
</head>
<body class="bg-zinc-50 text-zinc-900 antialiased">

    <x-landing.navbar />

    <main class="pt-32 pb-20 px-6 relative z-10">
        <div class="max-w-3xl mx-auto">
            
            <div class="mb-10 text-center">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full border border-green-200 bg-green-50 text-xs text-green-700 mb-4">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                    Formulir Online
                </div>
                <h1 class="text-4xl font-bold text-zinc-900 mb-4">Pendaftaran Pengunjung</h1>
                <p class="text-zinc-500 max-w-lg mx-auto">
                    Silakan lengkapi data di bawah ini untuk merencanakan kunjungan Anda ke Kebun Raya Sambas.
                </p>
            </div>

            @guest
            <div class="bg-white rounded-3xl shadow-xl shadow-zinc-200/50 border border-zinc-100 overflow-hidden p-8 text-center">
                <div class="max-w-md mx-auto">
                    <svg class="w-16 h-16 text-zinc-300 mx-auto mb-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H4.5a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                    <h2 class="text-2xl font-bold mb-2 font-heading">Akses Dibatasi</h2>
                    <p class="text-zinc-500 mb-6">
                        Untuk dapat mengisi formulir, silakan masuk ke akun Anda terlebih dahulu. Pendaftaran hanya untuk pengguna yang terautentikasi.
                    </p>
                    <div class="flex items-center justify-center gap-4">
                        <a href="{{ route('login') }}" class="w-full bg-zinc-900 hover:bg-zinc-800 text-white font-medium py-3 px-6 rounded-xl transition-all">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="w-full bg-zinc-100 hover:bg-zinc-200 text-zinc-800 font-medium py-3 px-6 rounded-xl transition-all">
                            Daftar Akun
                        </a>
                    </div>
                </div>
            </div>
            @endguest

            @auth
            <div class="bg-white rounded-3xl shadow-xl shadow-zinc-200/50 border border-zinc-100 overflow-hidden p-8">
                <form id="form-pendaftaran" action="{{ route('pendaftaran.pengunjung.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="nama_lengkap" class="text-sm font-medium text-zinc-700">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ Auth::user()->name ?? old('nama_lengkap') }}" class="w-full rounded-xl border-zinc-200 focus:border-zinc-900 focus:ring-zinc-900 text-sm px-4 py-3 @error('nama_lengkap') border-red-500 @enderror" placeholder="Masukkan nama lengkap" required>
                            @error('nama_lengkap')
                                <p class="text-xs text-red-600 mt-1 server-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="no_identitas" class="text-sm font-medium text-zinc-700">No. Identitas (KTP/NIK)</label>
                            <input type="text" name="no_identitas" id="no_identitas" value="{{ old('no_identitas') }}" class="w-full rounded-xl border-zinc-200 focus:border-zinc-900 focus:ring-zinc-900 text-sm px-4 py-3 @error('no_identitas') border-red-500 @enderror" placeholder="Contoh: 61010xxxxxxxx" required>
                            @error('no_identitas')
                                <p class="text-xs text-red-600 mt-1 server-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="nomor_hp" class="text-sm font-medium text-zinc-700">Nomor WhatsApp</label>
                            <input type="tel" name="nomor_hp" id="nomor_hp" value="{{ old('nomor_hp') }}" class="w-full rounded-xl border-zinc-200 focus:border-zinc-900 focus:ring-zinc-900 text-sm px-4 py-3 @error('nomor_hp') border-red-500 @enderror" placeholder="08xxxxxxxxxx" required>
                            @error('nomor_hp')
                                <p class="text-xs text-red-600 mt-1 server-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="tanggal_kunjungan" class="text-sm font-medium text-zinc-700">Tanggal Kunjungan</label>
                            <input type="date" name="tanggal_kunjungan" id="tanggal_kunjungan" value="{{ old('tanggal_kunjungan') }}" class="w-full rounded-xl border-zinc-200 focus:border-zinc-900 focus:ring-zinc-900 text-sm px-4 py-3 @error('tanggal_kunjungan') border-red-500 @enderror" required>
                            @error('tanggal_kunjungan')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="jumlah_rombongan" class="text-sm font-medium text-zinc-700">Jumlah Rombongan (Orang)</label>
                        <input type="number" name="jumlah_rombongan" id="jumlah_rombongan" min="1" value="{{ old('jumlah_rombongan', 1) }}" class="w-full rounded-xl border-zinc-200 focus:border-zinc-900 focus:ring-zinc-900 text-sm px-4 py-3 @error('jumlah_rombongan') border-red-500 @enderror" required>
                        @error('jumlah_rombongan')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="keperluan" class="text-sm font-medium text-zinc-700">Tujuan / Keperluan</label>
                        <textarea name="keperluan" id="keperluan" rows="3" class="w-full rounded-xl border-zinc-200 focus:border-zinc-900 focus:ring-zinc-900 text-sm px-4 py-3 @error('keperluan') border-red-500 @enderror" placeholder="Contoh: Wisata keluarga, Studi banding sekolah, dll.">{{ old('keperluan') }}</textarea>
                        @error('keperluan')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4 border-t border-zinc-100 mt-6">
                        <button type="submit" class="w-full bg-zinc-900 hover:bg-zinc-800 text-white font-medium py-3 rounded-xl transition-all shadow-lg shadow-zinc-900/10 flex justify-center items-center gap-2">
                            <span>Kirim Pendaftaran</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                        <p class="text-center text-xs text-zinc-400 mt-4">
                            Dengan mengirim formulir ini, Anda menyetujui aturan dan tata tertib Kebun Raya Sambas.
                        </p>
                    </div>
                </form>
            </div>
            @endauth

        </div>
    </main>

    <div class="fixed inset-0 -z-10 pointer-events-none">
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#8080800a_1px,transparent_1px),linear-gradient(to_bottom,#8080800a_1px,transparent_1px)] bg-[size:14px_24px]"></div>
        <div class="absolute left-0 right-0 top-0 -z-10 m-auto h-[310px] w-[310px] rounded-full bg-green-400 opacity-20 blur-[100px]"></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('form-pendaftaran');
            const nameInput = document.getElementById('nama_lengkap');
            const ktpInput = document.getElementById('no_identitas');
            const waInput = document.getElementById('nomor_hp');

            // Fungsi bantuan untuk memunculkan/menyembunyikan error real-time
            function toggleError(input, hasError, message) {
                let errorEl = input.nextElementSibling;
                
                // Cari atau buat elemen p untuk pesan error kustom (js)
                if (!errorEl || !errorEl.classList.contains('js-error')) {
                    errorEl = document.createElement('p');
                    errorEl.className = 'text-xs text-red-600 mt-1 js-error';
                    input.parentNode.insertBefore(errorEl, input.nextSibling);
                }

                if (hasError) {
                    input.classList.add('border-red-500');
                    errorEl.textContent = message;
                    errorEl.style.display = 'block';
                } else {
                    input.classList.remove('border-red-500');
                    errorEl.style.display = 'none';
                }
                
                // Sembunyikan error bawaan dari Laravel jika user mulai mengetik ulang
                const serverError = input.parentNode.querySelector('.server-error');
                if (serverError) serverError.style.display = 'none';
            }

            nameInput.addEventListener('input', function() {
                // REGEX: Hanya izinkan huruf besar/kecil dan spasi, blokir angka & simbol otomatis
                this.value = this.value.replace(/[^a-zA-Z\s]/g, '');
                
                if (this.value.trim() === '') {
                    toggleError(this, true, 'Nama lengkap tidak boleh kosong.');
                } else {
                    toggleError(this, false, '');
                }
            });

            ktpInput.addEventListener('input', function() {
                // REGEX: Hanya izinkan angka, otomatis hapus huruf atau simbol
                this.value = this.value.replace(/\D/g, '');
                
                // Paksa batas maksimal inputan KTP 16 digit saja
                if (this.value.length > 16) {
                    this.value = this.value.slice(0, 16);
                }
                
                if (this.value.length > 0 && this.value.length !== 16) {
                    toggleError(this, true, 'No. identitas harus terdiri dari tepat 16 digit angka.');
                } else {
                    toggleError(this, false, '');
                }
            });

            waInput.addEventListener('input', function() {
                // REGEX: Hanya izinkan angka, otomatis hapus huruf atau simbol
                this.value = this.value.replace(/\D/g, '');
                
                // Paksa batas maksimal inputan No WA 13 digit
                if (this.value.length > 13) {
                    this.value = this.value.slice(0, 13);
                }
                
                if (this.value.length > 0 && (this.value.length < 10 || this.value.length > 13)) {
                    toggleError(this, true, 'Nomor WhatsApp harus terdiri dari 10 hingga 13 digit angka.');
                } else {
                    toggleError(this, false, '');
                }
            });

            // Cegah formulir disubmit jika masih ada validasi JS yang error
            form.addEventListener('submit', function(e) {
                nameInput.dispatchEvent(new Event('input'));
                ktpInput.dispatchEvent(new Event('input'));
                waInput.dispatchEvent(new Event('input'));

                const errors = document.querySelectorAll('.js-error[style="display: block;"]');
                if (errors.length > 0) {
                    e.preventDefault(); // Menggagalkan submit
                    errors[0].previousElementSibling.focus(); // Mengarahkan kursor ke input yang salah pertama
                }
            });
        });
    </script>
</body>
</html>
