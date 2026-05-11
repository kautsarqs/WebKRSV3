<?php

use App\Http\Controllers\Admin\MapController;
use App\Http\Controllers\Admin\UserController;
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
    return view('beranda');
})->name('home');

// Halaman-halaman Menu
Route::get('/profil', function () {
    return view('landing.profil');
})->name('profil');
Route::get('/koleksi', [KoleksiController::class, 'publicIndex'])->name('koleksi');
Route::get('/koleksi/{koleksi}', [KoleksiController::class, 'show'])->name('koleksi.show');
Route::get('/koleksi/{koleksi}/peta', [KoleksiController::class, 'showMap'])->name('koleksi.peta');
Route::get('/penelitian', function () {
    return view('landing.penelitian');
})->name('penelitian');
Route::get('/peta', function () {
    $markers = MapMarker::all();
    return view('landing.peta', compact('markers'));
})->name('peta');
Route::get('/kontak', function () {
    return view('landing.kontak');
})->name('kontak');

// Pendaftaran Routes (Bisa diakses Guest/Auth)
Route::get('/pendaftaran/pengunjung', [PendaftaranController::class, 'createPengunjung'])->name('pendaftaran.pengunjung');
Route::post('/pendaftaran/pengunjung', [PendaftaranController::class, 'storePengunjung'])->name('pendaftaran.pengunjung.store');

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
    Route::get('/auth/google/callback', [SocialiteController::class, 'callback']);
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
        return view('dashboard');
    })->name('dashboard');

    // --- Area Admin (Harus Verified + Role Admin) ---
    Route::middleware('admin')->prefix('admin')->group(function () {

        Route::get('/dashboard', function () {
            $totalUsers = User::count();
            $totalKoleksi = Koleksi::count();
            $totalCategories = Category::count();
            $totalMapMarkers = MapMarker::count();

            return view('admin.dashboard', compact('totalUsers', 'totalKoleksi', 'totalCategories', 'totalMapMarkers'));
        })->name('admin.dashboard');

        // CRUD User
        Route::resource('users', UserController::class)->names('admin.users');

        // CRUD Peta
        Route::resource('maps', MapController::class)->names('admin.maps');

        // CRUD Koleksi
        Route::resource('koleksi', KoleksiController::class)->names('admin.koleksi');

        // CRUD Kategori
        Route::resource('categories', CategoryController::class)->names('admin.categories');
    });

    // --- Profile Routes ---
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');

    // Halaman Edit (Form)
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');

    // Action Update
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
});
