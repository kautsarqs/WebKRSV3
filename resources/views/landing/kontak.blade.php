<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>Hubungi Kami - Kebun Raya Sambas</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Inter', sans-serif; } .font-heading { font-family: 'Space Grotesk', sans-serif; }</style>
</head>
<body class="bg-white text-zinc-900 antialiased">
    <x-landing.navbar />

    <main class="pt-32 pb-20">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
                
                <div>
                    <h1 class="font-heading text-4xl font-bold mb-6">Hubungi Kami</h1>
                    <p class="text-zinc-500 leading-relaxed mb-10">
                        Punya pertanyaan tentang kunjungan, penelitian, atau kerjasama? Jangan ragu untuk menghubungi kami melalui formulir atau kontak di bawah ini.
                    </p>

                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-full bg-zinc-100 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-zinc-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-zinc-900">Alamat</h3>
                                <p class="text-zinc-500 text-sm mt-1">Jl. Raya Sambas No. 123, Sabung, Kec. Subah, Kabupaten Sambas, Kalimantan Barat</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-full bg-zinc-100 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-zinc-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-zinc-900">Email</h3>
                                <p class="text-zinc-500 text-sm mt-1">info@krsambas.go.id</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-full bg-zinc-100 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-zinc-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-zinc-900">Telepon</h3>
                                <p class="text-zinc-500 text-sm mt-1">+62 561 123456</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-zinc-200 p-8 rounded-3xl shadow-xl shadow-zinc-200/50">
                    <form action="#" class="space-y-4">
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-zinc-700 font-heading">Nama Lengkap</label>
                            <input type="text" class="w-full px-4 py-3 bg-zinc-50 border border-zinc-200 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 outline-none" placeholder="Masukkan nama anda">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-zinc-700 font-heading">Email</label>
                            <input type="email" class="w-full px-4 py-3 bg-zinc-50 border border-zinc-200 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 outline-none" placeholder="nama@email.com">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-zinc-700 font-heading">Pesan</label>
                            <textarea rows="4" class="w-full px-4 py-3 bg-zinc-50 border border-zinc-200 rounded-xl focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 outline-none" placeholder="Tulis pesan anda disini..."></textarea>
                        </div>
                        <button type="button" class="w-full py-4 bg-zinc-900 text-white font-bold rounded-xl hover:bg-zinc-800 transition shadow-lg shadow-zinc-900/20 mt-2">
                            Kirim Pesan
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </main>

    <x-landing.footer />
</body>
</html>