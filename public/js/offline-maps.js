/**
 * Offline Maps Engine (Multi-Layer Support)
 * Requires: Leaflet (L) and localforage
 */

if (typeof L === 'undefined' || typeof localforage === 'undefined') {
    console.warn("Leaflet atau localforage tidak dimuat. Offline Maps Engine dinonaktifkan.");
} else {

    // Generator URL agar Leaflet tetap bisa fetching tile tanpa terjebak bug cache getTileUrl bawaan Leaflet
    function getTileUrlForCoords(layer, coords) {
        var url = layer._url;
        url = url.replace('{z}', coords.z)
                 .replace('{x}', coords.x)
                 .replace('{y}', coords.y);
        if (url.indexOf('{s}') !== -1 && layer.options.subdomains) {
            var subdomains = layer.options.subdomains;
            var index = Math.abs(coords.x + coords.y) % subdomains.length;
            var s = typeof subdomains === 'string' ? subdomains[index] : subdomains[index];
            url = url.replace('{s}', s);
        }
        return url;
    }

    // Kelas kustom untuk layer peta offline (otomatis membaca IndexedDB)
    L.TileLayer.Offline = L.TileLayer.extend({
        getTileKey: function(coords) {
            var cleanUrl = (this._url || "").replace(/[^a-zA-Z0-9]/g, "").substring(0, 20);
            return `${cleanUrl}_tile_${coords.z}_${coords.x}_${coords.y}`;
        },
        createTile: function(coords, done) {
            var tile = document.createElement('img');

            // Gunakan event load bawaan Leaflet agar animasi & layout Leaflet (termasuk zoom) tidak rusak/berantakan
            L.DomEvent.on(tile, 'load', L.bind(this._tileOnLoad, this, done, tile));
            L.DomEvent.on(tile, 'error', L.bind(this._tileOnError, this, done, tile));

            if (this.options.crossOrigin) {
                tile.crossOrigin = this.options.crossOrigin === true ? '' : this.options.crossOrigin;
            }
            tile.alt = '';
            tile.setAttribute('role', 'presentation');

            var url = getTileUrlForCoords(this, coords);
            var key = this.getTileKey(coords);

            localforage.getItem(key).then(function(blob) {
                if (blob) {
                    // Ada di cache, set src menggunakan Blob URL
                    tile.src = URL.createObjectURL(blob);
                } else {
                    // Tidak ada di cache, unduh secara asinkron
                    fetch(url)
                        .then(function(res) {
                            if (!res.ok) throw new Error("Gagal mengambil tile");
                            return res.blob();
                        })
                        .then(function(blob) {
                            localforage.setItem(key, blob);
                            tile.src = URL.createObjectURL(blob);
                        })
                        .catch(function(err) {
                            // Fallback jika fetch blob gagal, gunakan src biasa tanpa pembatasan CORS
                            tile.removeAttribute('crossOrigin');
                            tile.src = url;
                        });
                }
            }).catch(function(err) {
                console.error("LocalForage error:", err);
                tile.removeAttribute('crossOrigin');
                tile.src = url;
            });

            // Leaflet akan memposisikan gambar ini di grid, saat src terisi dan onload memicu _tileOnLoad
            return tile;
        }
    });

    L.tileLayer.offline = function(urlTemplate, options) {
        return new L.TileLayer.Offline(urlTemplate, options);
    };

    // Fungsi utility untuk mencari tiles apa saja yang mencakup wilayah bounding box pada zoom tertentu
    function getTileCoordsForBounds(map, bounds, zoom) {
        var tileSize = 256;
        var nw = bounds.getNorthWest();
        var se = bounds.getSouthEast();
        var nwPoint = map.project(nw, zoom);
        var sePoint = map.project(se, zoom);

        var minX = Math.floor(nwPoint.x / tileSize);
        var minY = Math.floor(nwPoint.y / tileSize);
        var maxX = Math.floor(sePoint.x / tileSize);
        var maxY = Math.floor(sePoint.y / tileSize);

        var tiles = [];
        for (var x = minX; x <= maxX; x++) {
            for (var y = minY; y <= maxY; y++) {
                tiles.push({ z: zoom, x: x, y: y });
            }
        }
        return tiles;
    }

    /**
     * Memulai unduhan peta massal.
     * @param {Object} map Objek Leaflet Map
     * @param {Array} layers Array of layer objects (yang sudah L.tileLayer.offline)
     * @param {Array} zoomRanges Array of zoom levels (misal [13, 14, 15, 16, 17])
     * @param {Object} callbacks { onProgress(downloaded, failed, total, percentage), onSuccess(downloaded, failed), onError(msg) }
     */
    window.downloadOfflineMaps = function(map, layers, zoomRanges, callbacks) {
        if (!navigator.onLine) {
            if (callbacks.onError) callbacks.onError("Koneksi internet terputus. Tidak bisa mengunduh peta saat ini.");
            return;
        }

        var bounds = map.getBounds();
        var masterTileQueue = []; // akan menampung { layer, tile }

        // Kumpulkan semua tiles dari semua layer yang direquest
        layers.forEach(function(layer) {
            zoomRanges.forEach(function(z) {
                if (z >= layer.options.minZoom && z <= (layer.options.maxZoom || 19)) {
                    var tileCoords = getTileCoordsForBounds(map, bounds, z);
                    tileCoords.forEach(function(t) {
                        masterTileQueue.push({ layer: layer, tile: t });
                    });
                }
            });
        });

        if (masterTileQueue.length === 0) {
            if (callbacks.onError) callbacks.onError("Tidak ada petak (tiles) yang perlu diunduh untuk area ini.");
            return;
        }

        var total = masterTileQueue.length;
        var downloaded = 0;
        var failed = 0;
        
        var maxConcurrency = 2; // DIBATASI 2 agar tidak diblokir OSM/Satelit
        var index = 0;
        var activeRequests = 0;

        function updateProgress() {
            var totalProcessed = downloaded + failed;
            var percentage = Math.round((totalProcessed / total) * 100);
            if (callbacks.onProgress) callbacks.onProgress(totalProcessed, failed, total, percentage);

            if (totalProcessed === total) {
                // Simpan tanggal unduhan
                var now = new Date();
                var formattedDate = now.getDate() + ' ' + 
                                    ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'][now.getMonth()] + ' ' + 
                                    now.getFullYear() + ', ' + 
                                    String(now.getHours()).padStart(2, '0') + ':' + String(now.getMinutes()).padStart(2, '0');
                localStorage.setItem('offline_map_last_downloaded', formattedDate);
                
                // Dispatch event global agar UI AlpineJS di peta.blade.php bisa merespon
                window.dispatchEvent(new CustomEvent('map-downloaded', { detail: formattedDate }));
                
                if (callbacks.onSuccess) callbacks.onSuccess(downloaded, failed);
            }
        }

        function downloadNext() {
            if (index >= total) return; // Antrean habis

            var currentItem = masterTileQueue[index++];
            var layer = currentItem.layer;
            var tile = currentItem.tile;
            
            var url = getTileUrlForCoords(layer, tile);
            var key = layer.getTileKey(tile);

            activeRequests++;

            localforage.getItem(key).then(function(val) {
                if (val) {
                    // Sudah ada di cache, skip fetch
                    downloaded++;
                    activeRequests--;
                    updateProgress();
                    downloadNext();
                } else {
                    // Beri jeda antar fetch agar lebih manusiawi
                    setTimeout(function() {
                        fetch(url)
                            .then(function(r) { 
                                if (!r.ok) throw new Error("Gagal load tile dari server");
                                return r.blob(); 
                            })
                            .then(function(blob) {
                                return localforage.setItem(key, blob);
                            })
                            .then(function() {
                                downloaded++;
                                activeRequests--;
                                updateProgress();
                                downloadNext();
                            })
                            .catch(function(e) {
                                failed++;
                                activeRequests--;
                                updateProgress();
                                downloadNext();
                            });
                    }, 100); // 100ms jeda per permintaan
                }
            }).catch(function(err) {
                failed++;
                activeRequests--;
                updateProgress();
                downloadNext();
            });
        }

        // Mulai antrean sebanyak batas konkurensi
        for (var i = 0; i < Math.min(maxConcurrency, total); i++) {
            downloadNext();
        }
    };

    /**
     * Menghapus seluruh cache peta
     */
    window.clearOfflineMapsCache = function(callbacks) {
        localforage.clear().then(function() {
            localStorage.removeItem('offline_map_last_downloaded');
            if (callbacks.onSuccess) callbacks.onSuccess();
        }).catch(function(err) {
            if (callbacks.onError) callbacks.onError(err);
        });
    };

    window.getLastDownloadedOfflineMapsTime = function() {
        return localStorage.getItem('offline_map_last_downloaded') || 'Belum Pernah';
    };

    /**
     * Auto-download peta khusus untuk area Kebun Raya Sambas di background
     * Berjalan secara silent tanpa notifikasi ke user
     */
    window.autoDownloadKRS = function(map, layers) {
        if (!navigator.onLine) return; // Jangan jalankan jika offline
        
        // Batas wilayah Kebun Raya Sambas (approx)
        // [1.2599, 109.4751], [1.2799, 109.4951]
        var bounds = L.latLngBounds([1.2599, 109.4751], [1.2799, 109.4951]);
        
        // Zoom khusus untuk jalan utama & marker penting di KRS
        var zoomRanges = [13, 14, 15, 16, 17];
        
        var masterTileQueue = [];
        
        layers.forEach(function(layer) {
            if (!layer || typeof layer.getTileKey !== 'function') return;
            zoomRanges.forEach(function(z) {
                if (z >= (layer.options.minZoom || 0) && z <= (layer.options.maxZoom || 19)) {
                    var tileCoords = getTileCoordsForBounds(map, bounds, z);
                    tileCoords.forEach(function(t) {
                        masterTileQueue.push({ layer: layer, tile: t });
                    });
                }
            });
        });

        if (masterTileQueue.length === 0) return;

        var total = masterTileQueue.length;
        var maxConcurrency = 2; 
        var index = 0;
        var activeRequests = 0;

        function downloadNext() {
            if (index >= total) {
                if (activeRequests === 0) {
                    console.log("[AutoDownload] Peta Kebun Raya Sambas berhasil diunduh ke background cache.");
                }
                return;
            }

            var currentItem = masterTileQueue[index++];
            var layer = currentItem.layer;
            var tile = currentItem.tile;
            
            var url = getTileUrlForCoords(layer, tile);
            var key = layer.getTileKey(tile);

            activeRequests++;

            localforage.getItem(key).then(function(val) {
                if (val) {
                    activeRequests--;
                    downloadNext();
                } else {
                    setTimeout(function() {
                        fetch(url)
                            .then(function(r) { return r.ok ? r.blob() : null; })
                            .then(function(blob) {
                                if (blob) localforage.setItem(key, blob);
                                activeRequests--;
                                downloadNext();
                            })
                            .catch(function() {
                                activeRequests--;
                                downloadNext();
                            });
                    }, 200); // 200ms delay agar tidak mengganggu kecepatan website utama
                }
            }).catch(function() {
                activeRequests--;
                downloadNext();
            });
        }

        console.log("[AutoDownload] Memulai pengunduhan " + total + " tiles di background...");
        for (var i = 0; i < Math.min(maxConcurrency, total); i++) {
            downloadNext();
        }
    };
}
