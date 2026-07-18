@extends('layouts.landing')

@section('title', 'Syarat & Ketentuan - Kebun Raya Sambas')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-12">
    <div class="mb-10 text-center">
        <span class="text-zinc-650 font-medium tracking-widest text-sm uppercase mb-2 block">Informasi Legal</span>
        <h1 class="font-heading text-4xl font-bold text-zinc-900 mb-4">Syarat & Ketentuan</h1>
        <p class="text-zinc-650">Terakhir diperbarui: {{ date('d F Y') }}</p>
    </div>

    <div class="prose prose-zinc max-w-none text-zinc-800 bg-white p-8 md:p-12 rounded-3xl shadow-sm border border-zinc-200">
        <h2 class="text-2xl font-bold text-zinc-900 mb-4">1. Pendahuluan</h2>
        <p class="mb-6 leading-relaxed">
            Selamat datang di situs web Kebun Raya Sambas. Syarat & Ketentuan ini mengatur akses dan penggunaan Anda terhadap situs web, aplikasi, dan layanan terkait dari UPTD Kebun Raya Sambas. Dengan menggunakan situs ini, Anda secara otomatis menyetujui seluruh ketentuan di bawah ini.
        </p>

        <h2 class="text-2xl font-bold text-zinc-900 mb-4">2. Pendaftaran Kunjungan & Penelitian</h2>
        <ul class="list-disc pl-6 mb-6 space-y-2">
            <li>Pengunjung wajib mengisi data secara akurat saat melakukan pendaftaran daring.</li>
            <li>Bagi peneliti, proposal penelitian dan izin dari institusi terkait wajib diunggah pada saat pendaftaran.</li>
            <li>Persetujuan izin penelitian sepenuhnya berada pada kewenangan pengelola Kebun Raya Sambas.</li>
        </ul>

        <h2 class="text-2xl font-bold text-zinc-900 mb-4">3. Peraturan Selama Berada di Kawasan</h2>
        <p class="mb-4 leading-relaxed">Seluruh pengunjung dan peneliti wajib menaati aturan kawasan, termasuk namun tidak terbatas pada:</p>
        <ul class="list-disc pl-6 mb-6 space-y-2">
            <li>Dilarang merusak flora, fauna, dan fasilitas yang ada di dalam kawasan.</li>
            <li>Dilarang membuang sampah sembarangan (gunakan tempat sampah yang telah disediakan).</li>
            <li>Dilarang membuat api unggun atau aktivitas yang dapat memicu kebakaran lahan.</li>
            <li>Mengikuti arahan dari petugas lapangan sewaktu-waktu demi keamanan.</li>
        </ul>

        <h2 class="text-2xl font-bold text-zinc-900 mb-4">4. Penggunaan Situs Web</h2>
        <p class="mb-6 leading-relaxed">
            Anda dilarang menyalahgunakan situs web ini untuk aktivitas ilegal, merusak sistem (seperti menyebarkan virus), atau mengumpulkan data pengguna lain tanpa izin. Akses mencurigakan atau pelanggaran keamanan dapat mengakibatkan akun Anda dinonaktifkan permanen dan diproses secara hukum.
        </p>

        <h2 class="text-2xl font-bold text-zinc-900 mb-4">5. Penolakan Tanggung Jawab (Disclaimer)</h2>
        <p class="mb-6 leading-relaxed">
            Pengelola Kebun Raya Sambas tidak bertanggung jawab atas kerugian materiil, kecelakaan, atau kerusakan barang pribadi yang terjadi selama kunjungan ke kawasan. Kami juga tidak menjamin bahwa situs web akan selalu bebas dari gangguan server atau *maintenance*.
        </p>

        <h2 class="text-2xl font-bold text-zinc-900 mb-4">6. Perubahan Ketentuan</h2>
        <p class="mb-6 leading-relaxed">
            Syarat & Ketentuan ini dapat diubah atau diperbarui sewaktu-waktu tanpa pemberitahuan sebelumnya. Penggunaan situs secara berkelanjutan setelah adanya perubahan akan dianggap sebagai persetujuan Anda terhadap ketentuan yang baru.
        </p>
    </div>
</div>
@endsection
