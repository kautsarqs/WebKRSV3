<x-dashboard-layout title="Kelola Peneliti">
    <x-admin-sidebar />

    <div class="space-y-6 py-4">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold tracking-tight text-zinc-900 font-space">Kelola Peneliti</h2>
                <p class="text-zinc-500 text-sm mt-1">Daftar permohonan izin penelitian di Kebun Raya Sambas.</p>
            </div>

            <div class="flex items-center gap-3 flex-wrap">

                <form id="bulk-delete-form-peneliti" method="POST" action="{{ route('admin.peneliti.bulk-delete') }}" style="display:none;">
                    @csrf
                    <input type="hidden" id="bulk-ids-json-peneliti" name="ids_json" value="">
                    <button type="button" id="btn-bulk-delete-peneliti" onclick="confirmBulkDeletePeneliti()"
                            class="px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl text-xs font-bold transition-all flex items-center gap-2 shadow-lg shadow-red-600/10">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Hapus Masal (<span id="bulk-count-peneliti">0</span>)
                    </button>
                </form>

                <a href="{{ route('admin.peneliti.export', ['format' => 'pdf']) }}" target="_blank"
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

        <div class="flex items-center gap-2 overflow-x-auto pb-2">
            <a href="{{ route('admin.peneliti.index') }}"
               class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ !request('status') ? 'bg-zinc-900 text-white' : 'bg-zinc-100 text-zinc-650 hover:bg-zinc-200' }}">
                Semua
            </a>
            @foreach(['pending' => 'Pending', 'disetujui' => 'Disetujui', 'ditolak' => 'Ditolak'] as $val => $label)
                <a href="{{ route('admin.peneliti.index', ['status' => $val]) }}"
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ request('status') === $val ? 'bg-zinc-900 text-white' : 'bg-zinc-100 text-zinc-650 hover:bg-zinc-200' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

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
                                        <div class="flex flex-col gap-1.5 items-start">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase">Disetujui</span>
                                            <form method="POST" action="{{ route('admin.peneliti.status-penelitian', $row->id) }}" id="status-penelitian-form-{{ $row->id }}">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status_penelitian" onchange="document.getElementById('status-penelitian-form-{{ $row->id }}').submit()"
                                                        class="text-[10px] font-bold rounded-lg border border-zinc-200 bg-white pl-2 pr-8 py-1 text-zinc-700 focus:outline-none cursor-pointer min-w-[100px]">
                                                    <option value="sedang" {{ $row->status_penelitian === 'sedang' ? 'selected' : '' }}>🟢 Meneliti</option>
                                                    <option value="selesai" {{ $row->status_penelitian === 'selesai' ? 'selected' : '' }}>⚪ Selesai</option>
                                                </select>
                                            </form>
                                        </div>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-red-50 text-red-700 border border-red-200 uppercase">Ditolak</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="grid grid-cols-2 gap-1.5 w-full max-w-[180px] mx-auto">

                                        <button type="button"
                                                onclick="openDetailPenelitiModal({{ json_encode($row) }})"
                                                class="col-span-1 px-2 py-1.5 bg-zinc-100 hover:bg-zinc-200 text-zinc-700 text-[10px] sm:text-xs font-bold rounded-lg transition-all text-center">
                                            Detail
                                        </button>

                                        <button type="button"
                                                onclick="confirmDeletePeneliti({{ $row->id }}, {{ json_encode($row->nama_lengkap) }})"
                                                class="col-span-1 px-2 py-1.5 bg-red-50 hover:bg-red-600 hover:text-white text-red-600 text-[10px] sm:text-xs font-bold rounded-lg transition-all text-center">
                                            Hapus
                                        </button>

                                        <form id="delete-form-peneliti-{{ $row->id }}" method="POST" action="{{ route('admin.peneliti.destroy', $row->id) }}" style="display:none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>

                                        @if($row->status === 'pending')
                                            <form method="POST" action="{{ route('admin.peneliti.status', $row->id) }}" class="col-span-1">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="disetujui">
                                                <button type="submit" class="w-full px-2 py-1.5 bg-emerald-50 hover:bg-emerald-600 hover:text-white text-emerald-700 text-[10px] sm:text-xs font-bold rounded-lg transition-all text-center">Setujui</button>
                                            </form>
                                            <button type="button"
                                                    onclick="openTolakModal('{{ route('admin.peneliti.status', $row->id) }}', {{ json_encode($row->nama_lengkap) }})"
                                                    class="col-span-1 px-2 py-1.5 bg-orange-50 hover:bg-orange-500 hover:text-white text-orange-700 text-[10px] sm:text-xs font-bold rounded-lg transition-all text-center">
                                                Tolak
                                            </button>
                                        @endif
                                        @if($row->status === 'ditolak')
                                            <button type="button"
                                                    onclick="openEditCatatanPenelitiModal('{{ route('admin.peneliti.status', $row->id) }}', {{ json_encode($row->nama_lengkap) }}, {{ json_encode($row->catatan_admin) }})"
                                                    class="col-span-2 px-2 py-1.5 bg-zinc-100 hover:bg-zinc-200 text-zinc-700 text-[10px] sm:text-xs font-bold rounded-lg transition-all text-center">
                                                Alasan Penolakan
                                            </button>
                                        @endif
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

    <div id="modal-tolak-peneliti" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.5); align-items:center; justify-content:center;">
        <div style="background:#fff; border-radius:20px; padding:28px 32px; max-width:460px; width:90%; box-shadow:0 25px 50px rgba(0,0,0,0.15);">
            <p id="modal-tolak-title-peneliti" style="font-size:16px;font-weight:700;color:#18181b;margin:0 0 6px 0;font-family:'Space Grotesk',sans-serif;">Tolak Permohonan Penelitian</p>
            <p id="tolak-subtitle" style="font-size:13px;color:#71717a;margin:0 0 18px 0;">Berikan alasan penolakan.</p>
            <form id="tolak-form-peneliti" method="POST" action="">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="ditolak">
                <div style="margin-bottom:16px;">
                    <label style="font-size:12px;font-weight:700;color:#3f3f46;display:block;margin-bottom:6px;">Catatan / Alasan Penolakan</label>
                    <textarea name="catatan_admin" id="tolak-catatan-peneliti" rows="4" required
                              style="width:100%;border:1px solid #e5e5e5;border-radius:12px;padding:10px 14px;font-size:13px;resize:none;box-sizing:border-box;outline:none;"
                              placeholder="Contoh: Dokumen surat pengantar tidak valid. Mohon unggah kembali."></textarea>
                </div>
                <div style="display:flex;gap:10px;justify-content:flex-end;">
                    <button type="button" onclick="closeTolakModal()" style="padding:8px 18px;background:#f4f4f5;border:none;border-radius:10px;font-size:13px;font-weight:600;color:#3f3f46;cursor:pointer;">Batal</button>
                    <button type="submit" id="modal-tolak-submit-btn-peneliti" style="padding:8px 18px;background:#dc2626;border:none;border-radius:10px;font-size:13px;font-weight:700;color:#fff;cursor:pointer;">Tolak Permohonan</button>
                </div>
            </form>
        </div>
    </div>

    <div id="detail-peneliti-modal" style="display:none; position:fixed; inset:0; z-index:9998; background:rgba(0,0,0,0.5); align-items:center; justify-content:center;">
        <div style="background:#fff; border-radius:24px; padding:0; max-width:560px; width:90%; max-height:85vh; box-shadow:0 25px 50px rgba(0,0,0,0.15); display:flex; flex-direction:column; overflow:hidden;">
            <div style="padding:24px 24px 16px; border-bottom:1px solid #f4f4f5; display:flex; align-items:center; justify-content:space-between;">
                <h3 style="font-size:17px;font-weight:700;color:#18181b;margin:0;">Detail Peneliti</h3>
                <button onclick="closeDetailPenelitiModal()" style="background:none;border:none;cursor:pointer;color:#a1a1aa;font-size:22px;line-height:1;">&times;</button>
            </div>
            <div id="detail-peneliti-content" style="padding:24px; overflow-y:auto; flex:1; display:flex; flex-direction:column; gap:16px; font-size:14px; color:#3f3f46;">

            </div>
            <div style="padding:16px 24px; border-top:1px solid #f4f4f5; display:flex; justify-content:flex-end;">
                <button onclick="closeDetailPenelitiModal()" style="padding:8px 20px;background:#18181b;border:none;border-radius:12px;font-size:13px;font-weight:700;color:#fff;cursor:pointer;">Tutup</button>
            </div>
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

    function openTolakModal(url, nama) {
        document.getElementById('tolak-form-peneliti').action = url;
        document.getElementById('tolak-subtitle').textContent = 'Berikan alasan penolakan untuk peneliti: ' + nama;
        document.getElementById('tolak-catatan-peneliti').value = '';
        document.getElementById('modal-tolak-title-peneliti').textContent = 'Tolak Permohonan Penelitian';
        document.getElementById('modal-tolak-submit-btn-peneliti').textContent = 'Tolak Permohonan';
        document.getElementById('modal-tolak-peneliti').style.display = 'flex';
    }
    function openEditCatatanPenelitiModal(url, nama, catatan) {
        document.getElementById('tolak-form-peneliti').action = url;
        document.getElementById('tolak-subtitle').textContent = 'Edit alasan penolakan untuk peneliti: ' + nama;
        document.getElementById('tolak-catatan-peneliti').value = catatan || '';
        document.getElementById('modal-tolak-title-peneliti').textContent = 'Edit Alasan Penolakan';
        document.getElementById('modal-tolak-submit-btn-peneliti').textContent = 'Simpan Perubahan';
        document.getElementById('modal-tolak-peneliti').style.display = 'flex';
    }
    function closeTolakModal() {
        document.getElementById('modal-tolak-peneliti').style.display = 'none';
    }

    function openDetailPenelitiModal(data) {
        var content = document.getElementById('detail-peneliti-content');

        var dateMulai = new Date(data.tanggal_mulai).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        var dateSelesai = new Date(data.tanggal_selesai).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });

        var docs = '';
        try {
            var parsed = JSON.parse(data.surat_pengantar);
            if (parsed && typeof parsed === 'object') {
                if (parsed.surat_izin) {
                    docs += `<a href="/storage/${parsed.surat_izin}" target="_blank" style="color:#059669;font-weight:700;text-decoration:underline;margin-right:12px;">Surat Izin</a>`;
                }
                if (parsed.cv) {
                    docs += `<a href="/storage/${parsed.cv}" target="_blank" style="color:#059669;font-weight:700;text-decoration:underline;">CV</a>`;
                }
            } else {
                docs = `<a href="/storage/${data.surat_pengantar}" target="_blank" style="color:#059669;font-weight:700;text-decoration:underline;">Surat Pengantar</a>`;
            }
        } catch(e) {
            docs = `<a href="/storage/${data.surat_pengantar}" target="_blank" style="color:#059669;font-weight:700;text-decoration:underline;">Surat Pengantar</a>`;
        }

        var statusBadge = '';
        if (data.status === 'pending') {
            statusBadge = '<span style="padding:2px 8px;font-size:11px;font-weight:700;background:#fef3c7;color:#b45309;border:1px solid #fde68a;border-radius:9999px;text-transform:uppercase;">Pending</span>';
        } else if (data.status === 'disetujui') {
            statusBadge = '<span style="padding:2px 8px;font-size:11px;font-weight:700;background:#d1fae5;color:#065f46;border:1px solid #a7f3d0;border-radius:9999px;text-transform:uppercase;">Disetujui</span>';
        } else {
            statusBadge = '<span style="padding:2px 8px;font-size:11px;font-weight:700;background:#fee2e2;color:#991b1b;border:1px solid #fecaca;border-radius:9999px;text-transform:uppercase;">Ditolak</span>';
        }

        var statusPenelitianText = data.status_penelitian === 'selesai' ? '⚪ Selesai Penelitian' : '🟢 Sedang Penelitian';

        content.innerHTML = `
            <div style="display:grid;grid-template-columns: 140px 1fr; gap:10px 16px; align-items: start;">
                <div style="font-weight:700;color:#18181b;">Nama Lengkap:</div><div>${data.nama_lengkap}</div>
                <div style="font-weight:700;color:#18181b;">Nomor HP/WA:</div><div>${data.nomor_hp}</div>
                <div style="font-weight:700;color:#18181b;">Institusi:</div><div>${data.institusi}</div>
                <div style="font-weight:700;color:#18181b;">Program Studi:</div><div>${data.program_studi || '-'} (${data.jenjang})</div>
                <div style="font-weight:700;color:#18181b;">Judul Penelitian:</div><div style="font-weight:600;color:#18181b;line-height:1.4;">${data.judul_penelitian}</div>
                <div style="font-weight:700;color:#18181b;">Bidang Penelitian:</div><div>${data.bidang_penelitian}</div>
                <div style="font-weight:700;color:#18181b;">Durasi Waktu:</div><div>${dateMulai} s/d ${dateSelesai}</div>
                <div style="font-weight:700;color:#18181b;">Tujuan Penelitian:</div><div style="line-height:1.4;white-space:pre-line;">${data.tujuan_penelitian || '-'}</div>
                <div style="font-weight:700;color:#18181b;">Lampiran Dokumen:</div><div>${docs}</div>
                <div style="font-weight:700;color:#18181b;">Status Izin:</div><div>${statusBadge}</div>
                ${data.status === 'disetujui' ? `<div style="font-weight:700;color:#18181b;">Status Aktivitas:</div><div style="font-weight:600;color:#18181b;">${statusPenelitianText}</div>` : ''}
                ${data.catatan_admin ? `<div style="font-weight:700;color:#991b1b;">Catatan Penolakan:</div><div style="color:#991b1b;font-weight:500;line-height:1.4;">${data.catatan_admin}</div>` : ''}
            </div>
        `;
        document.getElementById('detail-peneliti-modal').style.display = 'flex';
    }
    function closeDetailPenelitiModal() {
        document.getElementById('detail-peneliti-modal').style.display = 'none';
    }

    ['modal-confirm-delete-peneliti','modal-confirm-bulk-peneliti','modal-tolak-peneliti','detail-peneliti-modal'].forEach(function(id) {
        var el = document.getElementById(id);
        if (el) el.addEventListener('click', function(e) { if (e.target === this) this.style.display = 'none'; });
    });
    </script>
</x-dashboard-layout>
