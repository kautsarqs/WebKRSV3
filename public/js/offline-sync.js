// Konfigurasi LocalForage untuk Offline Markers
localforage.config({
    name: 'KRS_Offline',
    storeName: 'markers'
});

const OfflineSync = {
    // Menyimpan data form marker ke IndexedDB
    async saveMarker(data) {
        try {
            const id = 'marker_' + Date.now();
            await localforage.setItem(id, data);
            this.updateBadge();
            return true;
        } catch (error) {
            console.error('Gagal menyimpan offline:', error);
            return false;
        }
    },

    // Mengambil semua data marker offline
    async getMarkers() {
        const markers = [];
        await localforage.iterate((value, key) => {
            if (key.startsWith('marker_')) {
                markers.push({ key, data: value });
            }
        });
        return markers;
    },

    // Update UI Badge (Jumlah Pending)
    async updateBadge() {
        const markers = await this.getMarkers();
        const count = markers.length;
        
        const badgeElement = document.getElementById('offline-sync-badge');
        const countElement = document.getElementById('offline-sync-count');
        const statusText = document.getElementById('offline-status-text');
        
        if (badgeElement && countElement && statusText) {
            if (count > 0) {
                badgeElement.classList.remove('hidden');
                countElement.innerText = count;
            } else {
                if (navigator.onLine) {
                    badgeElement.classList.add('hidden');
                } else {
                    badgeElement.classList.remove('hidden');
                    countElement.innerText = '0';
                }
            }

            if (navigator.onLine) {
                statusText.innerText = count > 0 ? 'Online (Pending)' : 'Online';
                statusText.classList.remove('text-red-500');
                statusText.classList.add('text-emerald-500');
            } else {
                statusText.innerText = 'Offline';
                statusText.classList.remove('text-emerald-500');
                statusText.classList.add('text-red-500');
            }
        }
    },

    // Menjalankan sinkronisasi ke server
    async syncNow() {
        if (!navigator.onLine) {
            alert('Tidak ada koneksi internet. Sinkronisasi dibatalkan.');
            return;
        }

        const markers = await this.getMarkers();
        if (markers.length === 0) {
            alert('Tidak ada data offline yang perlu disinkronkan.');
            return;
        }

        let successCount = 0;
        let failCount = 0;

        for (const item of markers) {
            try {
                // Mengirim data ke API endpoint yang tidak mewajibkan form-data multipart jika kita pakai JSON
                // Tapi lebih baik kita reconstruct FormData
                const formData = new FormData();
                Object.keys(item.data).forEach(key => {
                    const value = item.data[key];
                    if (value && typeof value === 'object' && value.content && value.content.startsWith('data:')) {
                        // Reconstruct File from base64
                        const arr = value.content.split(',');
                        const mime = arr[0].match(/:(.*?);/)[1];
                        const bstr = atob(arr[1]);
                        let n = bstr.length;
                        const u8arr = new Uint8Array(n);
                        while(n--){
                            u8arr[n] = bstr.charCodeAt(n);
                        }
                        const file = new File([u8arr], value.name, { type: mime });
                        formData.append(key, file);
                    } else {
                        formData.append(key, value);
                    }
                });

                // Ambil CSRF token dari meta tag
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                const response = await fetch('/admin/api/maps/sync', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                if (response.ok) {
                    await localforage.removeItem(item.key);
                    successCount++;
                } else {
                    failCount++;
                }
            } catch (error) {
                console.error('Gagal sinkronisasi marker:', error);
                failCount++;
            }
        }

        this.updateBadge();
        
        if (successCount > 0) {
            alert(`Berhasil sinkronisasi ${successCount} marker ke server.` + (failCount > 0 ? ` Gagal: ${failCount}` : ''));
            window.location.reload(); // Reload halaman untuk melihat hasil
        } else if (failCount > 0) {
            alert('Gagal menyinkronkan data. Silakan coba lagi nanti.');
        }
    },

    // Inisialisasi event listener
    init() {
        window.addEventListener('online', () => {
            this.updateBadge();
            // Optional: Auto sync
            // this.syncNow(); 
        });
        
        window.addEventListener('offline', () => {
            this.updateBadge();
        });

        // Setup awal saat halaman dimuat
        document.addEventListener('DOMContentLoaded', () => {
            this.updateBadge();
            
            const syncBtn = document.getElementById('offline-sync-btn');
            if (syncBtn) {
                syncBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.syncNow();
                });
            }
        });
    }
};

// Jalankan inisialisasi
OfflineSync.init();
