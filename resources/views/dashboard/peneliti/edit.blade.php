@extends('layouts.landing')

@section('title', 'Edit Pendaftaran Peneliti - Kebun Raya Sambas')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.12/build/css/intlTelInput.css">
<style>
    .iti { width: 100% !important; }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto px-4 py-12">
    <div class="mb-10 text-center">
        <h1 class="text-4xl font-bold text-zinc-900 mb-4">Edit Pendaftaran Peneliti</h1>
        <p class="text-zinc-500 max-w-xl mx-auto">
            Perbarui permohonan izin penelitian Anda di Kebun Raya Sambas. 
        </p>
    </div>

    <form id="form-peneliti" action="{{ route('dashboard.penelitis.update', $peneliti->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PATCH')

        {{-- ── INFORMASI PRIBADI ──────────────────────────────────────── --}}
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
                        <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap', $peneliti->nama_lengkap) }}"
                            class="w-full rounded-xl border-zinc-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm px-4 py-3 @error('nama_lengkap') border-red-500 @enderror"
                            placeholder="Masukkan nama lengkap" required>
                        @error('nama_lengkap')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="space-y-1.5">
                        <label for="jenjang" class="text-sm font-semibold text-zinc-700">Jenjang / Status <span class="text-red-500">*</span></label>
                        <select name="jenjang" id="jenjang"
                            class="w-full rounded-xl border-zinc-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm px-4 py-3 @error('jenjang') border-red-500 @enderror" required>
                            @foreach(['S1' => 'S1 (Sarjana)', 'S2' => 'S2 (Magister)', 'S3' => 'S3 (Doktor)', 'Dosen' => 'Dosen / Pengajar', 'Peneliti' => 'Peneliti Independen', 'Umum' => 'Umum'] as $val => $label)
                                <option value="{{ $val }}" {{ old('jenjang', $peneliti->jenjang) == $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('jenjang')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="space-y-1.5">
                    <label for="nomor_hp_display" class="text-sm font-semibold text-zinc-700">Nomor HP <span class="text-red-500">*</span></label>
                    <input type="tel" id="nomor_hp_display" class="w-full rounded-xl border-zinc-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm px-4 py-3" placeholder="8xxxxxxxxxx" required>
                    <input type="hidden" name="nomor_hp" id="nomor_hp" value="{{ old('nomor_hp', $peneliti->nomor_hp) }}">
                    @error('nomor_hp')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- ── ASAL INSTITUSI ─────────────────────────────────────────── --}}
        <div class="bg-white rounded-3xl shadow-xl shadow-zinc-200/50 border border-zinc-100 overflow-hidden">
            <div class="px-8 py-5 border-b border-zinc-100 flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-blue-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <h2 class="font-bold text-zinc-900 text-base">Asal Institusi</h2>
            </div>
            <div class="p-8 space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-1.5 md:col-span-2">
                        <label for="institusi" class="text-sm font-semibold text-zinc-700">Nama Institusi / Universitas <span class="text-red-500">*</span></label>
                        <input type="text" name="institusi" id="institusi" value="{{ old('institusi', $peneliti->institusi) }}"
                            class="w-full rounded-xl border-zinc-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm px-4 py-3 @error('institusi') border-red-500 @enderror"
                            placeholder="Contoh: Universitas Tanjungpura" required>
                        @error('institusi')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="space-y-1.5 md:col-span-2">
                        <label for="program_studi" class="text-sm font-semibold text-zinc-700">Program Studi / Departemen</label>
                        <input type="text" name="program_studi" id="program_studi" value="{{ old('program_studi', $peneliti->program_studi) }}"
                            class="w-full rounded-xl border-zinc-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm px-4 py-3"
                            placeholder="Contoh: Biologi, Kehutanan, dll.">
                    </div>
                </div>
            </div>
        </div>

        {{-- ── DETAIL PENELITIAN ──────────────────────────────────────── --}}
        <div class="bg-white rounded-3xl shadow-xl shadow-zinc-200/50 border border-zinc-100 overflow-hidden">
            <div class="px-8 py-5 border-b border-zinc-100 flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-violet-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <h2 class="font-bold text-zinc-900 text-base">Detail Penelitian</h2>
            </div>
            <div class="p-8 space-y-5">
                <div class="space-y-1.5">
                    <label for="judul_penelitian" class="text-sm font-semibold text-zinc-700">Judul Penelitian <span class="text-red-500">*</span></label>
                    <input type="text" name="judul_penelitian" id="judul_penelitian" value="{{ old('judul_penelitian', $peneliti->judul_penelitian) }}"
                        class="w-full rounded-xl border-zinc-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm px-4 py-3 @error('judul_penelitian') border-red-500 @enderror"
                        placeholder="Masukkan judul penelitian Anda" required>
                    @error('judul_penelitian')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="space-y-1.5">
                    <label for="bidang_penelitian" class="text-sm font-semibold text-zinc-700">Bidang Penelitian <span class="text-red-500">*</span></label>
                    <input type="text" name="bidang_penelitian" id="bidang_penelitian" value="{{ old('bidang_penelitian', $peneliti->bidang_penelitian) }}"
                        class="w-full rounded-xl border-zinc-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm px-4 py-3 @error('bidang_penelitian') border-red-500 @enderror"
                        placeholder="Contoh: Botani, Ekologi, Konservasi, dll." required>
                    @error('bidang_penelitian')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-1.5">
                        <label for="tanggal_mulai" class="text-sm font-semibold text-zinc-700">Tanggal Mulai Penelitian <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai', $peneliti->tanggal_mulai->format('Y-m-d')) }}" min="{{ date('Y-m-d') }}"
                            class="w-full rounded-xl border-zinc-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm px-4 py-3 @error('tanggal_mulai') border-red-500 @enderror" required>
                        @error('tanggal_mulai')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="space-y-1.5">
                        <label for="tanggal_selesai" class="text-sm font-semibold text-zinc-700">Tanggal Selesai Penelitian <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ old('tanggal_selesai', $peneliti->tanggal_selesai->format('Y-m-d')) }}" min="{{ date('Y-m-d') }}"
                            class="w-full rounded-xl border-zinc-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm px-4 py-3 @error('tanggal_selesai') border-red-500 @enderror" required>
                        @error('tanggal_selesai')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="space-y-1.5">
                    <label for="tujuan_penelitian" class="text-sm font-semibold text-zinc-700">Tujuan &amp; Latar Belakang Penelitian <span class="text-red-500">*</span></label>
                    <textarea name="tujuan_penelitian" id="tujuan_penelitian" rows="5"
                        class="w-full rounded-xl border-zinc-200 focus:border-emerald-500 focus:ring-emerald-500 text-sm px-4 py-3 @error('tujuan_penelitian') border-red-500 @enderror"
                        placeholder="Jelaskan tujuan, latar belakang, dan manfaat penelitian Anda..." required>{{ old('tujuan_penelitian', $peneliti->tujuan_penelitian) }}</textarea>
                    @error('tujuan_penelitian')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- ── DOKUMEN LAMPIRAN ────────────────────────────────────────── --}}
        @php
            $existingFiles = json_decode($peneliti->surat_pengantar, true) ?? [];
        @endphp
        <div class="bg-white rounded-3xl shadow-xl shadow-zinc-200/50 border border-zinc-100 overflow-hidden">
            <div class="px-8 py-5 border-b border-zinc-100 flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-amber-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                </div>
                <div>
                    <h2 class="font-bold text-zinc-900 text-base">Dokumen Lampiran</h2>
                </div>
            </div>
            <div class="p-8 space-y-6">
                <div class="space-y-2">
                    <label for="surat_izin_meneliti" class="text-sm font-semibold text-zinc-700">Surat Izin Meneliti dari Universitas / Institusi <span class="text-zinc-400 font-normal">(Kosongkan jika tidak ingin diubah)</span></label>
                    <input type="file" name="surat_izin_meneliti" id="surat_izin_meneliti" accept=".pdf,.jpg,.jpeg,.png" class="block w-full text-sm text-zinc-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                    <p class="text-xs text-zinc-400">Format: PDF, JPG, PNG - Maks. 5MB</p>
                    @if(!empty($existingFiles['surat_izin']))
                        <div class="mt-2 text-xs text-emerald-600 font-semibold flex items-center gap-1.5">
                            <span>File Terunggah:</span>
                            <a href="{{ Storage::url($existingFiles['surat_izin']) }}" target="_blank" class="underline">Lihat Surat Izin Saat Ini</a>
                        </div>
                    @endif
                    @error('surat_izin_meneliti')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="space-y-2">
                    <label for="cv" class="text-sm font-semibold text-zinc-700">Curriculum Vitae (CV) <span class="text-zinc-400 font-normal">(Kosongkan jika tidak ingin diubah)</span></label>
                    <input type="file" name="cv" id="cv" accept=".pdf,.jpg,.jpeg,.png" class="block w-full text-sm text-zinc-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                    <p class="text-xs text-zinc-400">Format: PDF, JPG, PNG - Maks. 5MB</p>
                    @if(!empty($existingFiles['cv']))
                        <div class="mt-2 text-xs text-emerald-600 font-semibold flex items-center gap-1.5">
                            <span>File Terunggah:</span>
                            <a href="{{ Storage::url($existingFiles['cv']) }}" target="_blank" class="underline">Lihat CV Saat Ini</a>
                        </div>
                    @endif
                    @error('cv')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- ── SUBMIT ──────────────────────────────────────────────────── --}}
        <div class="flex flex-col sm:flex-row items-center gap-4">
            <button type="submit" 
                class="flex-1 bg-zinc-900 hover:bg-emerald-700 text-white font-semibold py-4 rounded-2xl transition-all duration-300 shadow-lg shadow-zinc-900/15 flex justify-center items-center gap-2 hover:-translate-y-0.5 hover:shadow-xl cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Simpan Perubahan
            </button>
            <a href="{{ route('dashboard') }}" class="sm:w-auto w-full text-center bg-zinc-100 hover:bg-zinc-200 text-zinc-700 font-medium py-4 px-8 rounded-2xl transition-all">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.12/build/js/intlTelInput.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('form-peneliti');
        if (!form) return;

        const nameInput = document.getElementById('nama_lengkap');
        const phoneDisplay = document.getElementById('nomor_hp_display');
        const phoneHidden = document.getElementById('nomor_hp');
        const startInput = document.getElementById('tanggal_mulai');
        const endInput = document.getElementById('tanggal_selesai');

        // Phone intl-tel-input
        const iti = window.intlTelInput(phoneDisplay, {
            initialCountry: "id",
            preferredCountries: ["id", "my", "sg"],
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.12/build/js/utils.js"
        });

        function updatePhoneValue() {
            phoneHidden.value = iti.getNumber();
        }

        phoneDisplay.addEventListener('change', updatePhoneValue);
        phoneDisplay.addEventListener('keyup', updatePhoneValue);
        phoneDisplay.addEventListener('countrychange', updatePhoneValue);

        phoneDisplay.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '');
            const countryData = iti.getSelectedCountryData();
            let maxLen = 13;
            if (countryData.dialCode === '62') {
                maxLen = 13;
            }
            if (this.value.length > maxLen) {
                this.value = this.value.slice(0, maxLen);
            }
            updatePhoneValue();
        });

        if (phoneHidden.value) {
            iti.setNumber(phoneHidden.value);
        }

        function toggleError(input, hasError, message) {
            let errorEl = input.nextElementSibling;
            if (!errorEl || !errorEl.classList.contains('js-error')) {
                errorEl = document.createElement('p');
                errorEl.className = 'text-xs text-red-650 mt-1 js-error';
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
        }

        nameInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^a-zA-Z\s]/g, '');
            if (this.value.trim() === '') toggleError(this, true, 'Nama lengkap tidak boleh kosong.');
            else toggleError(this, false, '');
        });

        // Dynamic dates check
        startInput.addEventListener('change', function() {
            if (this.value) {
                endInput.min = this.value;
                if (endInput.value && new Date(endInput.value) < new Date(this.value)) {
                    toggleError(endInput, true, 'Tanggal selesai tidak boleh kurang dari tanggal mulai.');
                } else {
                    toggleError(endInput, false, '');
                }
            }
        });

        endInput.addEventListener('change', function() {
            if (this.value && startInput.value) {
                if (new Date(this.value) < new Date(startInput.value)) {
                    toggleError(this, true, 'Tanggal selesai tidak boleh kurang dari tanggal mulai.');
                } else {
                    toggleError(this, false, '');
                }
            }
        });

        form.addEventListener('submit', function(e) {
            nameInput.dispatchEvent(new Event('input'));
            
            const today = new Date();
            today.setHours(0,0,0,0);
            
            if (startInput.value && new Date(startInput.value) < today) {
                e.preventDefault();
                toggleError(startInput, true, 'Tanggal mulai tidak boleh tanggal yang sudah lewat.');
                return;
            }
            if (endInput.value && startInput.value && new Date(endInput.value) < new Date(startInput.value)) {
                e.preventDefault();
                toggleError(endInput, true, 'Tanggal selesai tidak boleh kurang dari tanggal mulai.');
                return;
            }

            if (!phoneHidden.value || phoneHidden.value.trim() === '') {
                e.preventDefault();
                alert('Nomor HP wajib diisi dengan format yang benar.');
                return;
            }

            const errors = document.querySelectorAll('.js-error[style="display: block;"]');
            if (errors.length > 0) {
                e.preventDefault();
                alert('Mohon periksa kembali kelengkapan data pendaftaran Anda.');
            }
        });
    });
</script>
@endpush
