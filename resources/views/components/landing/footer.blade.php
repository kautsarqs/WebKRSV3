<footer id="contact" class="bg-zinc-50 border-t border-zinc-200 pt-16 pb-8 mt-20">
    <div class="max-w-7xl mx-auto px-6">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-12 mb-16">

            <div class="lg:col-span-4 space-y-6">
                <div class="flex items-center gap-2 font-heading font-bold text-2xl tracking-wider text-zinc-900">
                    <img src="{{ asset('storage/images/logoKRS.png') }}" alt="Logo" class="h-14 w-auto">
                    <span class="pt-2">KEBUN RAYA SAMBAS</span>
                </div>
                <p class="text-zinc-500 leading-relaxed pr-6 text-sm">
                    Mewujudkan pelestarian tumbuhan lokal Kalimantan melalui konservasi, penelitian, dan edukasi lingkungan yang berkelanjutan.
                </p>
                <div class="flex gap-4">
                    <a href="#" class="h-10 w-10 rounded-full bg-white border border-zinc-200 flex items-center justify-center text-zinc-400 hover:text-zinc-900 hover:border-zinc-900 transition duration-300">
                        <span class="sr-only">Facebook</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"></path></svg>
                    </a>
                    <a href="#" class="h-10 w-10 rounded-full bg-white border border-zinc-200 flex items-center justify-center text-zinc-400 hover:text-zinc-900 hover:border-zinc-900 transition duration-300">
                        <span class="sr-only">Instagram</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37zm1.5-4.87h.01"></path><rect width="18" height="18" x="3" y="3" rx="2" ry="2"></rect></svg>
                    </a>
                </div>
            </div>

            <div class="lg:col-span-2">
                <h3 class="font-heading font-bold text-zinc-900 mb-6">Jelajahi</h3>
                <ul class="space-y-4 text-sm text-zinc-500">
                    <li><a href="#" class="hover:text-zinc-900 transition">Tentang Kami</a></li>
                    <li><a href="#" class="hover:text-zinc-900 transition">Koleksi Flora</a></li>
                    <li><a href="#" class="hover:text-zinc-900 transition">Fasilitas</a></li>
                    <li><a href="#" class="hover:text-zinc-900 transition">Peta Kawasan</a></li>
                </ul>
            </div>

            <div class="lg:col-span-2">
                <h3 class="font-heading font-bold text-zinc-900 mb-6">Layanan</h3>
                <ul class="space-y-4 text-sm text-zinc-500">
                    <li><a href="#" class="hover:text-zinc-900 transition">Tiket Masuk</a></li>
                    <li><a href="#" class="hover:text-zinc-900 transition">Sewa Tempat</a></li>
                    <li><a href="#" class="hover:text-zinc-900 transition">Kunjungan Sekolah</a></li>
                    <li><a href="#" class="hover:text-zinc-900 transition">Penelitian</a></li>
                </ul>
            </div>

            <div class="lg:col-span-4">
                <h3 class="font-heading font-bold text-zinc-900 mb-6">Hubungi Kami</h3>
                <ul class="space-y-4 text-sm text-zinc-500">
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-zinc-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span>Jl. Raya Sambas No. 123, Sabung, Kec. Subah, Kabupaten Sambas, Kalimantan Barat</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-zinc-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <a href="mailto:info@krsambas.go.id" class="hover:text-zinc-900 transition">info@krsambas.go.id</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="border-t border-zinc-200 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-zinc-400 text-sm text-center md:text-left">
                &copy; {{ date('Y') }} UPT Kebun Raya Sambas. All rights reserved.
            </p>
            <div class="flex gap-6 text-sm text-zinc-500">
                <a href="#" class="hover:text-zinc-900 transition">Kebijakan Privasi</a>
                <a href="#" class="hover:text-zinc-900 transition">Syarat & Ketentuan</a>
            </div>
        </div>
    </div>
</footer>