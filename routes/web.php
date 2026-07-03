<?php

use App\Http\Controllers\Admin\MapController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PendaftaranManageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\KoleksiController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocialiteController;
use App\Models\Category;
use App\Models\Koleksi;
use App\Models\MapMarker;
use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    $markers = \App\Models\MapMarker::all();
    $koleksis = \App\Models\Koleksi::latest()->take(4)->get();
    return view('beranda', compact('markers', 'koleksis'));
})->name('home');

// Halaman-halaman Menu
Route::get('/tentang-kami', function () {
    return view('landing.profil');
})->name('profil');
Route::get('/koleksi', [KoleksiController::class, 'publicIndex'])->name('koleksi');
Route::get('/koleksi/{koleksi}', [KoleksiController::class, 'show'])->name('koleksi.show');
Route::get('/peta', function () {
    $markers = MapMarker::all();
    return view('landing.peta', compact('markers'));
})->name('peta');
Route::get('/peta/{map}', [MapController::class, 'publicShow'])->name('peta.show');

// Pendaftaran Routes (Bisa diakses Guest/Auth)
Route::get('/pendaftaran/pengunjung', [PendaftaranController::class, 'createPengunjung'])->name('pendaftaran.pengunjung');
Route::post('/pendaftaran/pengunjung', [PendaftaranController::class, 'storePengunjung'])->name('pendaftaran.pengunjung.store');

Route::get('/pendaftaran/peneliti', [PendaftaranController::class, 'createPeneliti'])->name('pendaftaran.peneliti');
Route::post('/pendaftaran/peneliti', [PendaftaranController::class, 'storePeneliti'])->name('pendaftaran.peneliti.store');

// =========================================================================
// 1. GUEST ROUTES (Belum Login)
// =========================================================================
Route::middleware('guest')->group(function () {
    // Login Manual
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->name('login.store');

    // Register Manual
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'storeRegister'])->name('register.store');

    // Google Login (Socialite)
    Route::get('/auth/google', [SocialiteController::class, 'redirect'])->name('auth.google');
    Route::get('/auth/google/callback', [SocialiteController::class, 'callback'])->name('auth.google.callback');
});

// =========================================================================
// 2. AUTH ROUTES (Sudah Login, tapi belum tentu Verified)
// =========================================================================
Route::middleware('auth')->group(function () {

    // Logout (Bisa logout walau belum verifikasi email)
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

    // --- Logika Verifikasi Email ---

    // 1. Tampilan "Harap Verifikasi Email"
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    // 2. Proses saat Link di Email diklik
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/dashboard');
    })->middleware('signed')->name('verification.verify');

    // 3. Kirim Ulang Email Verifikasi
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Link verifikasi baru telah dikirim!');
    })->middleware('throttle:6,1')->name('verification.send');
});



// =========================================================================
// 3. VERIFIED ROUTES (Login + Email Verified)
// =========================================================================
// Middleware 'verified' mencegah user masuk sini jika email belum valid
Route::middleware(['auth', 'verified'])->group(function () {

    // --- Dashboard User Biasa ---
    Route::get('/dashboard', function () {
        if (Illuminate\Support\Facades\Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $pengunjungRegistrations = \App\Models\PendaftaranPengunjung::where('user_id', Illuminate\Support\Facades\Auth::id())->latest()->get();
        $penelitiRegistrations = \App\Models\PendaftaranPeneliti::where('user_id', Illuminate\Support\Facades\Auth::id())->latest()->get();

        return view('dashboard', compact('pengunjungRegistrations', 'penelitiRegistrations'));
    })->name('dashboard');

    // --- Edit/Batal Pendaftaran (User) ---
    Route::get('/dashboard/pengunjungs/{id}/edit', [PendaftaranController::class, 'editPengunjung'])->name('dashboard.pengunjungs.edit');
    Route::patch('/dashboard/pengunjungs/{id}', [PendaftaranController::class, 'updatePengunjung'])->name('dashboard.pengunjungs.update');
    Route::delete('/dashboard/pengunjungs/{id}', [PendaftaranController::class, 'destroyPengunjungUser'])->name('dashboard.pengunjungs.destroy');

    Route::get('/dashboard/penelitis/{id}/edit', [PendaftaranController::class, 'editPeneliti'])->name('dashboard.penelitis.edit');
    Route::patch('/dashboard/penelitis/{id}', [PendaftaranController::class, 'updatePeneliti'])->name('dashboard.penelitis.update');
    Route::delete('/dashboard/penelitis/{id}', [PendaftaranController::class, 'destroyPenelitiUser'])->name('dashboard.penelitis.destroy');

    // --- Area Admin (Harus Verified + Role Admin) ---
    Route::middleware('admin')->prefix('admin')->group(function () {

        Route::get('/dashboard', function () {
            $totalUsers = User::count();
            $totalKoleksi = Koleksi::count();
            $totalMapMarkers = MapMarker::count();

            $totalPengunjung = \App\Models\PendaftaranPengunjung::where('status', 'disetujui')->sum('jumlah_rombongan') ?? 0;
            $totalPeneliti = \App\Models\PendaftaranPeneliti::where('status', 'disetujui')->count();

            return view('admin.dashboard', compact('totalUsers', 'totalKoleksi', 'totalMapMarkers', 'totalPengunjung', 'totalPeneliti'));
        })->name('admin.dashboard');

        // CRUD User
        Route::resource('users', UserController::class)->names('admin.users');

        // CRUD Peta
        Route::resource('maps', MapController::class)->names('admin.maps');

        // CRUD Koleksi
        Route::resource('koleksi', KoleksiController::class)->names('admin.koleksi');

        // Kelola Pengunjung & Peneliti
        // IMPORTANT: specific/static routes must come before wildcard {id} routes
        Route::get('/pengunjungs', [PendaftaranManageController::class, 'indexPengunjung'])->name('admin.pengunjungs.index');
        Route::post('/pengunjungs/bulk-delete', [PendaftaranManageController::class, 'bulkDestroyPengunjung'])->name('admin.pengunjungs.bulk-delete');
        Route::get('/pengunjungs/export/{format}', [PendaftaranManageController::class, 'exportPengunjung'])->name('admin.pengunjungs.export');
        Route::patch('/pengunjungs/{id}/status', [PendaftaranManageController::class, 'updatePengunjungStatus'])->name('admin.pengunjungs.status');
        Route::delete('/pengunjungs/{id}', [PendaftaranManageController::class, 'destroyPengunjung'])->name('admin.pengunjungs.destroy');

        Route::get('/penelitis', [PendaftaranManageController::class, 'indexPeneliti'])->name('admin.penelitis.index');
        Route::post('/penelitis/bulk-delete', [PendaftaranManageController::class, 'bulkDestroyPeneliti'])->name('admin.penelitis.bulk-delete');
        Route::get('/penelitis/export/{format}', [PendaftaranManageController::class, 'exportPeneliti'])->name('admin.penelitis.export');
        Route::patch('/penelitis/{id}/status', [PendaftaranManageController::class, 'updatePenelitiStatus'])->name('admin.penelitis.status');
        Route::delete('/penelitis/{id}', [PendaftaranManageController::class, 'destroyPeneliti'])->name('admin.penelitis.destroy');
    });
});

// =========================================================================
// 4. PROFILE ROUTES (Login Only, Can be Unverified to allow correcting typos)
// =========================================================================
Route::middleware(['auth'])->group(function () {
    // --- Profile Routes ---
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');

    // Halaman Edit (Form)
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');

    // Action Update
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
});
