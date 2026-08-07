@extends('layouts.landing')

@section('title', 'Pendaftaran Magang - Kebun Raya Sambas')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.12/build/css/intlTelInput.css">
<style>
    .iti { width: 100% !important; }
    .iti__selected-dial-code {
        font-size: 0.875rem !important;
        color: #27272a !important;
        font-weight: 500;
    }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto px-4 py-12">
    <div class="mb-10 text-center">
        <h1 class="text-4xl font-bold text-zinc-900 mb-4 font-heading">Pendaftaran Magang</h1>
        <p class="text-zinc-500 max-w-xl mx-auto">
            Lengkapi formulir di bawah ini untuk mengajukan izin magang / PKL / Praktik Kerja di Kebun Raya Sambas.
            Notifikasi persetujuan akan dikirimkan oleh tim admin.
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
                Untuk dapat mengisi formulir pendaftaran magang, silakan masuk ke akun Anda terlebih dahulu.
            </p>
            <div class="flex items-center justify-center gap-4">
                <a href="{{ route('login') }}" class="flex-1 bg-zinc-900 hover:bg-zinc-800 text-white font-medium py-3 px-6 rounded-xl transition-all text-center">Masuk</a>
                <a href="{{ route('register') }}" class="flex-1 bg-zinc-100 hover:bg-zinc-200 text-zinc-800 font-medium py-3 px-6 rounded-xl transition-all text-center">Daftar Akun</a>
            </div>
        </div>
    </div>
    @endguest

    @auth
    @if(!Auth::user()->hasVerifiedEmail())
    <div class="bg-amber-50 border border-amber-200 text-amber-900 rounded-3xl p-6 flex flex-col md:flex-row items-center justify-between gap-4">
        <div>
            <h4 class="font-bold text-sm font-space">Email Belum Diverifikasi</h4>
            <p class="text-xs text-amber-700 mt-1">Silakan verifikasi email Anda terlebih dahulu sebelum mengajukan permohonan magang.</p>
        </div>
        <a href="{{ route('verification.notice') }}" class="px-5 py-2.5 bg-zinc-900 hover:bg-zinc-800 text-white rounded-xl text-xs font-bold transition-all shadow-md">
            Verifikasi Email
        </a>
    </div>
    @else
    <form id="form-magang" action="{{ route('pendaftaran.magang.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf

        <div class="bg-white rounded-3xl shadow-xl shadow-zinc-200/50 border border-zinc-100 overflow-hidden">
            <div class="px-8 py-5 border-b border-zinc-100 flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <h2 class="font-bold text-zinc-900 text-base">Informasi Pribadi</h2>
            </div>
            <div class="p-8 space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-1.5">
                        <label for="nama_lengkap" class="text-sm font-semibold text-zinc-700">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap') }}"
                            class="w-full rounded-xl border-zinc-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm px-4 py-3 @error('nama_lengkap') border-red-500 @enderror"
                            placeholder="Masukkan nama lengkap" required>
                        @error('nama_lengkap')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="space-y-1.5">
                        <label for="jenjang" class="text-sm font-semibold text-zinc-700">Jenjang / Pendidikan <span class="text-red-500">*</span></label>
                        <select name="jenjang" id="jenjang"
                            class="w-full rounded-xl border-zinc-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm px-4 py-3 @error('jenjang') border-red-500 @enderror" required>
                            @foreach(['SMK' => 'SMK / Sederajat', 'D3' => 'D3 (Diploma 3)', 'D4' => 'D4 (Diploma 4)', 'S1' => 'S1 (Sarjana)', 'S2' => 'S2 (Magister)', 'Umum' => 'Umum'] as $val => $label)
                                <option value="{{ $val }}" {{ old('jenjang') == $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('jenjang')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="space-y-1.5">
                    <label for="nomor_hp_display" class="text-sm font-semibold text-zinc-700">Nomor HP / WhatsApp <span class="text-red-500">*</span></label>
                    <input type="tel" id="nomor_hp_display" class="w-full rounded-xl border-zinc-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm px-4 py-3" placeholder="8xxxxxxxxxx" required>
                    <input type="hidden" name="nomor_hp" id="nomor_hp" value="{{ old('nomor_hp') }}">
                    @error('nomor_hp')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-xl shadow-zinc-200/50 border border-zinc-100 overflow-hidden">
            <div class="px-8 py-5 border-b border-zinc-100 flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-blue-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <h2 class="font-bold text-zinc-900 text-base">Asal Sekolah / Kampus / Instansi</h2>
            </div>
            <div class="p-8 space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-1.5 md:col-span-2">
                        <label for="institusi" class="text-sm font-semibold text-zinc-700">Nama Sekolah / Universitas / Instansi <span class="text-red-500">*</span></label>
                        <input type="text" name="institusi" id="institusi" value="{{ old('institusi') }}"
                            class="w-full rounded-xl border-zinc-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm px-4 py-3 @error('institusi') border-red-500 @enderror"
                            placeholder="Contoh: Politeknik Negeri Sambas / SMKN 1 Sambas" required>
                        @error('institusi')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="space-y-1.5 md:col-span-2">
                        <label for="program_studi" class="text-sm font-semibold text-zinc-700">Program Studi / Jurusan</label>
                        <input type="text" name="program_studi" id="program_studi" value="{{ old('program_studi') }}"
                            class="w-full rounded-xl border-zinc-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm px-4 py-3"
                            placeholder="Contoh: Agribisnis, Teknik Informatika, Agroteknologi, dll.">
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-xl shadow-zinc-200/50 border border-zinc-100 overflow-hidden">
            <div class="px-8 py-5 border-b border-zinc-100 flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-cyan-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <h2 class="font-bold text-zinc-900 text-base">Detail Kegiatan Magang</h2>
            </div>
            <div class="p-8 space-y-5">
                <div class="space-y-1.5">
                    <label for="judul_magang" class="text-sm font-semibold text-zinc-700">Judul / Topik Magang <span class="text-red-500">*</span></label>
                    <input type="text" name="judul_magang" id="judul_magang" value="{{ old('judul_magang') }}"
                        class="w-full rounded-xl border-zinc-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm px-4 py-3 @error('judul_magang') border-red-500 @enderror"
                        placeholder="Masukkan judul atau fokus kegiatan magang Anda" required>
                    @error('judul_magang')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="space-y-1.5">
                    <label for="bidang_magang" class="text-sm font-semibold text-zinc-700">Bidang Magang <span class="text-red-500">*</span></label>
                    <input type="text" name="bidang_magang" id="bidang_magang" value="{{ old('bidang_magang') }}"
                        class="w-full rounded-xl border-zinc-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm px-4 py-3 @error('bidang_magang') border-red-500 @enderror"
                        placeholder="Contoh: Pengelolaan Koleksi Plant, IT & Database, Ekowisata, Edukasi, dll." required>
                    @error('bidang_magang')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-1.5">
                        <label for="tanggal_mulai" class="text-sm font-semibold text-zinc-700">Tanggal Mulai Magang <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai') }}" min="{{ date('Y-m-d') }}"
                            class="w-full rounded-xl border-zinc-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm px-4 py-3 @error('tanggal_mulai') border-red-500 @enderror" required>
                        @error('tanggal_mulai')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="space-y-1.5">
                        <label for="tanggal_selesai" class="text-sm font-semibold text-zinc-700">Tanggal Selesai Magang <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ old('tanggal_selesai') }}" min="{{ date('Y-m-d') }}"
                            class="w-full rounded-xl border-zinc-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm px-4 py-3 @error('tanggal_selesai') border-red-500 @enderror" required>
                        @error('tanggal_selesai')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="space-y-1.5">
                    <label for="tujuan_magang" class="text-sm font-semibold text-zinc-700">Tujuan &amp; Rencana Kegiatan <span class="text-red-500">*</span></label>
                    <textarea name="tujuan_magang" id="tujuan_magang" rows="5"
                        class="w-full rounded-xl border-zinc-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm px-4 py-3 @error('tujuan_magang') border-red-500 @enderror"
                        placeholder="Jelaskan tujuan magang, rencana kerja, atau kompetensi yang ingin dicapai..." required>{{ old('tujuan_magang') }}</textarea>
                    @error('tujuan_magang')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-xl shadow-zinc-200/50 border border-zinc-100 overflow-hidden">
            <div class="px-8 py-5 border-b border-zinc-100 flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-amber-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                </div>
                <div>
                    <h2 class="font-bold text-zinc-900 text-base">Dokumen Lampiran <span class="text-red-500">*</span></h2>
                </div>
            </div>
            <div class="p-8 space-y-6">
                <div class="space-y-2">
                    <label for="surat_izin_magang" class="text-sm font-semibold text-zinc-700">Surat Pengantar / Permohonan Magang dari Instansi/Sekolah <span class="text-red-500">*</span></label>
                    <input type="file" name="surat_izin_magang" id="surat_izin_magang" accept=".pdf,.jpg,.jpeg,.png" class="block w-full text-sm text-zinc-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer" required>
                    <p class="text-xs text-zinc-400">Format: PDF, JPG, PNG - Maks. 5MB</p>
                    @error('surat_izin_magang')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="space-y-2">
                    <label for="cv" class="text-sm font-semibold text-zinc-700">Curriculum Vitae (CV) / Portofolio <span class="text-red-500">*</span></label>
                    <input type="file" name="cv" id="cv" accept=".pdf,.jpg,.jpeg,.png" class="block w-full text-sm text-zinc-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer" required>
                    <p class="text-xs text-zinc-400">Format: PDF, JPG, PNG - Maks. 5MB</p>
                    @error('cv')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-center gap-4">
            <button type="submit"
                class="flex-1 bg-zinc-900 hover:bg-emerald-700 text-white font-semibold py-4 rounded-2xl transition-all duration-300 shadow-lg shadow-zinc-900/15 flex justify-center items-center gap-2 hover:-translate-y-0.5 hover:shadow-xl cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Kirim Permohonan Magang
            </button>
            <a href="{{ route('home') }}" class="sm:w-auto w-full text-center bg-zinc-100 hover:bg-zinc-200 text-zinc-700 font-medium py-4 px-8 rounded-2xl transition-all">
                Batal
            </a>
        </div>
    </form>
    @endif
    @endauth
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.12/build/js/intlTelInput.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('form-magang');
        if (!form) return;

        const phoneDisplay = document.getElementById('nomor_hp_display');
        const phoneHidden = document.getElementById('nomor_hp');
        const startInput = document.getElementById('tanggal_mulai');
        const endInput = document.getElementById('tanggal_selesai');

        const iti = window.intlTelInput(phoneDisplay, {
            initialCountry: "id",
            preferredCountries: ["id", "my", "sg"],
            separateDialCode: true,
            formatOnDisplay: false,
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.12/build/js/utils.js"
        });

        function updatePhoneValue() {
            const countryData = iti.getSelectedCountryData();
            const dialCode = countryData.dialCode || '';
            let nationalNumber = phoneDisplay.value.replace(/\D/g, '');
            if (nationalNumber.startsWith('0')) {
                nationalNumber = nationalNumber.substring(1);
            }

            if (nationalNumber) {
                phoneHidden.value = '+' + dialCode + nationalNumber;
            } else {
                phoneHidden.value = '';
            }
        }

        phoneDisplay.addEventListener('change', updatePhoneValue);
        phoneDisplay.addEventListener('keyup', updatePhoneValue);

        if (phoneHidden.value) {
            iti.setNumber(phoneHidden.value);
        }

        if (startInput && endInput) {
            startInput.addEventListener('change', function() {
                if (this.value) {
                    endInput.min = this.value;
                    if (endInput.value && endInput.value < this.value) {
                        endInput.value = this.value;
                    }
                }
            });
        }

        form.addEventListener('submit', function() {
            updatePhoneValue();
        });
    });
</script>
@endpush
