@extends('layouts.landing')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.12/build/css/intlTelInput.css">
<style>
    .iti { width: 100% !important; }
    .iti__selected-dial-code {
        font-size: 0.875rem !important;
        color: #27272a !important;
        font-weight: 500;
    }
    .rombongan-row .iti__selected-dial-code {
        font-size: 0.75rem !important;
    }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto px-4 py-12">
    <div class="text-center mb-10">
        <h2 class="text-3xl font-bold tracking-tight text-zinc-950 font-space mb-2">Formulir Pendaftaran Pengunjung</h2>
        <p class="text-zinc-650 text-sm max-w-lg mx-auto">Silakan lengkapi data kunjungan rombongan Anda ke Kebun Raya Sambas di bawah ini.</p>
    </div>

    @guest
    <div class="bg-white rounded-3xl shadow-xl shadow-zinc-200/50 border border-zinc-100 overflow-hidden p-8 text-center">
        <div class="max-w-md mx-auto">
            <svg class="w-16 h-16 text-zinc-300 mx-auto mb-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H4.5a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
            </svg>
            <h2 class="text-2xl font-bold mb-2 font-heading">Akses Dibatasi</h2>
            <p class="text-zinc-500 mb-6">
                Untuk dapat mengisi formulir pengunjung, silakan masuk ke akun Anda terlebih dahulu.
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
            <p class="text-xs text-amber-700 mt-1">Silakan verifikasi email Anda terlebih dahulu sebelum mengajukan permohonan kunjungan.</p>
        </div>
        <a href="{{ route('verification.notice') }}" class="px-5 py-2.5 bg-zinc-900 hover:bg-zinc-800 text-white rounded-xl text-xs font-bold transition-all shadow-md">
            Verifikasi Email
        </a>
    </div>
    @else
    <div class="bg-white rounded-3xl shadow-xl shadow-zinc-200/50 border border-zinc-100 overflow-hidden p-8">
        <form id="form-pendaftaran" action="{{ route('pendaftaran.pengunjung.store') }}" method="POST" class="space-y-6">
            @csrf

            <h3 class="text-base font-bold text-zinc-800 border-b border-zinc-100 pb-2 mb-4">Informasi Utama Pendaftar</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="nama_lengkap" class="text-sm font-medium text-zinc-700">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap') }}" class="w-full rounded-xl border-zinc-200 focus:border-zinc-900 focus:ring-zinc-900 text-sm px-4 py-3 @error('nama_lengkap') border-red-500 @enderror" placeholder="Masukkan nama lengkap" required>
                    @error('nama_lengkap')<p class="text-xs text-red-600 mt-1 server-error">{{ $message }}</p>@enderror
                </div>
                <div class="space-y-2">
                    <label for="nomor_hp_display" class="text-sm font-medium text-zinc-700">Nomor HP <span class="text-red-500">*</span></label>
                    <input type="tel" id="nomor_hp_display" class="w-full rounded-xl border-zinc-200 focus:border-zinc-900 focus:ring-zinc-900 text-sm px-4 py-3" placeholder="8xxxxxxxxxx" required>
                    <input type="hidden" name="nomor_hp" id="nomor_hp" value="{{ old('nomor_hp') }}">
                    @error('nomor_hp')<p class="text-xs text-red-600 mt-1 server-error">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="instansi" class="text-sm font-medium text-zinc-700">Asal Daerah / Instansi <span class="text-red-500">*</span></label>
                    <input type="text" name="instansi" id="instansi" value="{{ old('instansi') }}" class="w-full rounded-xl border-zinc-200 focus:border-zinc-900 focus:ring-zinc-900 text-sm px-4 py-3 @error('instansi') border-red-500 @enderror" placeholder="Contoh: Sambas, Pontianak, Universitas Tanjungpura, dll." required>
                    @error('instansi')<p class="text-xs text-red-600 mt-1 server-error">{{ $message }}</p>@enderror
                </div>
                <div class="space-y-2">
                    <label for="tanggal_kunjungan" class="text-sm font-medium text-zinc-700">Tanggal Kunjungan <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_kunjungan" id="tanggal_kunjungan" value="{{ old('tanggal_kunjungan') }}" min="{{ date('Y-m-d') }}" class="w-full rounded-xl border-zinc-200 focus:border-zinc-900 focus:ring-zinc-900 text-sm px-4 py-3 @error('tanggal_kunjungan') border-red-500 @enderror" required>
                    @error('tanggal_kunjungan')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-sm font-medium text-zinc-700">Total Jumlah Rombongan</label>
                <input type="text" id="display_jumlah_rombongan" class="w-full rounded-xl border-zinc-200 bg-zinc-50 text-zinc-500 text-sm px-4 py-3 font-bold" value="1 Orang" readonly>
            </div>

            <div class="space-y-2">
                <label for="keperluan" class="text-sm font-medium text-zinc-700">Tujuan Berkunjung / Keperluan <span class="text-red-500">*</span></label>
                <textarea name="keperluan" id="keperluan" rows="3" class="w-full rounded-xl border-zinc-200 focus:border-zinc-900 focus:ring-zinc-900 text-sm px-4 py-3 @error('keperluan') border-red-500 @enderror" placeholder="Contoh: Wisata keluarga, Penelitian tanaman, Rekreasi, dll." required>{{ old('keperluan') }}</textarea>
                @error('keperluan')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- ── TAMBAH ANGGOTA ROMBONGAN ─────────────────────────────────── --}}
            <div class="pt-6 border-t border-zinc-100">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-base font-bold text-zinc-800">Anggota Rombongan Tambahan</h3>
                        <p class="text-xs text-zinc-500 mt-0.5">Daftarkan rekan Anda (jika ada) di bawah ini.</p>
                    </div>
                    <button type="button" id="btn-add-rombongan" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-zinc-900 hover:bg-zinc-800 text-white rounded-xl text-xs font-bold transition-all shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        Tambah Rekan
                    </button>
                </div>

                <div id="rombongan-list" class="space-y-4">
                    {{-- Row list --}}
                </div>
            </div>

            <div class="pt-4 border-t border-zinc-100 mt-6">
                <button type="submit" class="w-full bg-zinc-900 hover:bg-zinc-800 text-white font-medium py-3 rounded-xl transition-all shadow-lg shadow-zinc-900/10 flex justify-center items-center gap-2">
                    <span>Kirim Pendaftaran</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </div>
        </form>
    </div>
    @endif
    @endauth
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.12/build/js/intlTelInput.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('form-pendaftaran');
        if (!form) return;

        const nameInput = document.getElementById('nama_lengkap');
        const phoneDisplay = document.getElementById('nomor_hp_display');
        const phoneHidden = document.getElementById('nomor_hp');
        const displayTotalInput = document.getElementById('display_jumlah_rombongan');
        const rombonganList = document.getElementById('rombongan-list');
        const btnAdd = document.getElementById('btn-add-rombongan');

        let rombonganIndex = 0;

        // Initialize phone country flags
        function initIntlPhone(input, hiddenInput) {
            const iti = window.intlTelInput(input, {
                initialCountry: "id",
                preferredCountries: ["id", "my", "sg"],
                separateDialCode: true,
                formatOnDisplay: false,
                utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.12/build/js/utils.js"
            });
            
            function updateValue() {
                const countryData = iti.getSelectedCountryData();
                const dialCode = countryData.dialCode || '';
                let nationalNumber = input.value.replace(/\D/g, '');
                if (nationalNumber.startsWith('0')) {
                    nationalNumber = nationalNumber.substring(1);
                }
                
                if (nationalNumber) {
                    hiddenInput.value = '+' + dialCode + nationalNumber;
                } else {
                    hiddenInput.value = '';
                }
            }
            
            input.addEventListener('change', updateValue);
            input.addEventListener('keyup', updateValue);
            input.addEventListener('countrychange', updateValue);
            
            input.addEventListener('input', function() {
                this.value = this.value.replace(/\D/g, '');
                if (this.value.startsWith('0')) {
                    this.value = this.value.substring(1);
                }
                const countryData = iti.getSelectedCountryData();
                let maxLen = 13;
                if (countryData.dialCode === '62') {
                    maxLen = 13;
                }
                if (this.value.length > maxLen) {
                    this.value = this.value.slice(0, maxLen);
                }
                updateValue();
            });

            if (hiddenInput.value) {
                iti.setNumber(hiddenInput.value);
                input.value = input.value.replace(/[\s-]/g, '');
                if (input.value.startsWith('0')) {
                    input.value = input.value.substring(1);
                }
                // Update the hidden field synchronously with the cleaned formatted value
                updateValue();
            }
        }

        initIntlPhone(phoneDisplay, phoneHidden);

        function updateTotalCount() {
            const rows = rombonganList.querySelectorAll('.rombongan-row');
            const total = 1 + rows.length;
            displayTotalInput.value = `${total} Orang`;
        }

        function addRombonganRow() {
            const index = rombonganIndex++;
            const rowHtml = `
                <div class="rombongan-row bg-zinc-50 border border-zinc-150 rounded-2xl p-4 relative group transition-all" data-index="${index}">
                    <button type="button" class="btn-remove-row absolute top-4 right-4 text-zinc-400 hover:text-red-650 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mr-6">
                        <div class="space-y-1">
                            <label class="text-xs font-semibold text-zinc-650">Nama Anggota <span class="text-red-500">*</span></label>
                            <input type="text" name="rombongan[${index}][nama]" class="row-name w-full rounded-lg border-zinc-200 focus:ring-zinc-900 focus:border-zinc-900 text-xs px-3 py-2" placeholder="Nama Rekan Rombongan" required>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-semibold text-zinc-650">Nomor HP</label>
                            <input type="tel" class="row-phone-display w-full rounded-lg border-zinc-200 focus:ring-zinc-900 focus:border-zinc-900 text-xs px-3 py-2" placeholder="Nomor HP">
                            <input type="hidden" name="rombongan[${index}][nomor_hp]" class="row-phone-hidden">
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-semibold text-zinc-650">Asal Daerah / Instansi</label>
                            <input type="text" name="rombongan[${index}][instansi]" class="row-instansi w-full rounded-lg border-zinc-200 focus:ring-zinc-900 focus:border-zinc-900 text-xs px-3 py-2" placeholder="Kosongkan jika sama">
                        </div>
                    </div>
                </div>
            `;
            rombonganList.insertAdjacentHTML('beforeend', rowHtml);
            updateTotalCount();

            // Bind listener to the newly created row
            const newRow = rombonganList.lastElementChild;
            
            // Name validation
            newRow.querySelector('.row-name').addEventListener('input', function() {
                this.value = this.value.replace(/[^a-zA-Z\s]/g, '');
            });

            // Initialize dynamic intl-phone input
            initIntlPhone(newRow.querySelector('.row-phone-display'), newRow.querySelector('.row-phone-hidden'));

            // Remove row button listener
            newRow.querySelector('.btn-remove-row').addEventListener('click', function() {
                newRow.remove();
                updateTotalCount();
            });
        }

        btnAdd.addEventListener('click', addRombonganRow);

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
            const serverError = input.parentNode.querySelector('.server-error');
            if (serverError) serverError.style.display = 'none';
        }

        // Setup main fields constraints
        nameInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^a-zA-Z\s]/g, '');
            if (this.value.trim() === '') toggleError(this, true, 'Nama lengkap tidak boleh kosong.');
            else toggleError(this, false, '');
        });

        const dateInput = document.getElementById('tanggal_kunjungan');

        dateInput.addEventListener('input', function() {
            if (!this.value) {
                toggleError(this, true, 'Tanggal kunjungan wajib diisi.');
                return;
            }
            const selectedDate = new Date(this.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            selectedDate.setHours(0, 0, 0, 0);
            
            if (selectedDate < today) {
                toggleError(this, true, 'Tanggal kunjungan tidak boleh tanggal yang sudah lewat.');
            } else {
                toggleError(this, false, '');
            }
        });

        form.addEventListener('submit', function(e) {
            nameInput.dispatchEvent(new Event('input'));
            dateInput.dispatchEvent(new Event('input'));

            // Force update main phone and rombongan phone hidden inputs before validation
            phoneDisplay.dispatchEvent(new Event('input'));
            document.querySelectorAll('.row-phone-display').forEach(el => {
                el.dispatchEvent(new Event('input'));
            });

            // Phone validation
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
