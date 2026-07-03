<x-dashboard-layout title="Kelola Peneliti">
    <x-admin-sidebar />

    <div class="space-y-6 py-4">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold tracking-tight text-zinc-900 font-space">Kelola Peneliti</h2>
                <p class="text-zinc-500 text-sm mt-1">Daftar permohonan izin penelitian di Kebun Raya Sambas.</p>
            </div>
            
            <div class="flex items-center gap-3 flex-wrap">
                {{-- Bulk Delete Button --}}
                <form id="bulk-delete-form-peneliti" method="POST" action="{{ route('admin.penelitis.bulk-delete') }}" style="display:none;">
                    @csrf
                    <input type="hidden" id="bulk-ids-json-peneliti" name="ids_json" value="">
                    <button type="button" id="btn-bulk-delete-peneliti" onclick="confirmBulkDeletePeneliti()"
                            class="px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl text-xs font-bold transition-all flex items-center gap-2 shadow-lg shadow-red-600/10">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Hapus Masal (<span id="bulk-count-peneliti">0</span>)
                    </button>
                </form>

                <a href="{{ route('admin.penelitis.export', ['format' => 'pdf']) }}" target="_blank"
                   class="px-4 py-2.5 bg-zinc-900 hover:bg-zinc-800 text-white rounded-xl text-xs font-bold transition-all flex items-center gap-2 shadow-lg shadow-zinc-900/10">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Cetak PDF
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="flex items-start gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-2xl">
                <svg class="w-5 h-5 mt-0.5 shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-sm font-medium">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 px-5 py-4 rounded-2xl">
                <svg class="w-5 h-5 mt-0.5 shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-sm font-medium">{{ session('error') }}</p>
            </div>
        @endif

        {{-- Status Filters --}}
        <div class="flex items-center gap-2 overflow-x-auto pb-2">
            <a href="{{ route('admin.penelitis.index') }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ !request('status') ? 'bg-zinc-900 text-white' : 'bg-zinc-100 text-zinc-650 hover:bg-zinc-200' }}">
                Semua
            </a>
            @foreach(['pending' => 'Pending', 'disetujui' => 'Disetujui', 'ditolak' => 'Ditolak'] as $val => $label)
                <a href="{{ route('admin.penelitis.index', ['status' => $val]) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ request('status') === $val ? 'bg-zinc-900 text-white' : 'bg-zinc-100 text-zinc-650 hover:bg-zinc-200' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        {{-- Table Card --}}
        <div class="bg-white border border-zinc-200/80 rounded-3xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-zinc-50 border-b border-zinc-200 text-zinc-400 text-[10px] font-bold uppercase tracking-wider">
                            <th class="px-6 py-4 w-12 text-center">
                                <input type="checkbox" id="check-all-peneliti" class="rounded border-zinc-300" onchange="toggleAllPeneliti(this)">
                            </th>
                            <th class="px-6 py-4">Peneliti &amp; Kontak</th>
                            <th class="px-6 py-4">Institusi</th>
                            <th class="px-6 py-4">Judul Penelitian</th>
                            <th class="px-6 py-4">Mulai / Selesai</th>
                            <th class="px-6 py-4">Surat</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200/60 text-sm text-zinc-700">
                        @forelse($penelitis as $row)
                            <tr class="hover:bg-zinc-50/40 transition-colors">
                                <td class="px-6 py-4 text-center">
                                    <input type="checkbox" class="row-checkbox-peneliti rounded border-zinc-300" 
                                           value="{{ $row->id }}" onchange="updateBulkBtnPeneliti()">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-zinc-900">{{ $row->nama_lengkap }}</div>
                                    <div class="text-xs text-zinc-500 mt-0.5">{{ $row->nomor_hp }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-zinc-800">{{ $row->institusi }}</div>
                                    <div class="text-xs text-zinc-500 mt-0.5">{{ $row->program_studi ?? '-' }} ({{ $row->jenjang }})</div>
                                </td>
                                <td class="px-6 py-4 max-w-xs">
                                    <div class="font-medium text-zinc-900 truncate" title="{{ $row->judul_penelitian }}">{{ $row->judul_penelitian }}</div>
                                    <div class="text-xs text-zinc-500 mt-0.5 truncate">{{ $row->bidang_penelitian }}</div>
                                </td>
                                <td class="px-6 py-4 text-xs font-medium text-zinc-650">
                                    <div>{{ $row->tanggal_mulai->format('d-m-Y') }}</div>
                                    <div class="text-zinc-400 text-[10px] mt-0.5">s/d</div>
                                    <div class="mt-0.5">{{ $row->tanggal_selesai->format('d-m-Y') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $docs = json_decode($row->surat_pengantar, true);
                                    @endphp
                                    @if(is_array($docs))
                                        <div class="flex flex-col gap-1 items-start">
                                            @if(!empty($docs['surat_izin']))
                                                <a href="{{ Storage::url($docs['surat_izin']) }}" target="_blank" 
                                                   class="inline-flex items-center gap-1 text-xs text-emerald-600 hover:text-emerald-700 font-bold underline">
                                                    Surat Izin
                                                </a>
                                            @endif
                                            @if(!empty($docs['cv']))
                                                <a href="{{ Storage::url($docs['cv']) }}" target="_blank" 
                                                   class="inline-flex items-center gap-1 text-xs text-emerald-600 hover:text-emerald-700 font-bold underline">
                                                    CV
                                                </a>
                                            @endif
                                        </div>
                                    @elseif($row->surat_pengantar)
                                        <a href="{{ Storage::url($row->surat_pengantar) }}" target="_blank" 
                                           class="inline-flex items-center gap-1 text-xs text-emerald-600 hover:text-emerald-700 font-bold underline">
                                            Unduh
                                        </a>
                                    @else
                                        <span class="text-zinc-400 text-xs">Tidak ada</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($row->status === 'pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 uppercase">Pending</span>
                                    @elseif($row->status === 'disetujui')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase">Disetujui</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-red-50 text-red-700 border border-red-200 uppercase">Ditolak</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2 flex-wrap">
                                        @if($row->status === 'pending')
                                            <form method="POST" action="{{ route('admin.penelitis.status', $row->id) }}" style="display:inline;">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="disetujui">
                                                <button type="submit" class="px-2.5 py-1.5 bg-emerald-50 hover:bg-emerald-600 hover:text-white text-emerald-700 text-xs font-bold rounded-lg transition-all">Setujui</button>
                                            </form>
                                            <button type="button"
                                                    onclick="openTolakModal('{{ route('admin.penelitis.status', $row->id) }}', {{ json_encode($row->nama_lengkap) }})"
                                                    class="px-2.5 py-1.5 bg-orange-50 hover:bg-orange-500 hover:text-white text-orange-700 text-xs font-bold rounded-lg transition-all">
                                                Tolak
                                            </button>
                                        @endif

                                        <button type="button"
                                                onclick="confirmDeletePeneliti({{ $row->id }}, {{ json_encode($row->nama_lengkap) }})"
                                                class="px-2.5 py-1.5 bg-red-50 hover:bg-red-600 hover:text-white text-red-600 text-xs font-bold rounded-lg transition-all">
                                            Hapus
                                        </button>
                                        <form id="delete-form-peneliti-{{ $row->id }}" method="POST" action="{{ route('admin.penelitis.destroy', $row->id) }}" style="display:none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-zinc-400">
                                    Tidak ada data pendaftaran peneliti.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($penelitis->hasPages())
                <div class="px-6 py-4 border-t border-zinc-200/60">
                    {{ $penelitis->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- ===== CONFIRM DELETE MODAL ===== --}}
    <div id="modal-confirm-delete-peneliti" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.5); align-items:center; justify-content:center;">
        <div style="background:#fff; border-radius:20px; padding:28px 32px; max-width:420px; width:90%; box-shadow:0 25px 50px rgba(0,0,0,0.15);">
            <div style="display:flex; align-items:center; gap:14px; margin-bottom:16px;">
                <div style="width:44px;height:44px;background:#fef2f2;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg style="width:22px;height:22px;" fill="none" stroke="#dc2626" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </div>
                <div>
                    <p style="font-size:16px;font-weight:700;color:#18181b;margin:0;">Hapus Data Peneliti</p>
                    <p style="font-size:13px;color:#71717a;margin:4px 0 0 0;" id="confirm-delete-peneliti-name">Apakah Anda yakin?</p>
                </div>
            </div>
            <p style="font-size:13px;color:#52525b;margin-bottom:22px;">Data peneliti dan lampiran surat pengantar akan dihapus permanen.</p>
            <div style="display:flex;gap:10px;justify-content:flex-end;">
                <button onclick="closeConfirmDeletePeneliti()" style="padding:8px 18px;background:#f4f4f5;border:none;border-radius:10px;font-size:13px;font-weight:600;color:#3f3f46;cursor:pointer;">Batal</button>
                <button onclick="executeDeletePeneliti()" style="padding:8px 18px;background:#dc2626;border:none;border-radius:10px;font-size:13px;font-weight:700;color:#fff;cursor:pointer;">Ya, Hapus</button>
            </div>
        </div>
    </div>

    {{-- ===== CONFIRM BULK DELETE MODAL ===== --}}
    <div id="modal-confirm-bulk-peneliti" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.5); align-items:center; justify-content:center;">
        <div style="background:#fff; border-radius:20px; padding:28px 32px; max-width:420px; width:90%; box-shadow:0 25px 50px rgba(0,0,0,0.15);">
            <div style="display:flex; align-items:center; gap:14px; margin-bottom:16px;">
                <div style="width:44px;height:44px;background:#fef2f2;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg style="width:22px;height:22px;" fill="none" stroke="#dc2626" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </div>
                <div>
                    <p style="font-size:16px;font-weight:700;color:#18181b;margin:0;">Hapus Masal Peneliti</p>
                    <p style="font-size:13px;color:#71717a;margin:4px 0 0 0;" id="confirm-bulk-count-peneliti">Hapus data terpilih?</p>
                </div>
            </div>
            <p style="font-size:13px;color:#52525b;margin-bottom:22px;">Semua data peneliti terpilih beserta lampirannya akan dihapus permanen.</p>
            <div style="display:flex;gap:10px;justify-content:flex-end;">
                <button onclick="closeConfirmBulkPeneliti()" style="padding:8px 18px;background:#f4f4f5;border:none;border-radius:10px;font-size:13px;font-weight:600;color:#3f3f46;cursor:pointer;">Batal</button>
                <button onclick="executeBulkDeletePeneliti()" style="padding:8px 18px;background:#dc2626;border:none;border-radius:10px;font-size:13px;font-weight:700;color:#fff;cursor:pointer;">Ya, Hapus Semua</button>
            </div>
        </div>
    </div>

    {{-- ===== TOLAK MODAL (Peneliti) ===== --}}
    <div id="modal-tolak-peneliti" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.5); align-items:center; justify-content:center;">
        <div style="background:#fff; border-radius:20px; padding:28px 32px; max-width:460px; width:90%; box-shadow:0 25px 50px rgba(0,0,0,0.15);">
            <p style="font-size:16px;font-weight:700;color:#18181b;margin:0 0 6px 0;">Tolak Permohonan Penelitian</p>
            <p id="tolak-subtitle" style="font-size:13px;color:#71717a;margin:0 0 18px 0;">Berikan alasan penolakan.</p>
            <form id="tolak-form-peneliti" method="POST" action="">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="ditolak">
                <div style="margin-bottom:16px;">
                    <label style="font-size:12px;font-weight:700;color:#3f3f46;display:block;margin-bottom:6px;">Catatan / Alasan Penolakan</label>
                    <textarea name="catatan_admin" rows="4" required
                              style="width:100%;border:1px solid #e5e5e5;border-radius:12px;padding:10px 14px;font-size:13px;resize:none;box-sizing:border-box;"
                              placeholder="Contoh: Dokumen surat pengantar tidak valid. Mohon unggah kembali."></textarea>
                </div>
                <div style="display:flex;gap:10px;justify-content:flex-end;">
                    <button type="button" onclick="closeTolakModal()" style="padding:8px 18px;background:#f4f4f5;border:none;border-radius:10px;font-size:13px;font-weight:600;color:#3f3f46;cursor:pointer;">Batal</button>
                    <button type="submit" style="padding:8px 18px;background:#dc2626;border:none;border-radius:10px;font-size:13px;font-weight:700;color:#fff;cursor:pointer;">Tolak Permohonan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    var _deletePenelitiId = null;

    function toggleAllPeneliti(chk) {
        document.querySelectorAll('.row-checkbox-peneliti').forEach(function(cb) { cb.checked = chk.checked; });
        updateBulkBtnPeneliti();
    }
    function updateBulkBtnPeneliti() {
        var ids = getCheckedIdsPeneliti();
        var form = document.getElementById('bulk-delete-form-peneliti');
        var countEl = document.getElementById('bulk-count-peneliti');
        if (form) form.style.display = ids.length > 0 ? 'inline-flex' : 'none';
        if (countEl) countEl.textContent = ids.length;
    }
    function getCheckedIdsPeneliti() {
        var ids = [];
        document.querySelectorAll('.row-checkbox-peneliti:checked').forEach(function(cb) { ids.push(parseInt(cb.value)); });
        return ids;
    }

    // Single Delete
    function confirmDeletePeneliti(id, nama) {
        _deletePenelitiId = id;
        var el = document.getElementById('confirm-delete-peneliti-name');
        if (el) el.textContent = 'Hapus data: ' + nama;
        document.getElementById('modal-confirm-delete-peneliti').style.display = 'flex';
    }
    function closeConfirmDeletePeneliti() {
        document.getElementById('modal-confirm-delete-peneliti').style.display = 'none';
        _deletePenelitiId = null;
    }
    function executeDeletePeneliti() {
        if (!_deletePenelitiId) return;
        document.getElementById('delete-form-peneliti-' + _deletePenelitiId).submit();
    }

    // Bulk Delete
    function confirmBulkDeletePeneliti() {
        var ids = getCheckedIdsPeneliti();
        if (ids.length === 0) return;
        document.getElementById('confirm-bulk-count-peneliti').textContent = 'Hapus ' + ids.length + ' data peneliti terpilih?';
        document.getElementById('modal-confirm-bulk-peneliti').style.display = 'flex';
    }
    function closeConfirmBulkPeneliti() {
        document.getElementById('modal-confirm-bulk-peneliti').style.display = 'none';
    }
    function executeBulkDeletePeneliti() {
        var ids = getCheckedIdsPeneliti();
        document.getElementById('bulk-ids-json-peneliti').value = JSON.stringify(ids);
        document.getElementById('bulk-delete-form-peneliti').submit();
    }

    // Tolak Modal
    function openTolakModal(url, nama) {
        document.getElementById('tolak-form-peneliti').action = url;
        document.getElementById('tolak-subtitle').textContent = 'Berikan alasan penolakan untuk peneliti: ' + nama;
        document.getElementById('modal-tolak-peneliti').style.display = 'flex';
    }
    function closeTolakModal() {
        document.getElementById('modal-tolak-peneliti').style.display = 'none';
    }

    // Close on backdrop click
    ['modal-confirm-delete-peneliti','modal-confirm-bulk-peneliti','modal-tolak-peneliti'].forEach(function(id) {
        var el = document.getElementById(id);
        if (el) el.addEventListener('click', function(e) { if (e.target === this) this.style.display = 'none'; });
    });
    </script>
</x-dashboard-layout>
