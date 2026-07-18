<x-dashboard-layout title="Kelola Pengunjung">
    <x-admin-sidebar />

    <div class="space-y-6 py-4">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold tracking-tight text-zinc-900 font-space">Kelola Pengunjung</h2>
                <p class="text-zinc-500 text-sm mt-1">Daftar permohonan kunjungan rombongan ke Kebun Raya Sambas.</p>
            </div>
            
            <div class="flex items-center gap-3 flex-wrap">
                {{-- Bulk Delete Button --}}
                <form id="bulk-delete-form" method="POST" action="{{ route('admin.pengunjung.bulk-delete') }}" style="display:none;">
                    @csrf
                    <input type="hidden" id="bulk-ids-json" name="ids_json" value="">
                    <button type="button" id="btn-bulk-delete" onclick="confirmBulkDelete()"
                            class="px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl text-xs font-bold transition-all flex items-center gap-2 shadow-lg shadow-red-600/10">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Hapus Masal (<span id="bulk-count">0</span>)
                    </button>
                </form>

                <a href="{{ route('admin.pengunjung.export', ['format' => 'pdf']) }}" target="_blank"
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
            <a href="{{ route('admin.pengunjung.index') }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ !request('status') ? 'bg-zinc-900 text-white' : 'bg-zinc-100 text-zinc-650 hover:bg-zinc-200' }}">
                Semua
            </a>
            @foreach(['pending' => 'Pending', 'disetujui' => 'Disetujui', 'ditolak' => 'Ditolak'] as $val => $label)
                <a href="{{ route('admin.pengunjung.index', ['status' => $val]) }}" 
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
                                <input type="checkbox" id="check-all" class="rounded border-zinc-300" onchange="toggleAll(this)">
                            </th>
                            <th class="px-6 py-4">Nama Perwakilan</th>
                            <th class="px-6 py-4">Asal Daerah / Instansi</th>
                            <th class="px-6 py-4">Tanggal Kunjungan</th>
                            <th class="px-6 py-4 text-center">Jumlah Rombongan</th>
                            <th class="px-6 py-4">Tujuan</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200/60 text-sm text-zinc-700">
                        @forelse($pengunjungs as $row)
                            @php
                                $friends = $row->rombongan_details ?? [];
                                $mainPerson = [
                                    'nama' => $row->nama_lengkap,
                                    'nomor_hp' => $row->nomor_hp,
                                    'instansi' => $row->instansi ?? '-',
                                    'peran' => 'Perwakilan (Pendaftar)'
                                ];
                                $allMembers = array_merge([$mainPerson], array_map(function($f) {
                                    $f['peran'] = 'Rekan Rombongan';
                                    return $f;
                                }, $friends));
                            @endphp
                            <tr class="hover:bg-zinc-50/40 transition-colors">
                                <td class="px-6 py-4 text-center">
                                    <input type="checkbox" class="row-checkbox rounded border-zinc-300" 
                                           value="{{ $row->id }}" onchange="updateBulkBtn()">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-zinc-900">{{ $row->nama_lengkap }}</div>
                                    <div class="text-xs text-zinc-500 mt-0.5">{{ $row->nomor_hp }}</div>
                                </td>
                                <td class="px-6 py-4 font-semibold text-zinc-800">{{ $row->instansi ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    {{ \Carbon\Carbon::parse($row->tanggal_kunjungan)->translatedFormat('d F Y') }}
                                </td>
                                <td class="px-6 py-4 text-center font-bold text-zinc-900">
                                    {{ $row->jumlah_rombongan }} Orang
                                </td>
                                <td class="px-6 py-4 max-w-xs truncate" title="{{ $row->keperluan }}">
                                    {{ $row->keperluan ?? '-' }}
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
                                    <div class="grid grid-cols-2 gap-1.5 w-full max-w-[180px] mx-auto">
                                        {{-- Lihat Semua --}}
                                        <button type="button"
                                                onclick="openRombonganModal({{ json_encode($row->nama_lengkap) }}, {{ json_encode($allMembers) }})"
                                                class="col-span-1 px-2 py-1.5 bg-zinc-100 hover:bg-zinc-200 text-zinc-700 text-[10px] sm:text-xs font-bold rounded-lg transition-all text-center">
                                            Lihat Semua
                                        </button>

                                        {{-- Hapus button opens confirm modal --}}
                                        <button type="button"
                                                onclick="confirmDelete({{ $row->id }}, {{ json_encode($row->nama_lengkap) }})"
                                                class="col-span-1 px-2 py-1.5 bg-red-50 hover:bg-red-600 hover:text-white text-red-600 text-[10px] sm:text-xs font-bold rounded-lg transition-all text-center">
                                            Hapus
                                        </button>
                                        <form id="delete-form-{{ $row->id }}" method="POST" action="{{ route('admin.pengunjung.destroy', $row->id) }}" style="display:none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>

                                        @if($row->status === 'pending')
                                            <form method="POST" action="{{ route('admin.pengunjung.status', $row->id) }}" class="col-span-1">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="disetujui">
                                                <button type="submit" class="w-full px-2 py-1.5 bg-emerald-50 hover:bg-emerald-600 hover:text-white text-emerald-700 text-[10px] sm:text-xs font-bold rounded-lg transition-all text-center">Setujui</button>
                                            </form>
                                            <button type="button"
                                                    onclick="openTolakPengunjungModal('{{ route('admin.pengunjung.status', $row->id) }}', {{ json_encode($row->nama_lengkap) }})"
                                                    class="col-span-1 px-2 py-1.5 bg-orange-50 hover:bg-orange-500 hover:text-white text-orange-700 text-[10px] sm:text-xs font-bold rounded-lg transition-all text-center">
                                                Tolak
                                            </button>
                                        @endif
                                        @if($row->status === 'ditolak')
                                            <button type="button"
                                                    onclick="openEditCatatanPengunjungModal('{{ route('admin.pengunjung.status', $row->id) }}', {{ json_encode($row->nama_lengkap) }}, {{ json_encode($row->catatan_admin) }})"
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
                                    Tidak ada data pendaftaran pengunjung.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($pengunjungs->hasPages())
                <div class="px-6 py-4 border-t border-zinc-200/60">
                    {{ $pengunjungs->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- ===== CONFIRM DELETE MODAL ===== --}}
    <div id="modal-confirm-delete" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.5); display:none; align-items:center; justify-content:center;">
        <div style="background:#fff; border-radius:20px; padding:28px 32px; max-width:420px; width:90%; box-shadow:0 25px 50px rgba(0,0,0,0.15);">
            <div style="display:flex; align-items:center; gap:14px; margin-bottom:16px;">
                <div style="width:44px;height:44px;background:#fef2f2;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg style="width:22px;height:22px;color:#dc2626;" fill="none" stroke="#dc2626" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </div>
                <div>
                    <p style="font-size:16px;font-weight:700;color:#18181b;margin:0;">Hapus Data Pengunjung</p>
                    <p style="font-size:13px;color:#71717a;margin:4px 0 0 0;" id="confirm-delete-name">Apakah Anda yakin?</p>
                </div>
            </div>
            <p style="font-size:13px;color:#52525b;margin-bottom:22px;">Tindakan ini tidak dapat dibatalkan. Data pendaftaran pengunjung ini akan dihapus permanen.</p>
            <div style="display:flex;gap:10px;justify-content:flex-end;">
                <button onclick="closeConfirmDelete()" style="padding:8px 18px;background:#f4f4f5;border:none;border-radius:10px;font-size:13px;font-weight:600;color:#3f3f46;cursor:pointer;">Batal</button>
                <button id="btn-confirm-delete-yes" onclick="executeDelete()" style="padding:8px 18px;background:#dc2626;border:none;border-radius:10px;font-size:13px;font-weight:700;color:#fff;cursor:pointer;">Ya, Hapus</button>
            </div>
        </div>
    </div>

    {{-- ===== CONFIRM BULK DELETE MODAL ===== --}}
    <div id="modal-confirm-bulk" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.5); align-items:center; justify-content:center;">
        <div style="background:#fff; border-radius:20px; padding:28px 32px; max-width:420px; width:90%; box-shadow:0 25px 50px rgba(0,0,0,0.15);">
            <div style="display:flex; align-items:center; gap:14px; margin-bottom:16px;">
                <div style="width:44px;height:44px;background:#fef2f2;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg style="width:22px;height:22px;" fill="none" stroke="#dc2626" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </div>
                <div>
                    <p style="font-size:16px;font-weight:700;color:#18181b;margin:0;">Hapus Masal</p>
                    <p style="font-size:13px;color:#71717a;margin:4px 0 0 0;" id="confirm-bulk-count">Hapus data terpilih?</p>
                </div>
            </div>
            <p style="font-size:13px;color:#52525b;margin-bottom:22px;">Tindakan ini tidak dapat dibatalkan. Semua data yang dipilih akan dihapus permanen.</p>
            <div style="display:flex;gap:10px;justify-content:flex-end;">
                <button onclick="closeConfirmBulk()" style="padding:8px 18px;background:#f4f4f5;border:none;border-radius:10px;font-size:13px;font-weight:600;color:#3f3f46;cursor:pointer;">Batal</button>
                <button onclick="executeBulkDelete()" style="padding:8px 18px;background:#dc2626;border:none;border-radius:10px;font-size:13px;font-weight:700;color:#fff;cursor:pointer;">Ya, Hapus Semua</button>
            </div>
        </div>
    </div>

    {{-- ===== ROMBONGAN MODAL ===== --}}
    <div id="rombongan-modal" style="display:none; position:fixed; inset:0; z-index:9998; background:rgba(0,0,0,0.5); align-items:center; justify-content:center;">
        <div style="background:#fff; border-radius:24px; padding:0; max-width:560px; width:90%; max-height:80vh; box-shadow:0 25px 50px rgba(0,0,0,0.15); display:flex; flex-direction:column; overflow:hidden;">
            <div style="padding:24px 24px 16px; border-bottom:1px solid #f4f4f5; display:flex; align-items:center; justify-content:space-between;">
                <h3 id="modal-title-text" style="font-size:17px;font-weight:700;color:#18181b;margin:0;">Detail Rombongan</h3>
                <button onclick="closeRombonganModal()" style="background:none;border:none;cursor:pointer;color:#a1a1aa;font-size:22px;line-height:1;">&times;</button>
            </div>
            <div id="modal-members-list" style="padding:16px 24px; overflow-y:auto; flex:1; display:flex; flex-direction:column; gap:10px;"></div>
            <div style="padding:16px 24px; border-top:1px solid #f4f4f5; display:flex; justify-content:flex-end;">
                <button onclick="closeRombonganModal()" style="padding:8px 20px;background:#18181b;border:none;border-radius:12px;font-size:13px;font-weight:700;color:#fff;cursor:pointer;">Tutup</button>
            </div>
        </div>
    </div>

    {{-- ===== TOLAK MODAL (Pengunjung) ===== --}}
    <div id="modal-tolak-pengunjung" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.5); align-items:center; justify-content:center;">
        <div style="background:#fff; border-radius:20px; padding:28px 32px; max-width:460px; width:90%; box-shadow:0 25px 50px rgba(0,0,0,0.15);">
            <p id="modal-tolak-title-pengunjung" style="font-size:16px;font-weight:700;color:#18181b;margin:0 0 6px 0;font-family:'Space Grotesk',sans-serif;">Tolak Pendaftaran Pengunjung</p>
            <p id="tolak-subtitle-pengunjung" style="font-size:13px;color:#71717a;margin:0 0 18px 0;">Berikan alasan penolakan.</p>
            <form id="tolak-form-pengunjung" method="POST" action="">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="ditolak">
                <div style="margin-bottom:16px;">
                    <label style="font-size:12px;font-weight:700;color:#3f3f46;display:block;margin-bottom:6px;">Catatan / Alasan Penolakan</label>
                    <textarea name="catatan_admin" id="tolak-catatan-pengunjung" rows="4" required
                              style="width:100%;border:1px solid #e5e5e5;border-radius:12px;padding:10px 14px;font-size:13px;resize:none;box-sizing:border-box;outline:none;"
                              placeholder="Contoh: Tanggal kunjungan penuh atau keperluan tidak sesuai."></textarea>
                </div>
                <div style="display:flex;gap:10px;justify-content:flex-end;">
                    <button type="button" onclick="closeTolakPengunjungModal()" style="padding:8px 18px;background:#f4f4f5;border:none;border-radius:10px;font-size:13px;font-weight:600;color:#3f3f46;cursor:pointer;">Batal</button>
                    <button type="submit" id="modal-tolak-submit-btn-pengunjung" style="padding:8px 18px;background:#dc2626;border:none;border-radius:10px;font-size:13px;font-weight:700;color:#fff;cursor:pointer;">Tolak Permohonan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    var _deleteTargetId = null;

    // ---- Checkbox logic ----
    function toggleAll(chk) {
        document.querySelectorAll('.row-checkbox').forEach(function(cb) { cb.checked = chk.checked; });
        updateBulkBtn();
    }
    function updateBulkBtn() {
        var ids = getCheckedIds();
        var form = document.getElementById('bulk-delete-form');
        var countEl = document.getElementById('bulk-count');
        if (form) form.style.display = ids.length > 0 ? 'inline-flex' : 'none';
        if (countEl) countEl.textContent = ids.length;
    }
    function getCheckedIds() {
        var ids = [];
        document.querySelectorAll('.row-checkbox:checked').forEach(function(cb) { ids.push(parseInt(cb.value)); });
        return ids;
    }

    // ---- Single Delete ----
    function confirmDelete(id, nama) {
        _deleteTargetId = id;
        var el = document.getElementById('confirm-delete-name');
        if (el) el.textContent = 'Hapus data pendaftaran: ' + nama;
        var modal = document.getElementById('modal-confirm-delete');
        modal.style.display = 'flex';
    }
    function closeConfirmDelete() {
        document.getElementById('modal-confirm-delete').style.display = 'none';
        _deleteTargetId = null;
    }
    function executeDelete() {
        if (!_deleteTargetId) return;
        var form = document.getElementById('delete-form-' + _deleteTargetId);
        if (form) form.submit();
    }

    // ---- Bulk Delete ----
    function confirmBulkDelete() {
        var ids = getCheckedIds();
        if (ids.length === 0) return;
        document.getElementById('confirm-bulk-count').textContent = 'Hapus ' + ids.length + ' data terpilih?';
        document.getElementById('modal-confirm-bulk').style.display = 'flex';
    }
    function closeConfirmBulk() {
        document.getElementById('modal-confirm-bulk').style.display = 'none';
    }
    function executeBulkDelete() {
        var ids = getCheckedIds();
        document.getElementById('bulk-ids-json').value = JSON.stringify(ids);
        document.getElementById('bulk-delete-form').submit();
    }

    // ---- Rombongan Modal ----
    function openRombonganModal(nama, members) {
        document.getElementById('modal-title-text').textContent = 'Rombongan: ' + nama;
        var list = document.getElementById('modal-members-list');
        list.innerHTML = '';
        if (!members || members.length === 0) {
            list.innerHTML = '<p style="font-size:13px;color:#a1a1aa;">Tidak ada data rombongan.</p>';
        } else {
            members.forEach(function(m) {
                var isPerwakilan = m.peran && m.peran.includes('Perwakilan');
                var card = document.createElement('div');
                card.style.cssText = 'background:#f9f9f9;border:1px solid #e5e5e5;border-radius:14px;padding:14px 16px;display:flex;align-items:center;justify-content:space-between;gap:12px;';
                card.innerHTML =
                    '<div>' +
                    '<div style="font-size:14px;font-weight:700;color:#18181b;">' + (m.nama || '-') + '</div>' +
                    '<div style="font-size:12px;color:#71717a;margin-top:3px;">HP: ' + (m.nomor_hp || '-') + '</div>' +
                    '<div style="font-size:11px;color:#a1a1aa;margin-top:2px;">Asal Daerah / Instansi: ' + (m.instansi || '-') + '</div>' +
                    '</div>' +
                    '<span style="padding:2px 10px;border-radius:20px;font-size:10px;font-weight:700;border:1px solid;white-space:nowrap;' +
                    (isPerwakilan ? 'background:#18181b;color:#fff;border-color:#18181b;' : 'background:#fff;color:#52525b;border-color:#e5e5e5;') +
                    '">' + (m.peran || '-') + '</span>';
                list.appendChild(card);
            });
        }
        document.getElementById('rombongan-modal').style.display = 'flex';
    }
    function closeRombonganModal() {
        document.getElementById('rombongan-modal').style.display = 'none';
    }

    // Tolak Pengunjung Modal
    function openTolakPengunjungModal(url, nama) {
        document.getElementById('tolak-form-pengunjung').action = url;
        document.getElementById('tolak-subtitle-pengunjung').textContent = 'Berikan alasan penolakan untuk pengunjung: ' + nama;
        document.getElementById('tolak-catatan-pengunjung').value = '';
        document.getElementById('modal-tolak-title-pengunjung').textContent = 'Tolak Pendaftaran Pengunjung';
        document.getElementById('modal-tolak-submit-btn-pengunjung').textContent = 'Tolak Pendaftaran';
        document.getElementById('modal-tolak-pengunjung').style.display = 'flex';
    }
    function openEditCatatanPengunjungModal(url, nama, catatan) {
        document.getElementById('tolak-form-pengunjung').action = url;
        document.getElementById('tolak-subtitle-pengunjung').textContent = 'Edit alasan penolakan untuk pengunjung: ' + nama;
        document.getElementById('tolak-catatan-pengunjung').value = catatan || '';
        document.getElementById('modal-tolak-title-pengunjung').textContent = 'Edit Alasan Penolakan';
        document.getElementById('modal-tolak-submit-btn-pengunjung').textContent = 'Simpan Perubahan';
        document.getElementById('modal-tolak-pengunjung').style.display = 'flex';
    }
    function closeTolakPengunjungModal() {
        document.getElementById('modal-tolak-pengunjung').style.display = 'none';
    }

    // Close modals on backdrop click
    document.getElementById('modal-confirm-delete').addEventListener('click', function(e) {
        if (e.target === this) closeConfirmDelete();
    });
    document.getElementById('modal-confirm-bulk').addEventListener('click', function(e) {
        if (e.target === this) closeConfirmBulk();
    });
    document.getElementById('rombongan-modal').addEventListener('click', function(e) {
        if (e.target === this) closeRombonganModal();
    });
    document.getElementById('modal-tolak-pengunjung').addEventListener('click', function(e) {
        if (e.target === this) closeTolakPengunjungModal();
    });
    </script>
</x-dashboard-layout>
