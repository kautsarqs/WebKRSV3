<x-dashboard-layout title="Kelola Peta">
    <x-admin-sidebar />

    <div class="space-y-6">

        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold tracking-tight text-zinc-900 font-space">Daftar Marker Peta</h2>
                <p class="text-zinc-500 text-sm mt-1 font-inter">Kelola semua marker yang ditampilkan di peta digital.</p>
            </div>

            <div class="flex items-center gap-3">
                <form action="{{ route('admin.maps.index') }}" method="GET" class="relative group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-3 h-5 w-5 text-zinc-400 group-hover:text-zinc-600 transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    <input
                        type="search"
                        name="search"
                        placeholder="Cari marker..."
                        value="{{ request('search') }}"
                        class="pl-10 pr-4 py-2.5 w-full md:w-[280px] bg-white/60 backdrop-blur-sm border border-zinc-200 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 outline-none text-zinc-800 text-sm shadow-sm transition-all"
                    />
                </form>

                <a href="{{ route('admin.maps.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-zinc-900 text-white text-sm font-bold font-space rounded-xl hover:bg-zinc-800 transition-all shadow-lg shadow-zinc-900/20 transform hover:-translate-y-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Tambah Marker
                </a>
            </div>
        </div>

        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="bg-green-100/80 backdrop-blur-sm border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm font-bold flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="bg-red-100/80 backdrop-blur-sm border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm font-bold flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white/60 backdrop-blur-xl border border-zinc-200/60 rounded-3xl shadow-xl shadow-zinc-200/40 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-zinc-50/50 border-b border-zinc-200/60">
                        <tr>
                            <th class="px-6 py-4 font-bold text-zinc-900 font-space tracking-wide">Nama</th>
                            <th class="px-6 py-4 font-bold text-zinc-900 font-space tracking-wide">Foto / Simbol</th>
                            <th class="px-6 py-4 font-bold text-zinc-900 font-space tracking-wide">Koordinat</th>
                            <th class="px-6 py-4 font-bold text-zinc-900 font-space tracking-wide">Tipe</th>
                            <th class="px-6 py-4 font-bold text-zinc-900 font-space tracking-wide">Warna</th>
                            <th class="px-6 py-4 font-bold text-zinc-900 font-space tracking-wide text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200/60">
                        @forelse ($markers as $marker)
                            <tr class="hover:bg-white/40 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-zinc-700 font-space group-hover:text-zinc-900 transition-colors">{{ $marker->name }}</div>
                                    @if($marker->description)
                                        <div class="text-xs text-zinc-500 mt-1 line-clamp-1">{{ Str::limit($marker->description, 50) }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($marker->geometry_type === 'polygon')
                                        @php
                                            $normTypeIdx = strtolower(str_replace([' ', '-', '_'], '', $marker->type ?? ''));
                                            $isBatas = str_contains($normTypeIdx, 'batas');
                                        @endphp
                                        <div class="w-16 h-16 bg-zinc-50 rounded-lg border border-zinc-200 flex items-center justify-center overflow-hidden shadow-xs" title="{{ $marker->type }}">
                                            <svg class="w-11 h-11" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M12 42 C10 28, 16 10, 32 9 C48 8, 56 22, 54 38 C52 50, 40 54, 32 54 C22 54, 14 52, 12 42 Z"
                                                      stroke="{{ $marker->color ?? '#3b82f6' }}" stroke-width="3.5"
                                                      stroke-dasharray="{{ $isBatas ? '8 5' : 'none' }}" stroke-linecap="round" stroke-linejoin="round"
                                                      fill="{{ $marker->color ?? '#3b82f6' }}" fill-opacity="{{ $isBatas ? '0.08' : '0.18' }}"/>
                                            </svg>
                                        </div>
                                    @elseif($marker->geometry_type === 'linestring' || $marker->geometry_type === 'polyline')
                                        @php
                                            $typeNormIdx = strtolower(str_replace([' ','-'], '_', $marker->type));
                                            $lineColor = ($typeNormIdx === 'jalan_utama') ? '#b8b8b8' : (($typeNormIdx === 'jalan_lain') ? '#c8c8c8' : ($marker->color ?? '#3b82f6'));
                                            $lineWidth = ($typeNormIdx === 'jalan_utama') ? '5.5' : '2.5';
                                        @endphp
                                        <div class="w-16 h-16 bg-zinc-50 rounded-lg border border-zinc-200 flex items-center justify-center overflow-hidden shadow-xs" title="LineString">
                                            <svg class="w-11 h-11" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M8 50 C16 36, 20 18, 32 28 C42 37, 46 14, 56 12"
                                                      stroke="{{ $lineColor }}"
                                                      stroke-width="{{ $lineWidth }}"
                                                      stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </div>
                                    @elseif($marker->photo)
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($marker->photo) }}" alt="{{ $marker->name }}" class="w-16 h-16 object-cover rounded-lg border border-zinc-200 shadow-sm">
                                    @else
                                        <div class="w-16 h-16 bg-zinc-100 rounded-lg border border-zinc-200 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-medium text-zinc-500 text-xs">
                                    {{ number_format($marker->center_latitude, 6) }}, {{ number_format($marker->center_longitude, 6) }}
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $typeLabels = [
                                            'area_koleksi'     => 'Area Koleksi',
                                            'fasilitas_umum'   => 'Fasilitas Umum',
                                            'kantor_pengelola' => 'Kantor Pengelola',
                                            'pos_keamanan'     => 'Pos Keamanan',
                                            'jalan_utama'      => 'Jalan Utama',
                                            'jalan_lain'       => 'Jalan Lain',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-zinc-100 text-zinc-600 border border-zinc-200 font-space">
                                        {{ $typeLabels[strtolower(str_replace([' ','-'],'_',$marker->type))] ?? $marker->type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="w-5 h-5 rounded-full border-2 border-zinc-200" style="background-color: {{ $marker->color }}"></span>
                                        <span class="text-xs text-zinc-500 font-mono">{{ $marker->color }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.maps.edit', $marker) }}"
                                           class="p-2 text-zinc-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </a>

                                        <button type="button"
                                                onclick="showDeleteModal('{{ $marker->id }}', '{{ addslashes($marker->name) }}')"
                                                class="p-2 text-zinc-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Hapus">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="text-zinc-400 text-sm font-medium">Belum ada marker peta. <a href="{{ route('admin.maps.create') }}" class="text-zinc-900 font-bold hover:underline">Tambah marker pertama</a></div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($markers->hasPages())
                <div class="px-6 py-4 border-t border-zinc-200/60 bg-zinc-50/30">
                    {{ $markers->links() }}
                </div>
            @endif
        </div>
    </div>

    <div id="delete-modal" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.5); align-items:center; justify-content:center;">
        <div style="background:#fff; border-radius:20px; padding:28px 32px; max-width:420px; width:90%; box-shadow:0 25px 50px rgba(0,0,0,0.15);">
            <div style="display:flex; align-items:center; gap:14px; margin-bottom:16px;">
                <div style="width:44px;height:44px;background:#fef2f2;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg style="width:22px;height:22px;color:#dc2626;" fill="none" stroke="#dc2626" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </div>
                <div>
                    <p style="font-size:16px;font-weight:700;color:#18181b;margin:0;">Hapus Marker</p>
                    <p style="font-size:13px;color:#71717a;margin:4px 0 0 0;" id="delete-modal-text">Yakin ingin menghapus marker ini?</p>
                </div>
            </div>
            <p style="font-size:13px;color:#52525b;margin-bottom:22px;">Tindakan ini tidak dapat dibatalkan. Marker ini akan dihapus secara permanen.</p>
            <div style="display:flex;gap:10px;justify-content:flex-end;">
                <button type="button" onclick="closeDeleteModal()" style="padding:8px 18px;background:#f4f4f5;border:none;border-radius:10px;font-size:13px;font-weight:600;color:#3f3f46;cursor:pointer;">Batal</button>
                <button type="button" onclick="confirmDelete()" style="padding:8px 18px;background:#dc2626;border:none;border-radius:10px;font-size:13px;font-weight:700;color:#fff;cursor:pointer;">Ya, Hapus</button>
            </div>
        </div>
    </div>

    <form id="delete-form" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        var deleteBaseUrl = '{{ url("admin/maps") }}';
        var pendingDeleteId = null;

        function showDeleteModal(id, name) {
            pendingDeleteId = id;
            document.getElementById('delete-modal-text').textContent = 'Yakin ingin menghapus marker: ' + name;
            document.getElementById('delete-modal').style.display = 'flex';
        }

        function closeDeleteModal() {
            pendingDeleteId = null;
            document.getElementById('delete-modal').style.display = 'none';
        }

        function confirmDelete() {
            if (!pendingDeleteId) return;
            var form = document.getElementById('delete-form');
            form.action = deleteBaseUrl + '/' + pendingDeleteId;
            form.submit();
        }

        document.getElementById('delete-modal').addEventListener('click', function(e) {
            if (e.target === this) closeDeleteModal();
        });
    </script>

</x-dashboard-layout>
