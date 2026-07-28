<?php

use App\Http\Controllers\Admin\MapController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PendaftaranManageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KoleksiController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\PasswordResetController;
use App\Models\Koleksi;
use App\Models\MapMarker;
use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $markers = \App\Models\MapMarker::all();
    $koleksis = \App\Models\Koleksi::latest()->take(4)->get();
    return view('beranda', compact('markers', 'koleksis'));
})->name('home');

Route::get('/tentang-kami', function () {
    return view('landing.profil');
})->name('profil');
Route::get('/kebijakan-privasi', function () {
    return view('landing.privasi');
})->name('privasi');
Route::get('/syarat-ketentuan', function () {
    return view('landing.syarat');
})->name('syarat');
Route::get('/koleksi', [KoleksiController::class, 'publicIndex'])->name('koleksi');
Route::get('/koleksi/{koleksi}', [KoleksiController::class, 'show'])->name('koleksi.show');
Route::get('/peta', function () {
    $markers = MapMarker::all();
    return view('landing.peta', compact('markers'));
})->name('peta');
Route::get('/peta/{map}', [MapController::class, 'publicShow'])->name('peta.show');

Route::get('/pendaftaran/pengunjung', [PendaftaranController::class, 'createPengunjung'])->name('pendaftaran.pengunjung');
Route::post('/pendaftaran/pengunjung', [PendaftaranController::class, 'storePengunjung'])->name('pendaftaran.pengunjung.store');
Route::get('/api/pendaftaran/status', [PendaftaranController::class, 'getStatus'])->name('pendaftaran.status');

Route::get('/pendaftaran/peneliti', [PendaftaranController::class, 'createPeneliti'])->name('pendaftaran.peneliti');
Route::post('/pendaftaran/peneliti', [PendaftaranController::class, 'storePeneliti'])->name('pendaftaran.peneliti.store');

Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->name('login.store');

    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'storeRegister'])->name('register.store');

    Route::get('/forgot-password', [PasswordResetController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'edit'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'update'])->name('password.update');

    Route::get('/auth/google', [SocialiteController::class, 'redirect'])->name('auth.google');
    Route::get('/auth/google/callback', [SocialiteController::class, 'callback'])->name('auth.google.callback');
});

Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/dashboard');
    })->middleware('signed')->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Link verifikasi baru telah dikirim!');
    })->middleware('throttle:6,1')->name('verification.send');
});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        if (Illuminate\Support\Facades\Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $pengunjungRegistrations = \App\Models\PendaftaranPengunjung::with('editedVersion')->where('user_id', Illuminate\Support\Facades\Auth::id())->latest()->get();
        $penelitiRegistrations = \App\Models\PendaftaranPeneliti::with('editedVersion')->where('user_id', Illuminate\Support\Facades\Auth::id())->latest()->get();

        return view('dashboard.index', compact('pengunjungRegistrations', 'penelitiRegistrations'));
    })->name('dashboard');

    Route::get('/dashboard/pengunjung/{id}/edit', [PendaftaranController::class, 'editPengunjung'])->name('dashboard.pengunjung.edit');
    Route::patch('/dashboard/pengunjung/{id}', [PendaftaranController::class, 'updatePengunjung'])->name('dashboard.pengunjung.update');
    Route::delete('/dashboard/pengunjung/{id}', [PendaftaranController::class, 'destroyPengunjungUser'])->name('dashboard.pengunjung.destroy');

    Route::get('/dashboard/peneliti/{id}/edit', [PendaftaranController::class, 'editPeneliti'])->name('dashboard.peneliti.edit');
    Route::patch('/dashboard/peneliti/{id}', [PendaftaranController::class, 'updatePeneliti'])->name('dashboard.peneliti.update');
    Route::delete('/dashboard/peneliti/{id}', [PendaftaranController::class, 'destroyPenelitiUser'])->name('dashboard.peneliti.destroy');

    Route::middleware('admin')->prefix('admin')->group(function () {

        Route::get('/dashboard', function () {
            $totalUsers = User::count();
            $totalKoleksi = Koleksi::count();
            $totalMapMarkers = MapMarker::count();

            $totalPengunjung = \App\Models\PendaftaranPengunjung::where('status', 'disetujui')->sum('jumlah_rombongan') ?? 0;
            $totalPeneliti = \App\Models\PendaftaranPeneliti::where('status', 'disetujui')->count();

            $pengunjungTotal = \App\Models\PendaftaranPengunjung::count();
            $pendaftaranKunjunganOpen = \App\Models\Setting::isPendaftaranPengunjungOpen();

            $penelitiPending = \App\Models\PendaftaranPeneliti::where('status', 'pending')->count();
            $penelitiSedang = \App\Models\PendaftaranPeneliti::where('status', 'disetujui')->where('status_penelitian', 'sedang')->count();
            $penelitiSelesai = \App\Models\PendaftaranPeneliti::where('status', 'disetujui')->where('status_penelitian', 'selesai')->count();
            $penelitiTolak = \App\Models\PendaftaranPeneliti::where('status', 'ditolak')->count();

            return view('admin.dashboard', compact(
                'totalUsers', 'totalKoleksi', 'totalMapMarkers', 'totalPengunjung', 'totalPeneliti',
                'pengunjungTotal', 'pendaftaranKunjunganOpen',
                'penelitiPending', 'penelitiSedang', 'penelitiSelesai', 'penelitiTolak'
            ));
        })->name('admin.dashboard');

        Route::resource('users', UserController::class)->names('admin.users');

        Route::resource('maps', MapController::class)->names('admin.maps');
        Route::post('/api/maps/sync', [MapController::class, 'syncOffline'])->name('admin.maps.sync');

        Route::resource('koleksi', KoleksiController::class)->names('admin.koleksi');

        Route::get('/pengunjung', [PendaftaranManageController::class, 'indexPengunjung'])->name('admin.pengunjung.index');
        Route::post('/pengunjung/bulk-delete', [PendaftaranManageController::class, 'bulkDestroyPengunjung'])->name('admin.pengunjung.bulk-delete');
        Route::get('/pengunjung/export/{format}', [PendaftaranManageController::class, 'exportPengunjung'])->name('admin.pengunjung.export');
        Route::post('/pengunjung/toggle', [PendaftaranManageController::class, 'togglePendaftaranPengunjung'])->name('admin.pengunjung.toggle');
        Route::delete('/pengunjung/{id}', [PendaftaranManageController::class, 'destroyPengunjung'])->name('admin.pengunjung.destroy');

        Route::get('/peneliti', [PendaftaranManageController::class, 'indexPeneliti'])->name('admin.peneliti.index');
        Route::post('/peneliti/bulk-delete', [PendaftaranManageController::class, 'bulkDestroyPeneliti'])->name('admin.peneliti.bulk-delete');
        Route::get('/peneliti/export/{format}', [PendaftaranManageController::class, 'exportPeneliti'])->name('admin.peneliti.export');
        Route::patch('/peneliti/{id}/status', [PendaftaranManageController::class, 'updatePenelitiStatus'])->name('admin.peneliti.status');
        Route::patch('/peneliti/{id}/status-penelitian', [PendaftaranManageController::class, 'updatePenelitiStatusPenelitian'])->name('admin.peneliti.status-penelitian');
        Route::delete('/peneliti/{id}', [PendaftaranManageController::class, 'destroyPeneliti'])->name('admin.peneliti.destroy');
    });
});

Route::middleware(['auth'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');

    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
});
