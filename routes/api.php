<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VidioController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\PromosiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\SpesialisController;
use App\Http\Controllers\JobVacancyController;
use App\Http\Controllers\Api\LamaranController;
use App\Http\Controllers\JadwalDokterController;
use App\Http\Controllers\JadwalPoliklinikController;
use App\Http\Controllers\LeafletController;

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| AUTHENTICATED USER (SEMUA ROLE)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:api'])->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

/*
|--------------------------------------------------------------------------
| ADMIN & SUPER ADMIN
| (super_admin otomatis lolos via middleware role)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:api', 'role:admin'])->group(function () {

    // ARTIKEL
    Route::get('/artikels', [ArtikelController::class, 'index']);
    Route::get('/artikel/{slug}', [ArtikelController::class, 'show']);
    Route::post('/artikels', [ArtikelController::class, 'store']);
    Route::put('/artikels/{slug}', [ArtikelController::class, 'update']);
    Route::delete('/artikels/{slug}', [ArtikelController::class, 'destroy']);

    // PROMOSI
    Route::get('/promosis', [PromosiController::class, 'index']);
    Route::post('/promosis', [PromosiController::class, 'store']);
    Route::put('/promosis/{id}', [PromosiController::class, 'update']);
    Route::delete('/promosis/{id}', [PromosiController::class, 'destroy']);

    // GALERI
    Route::get('/galleries', [GalleryController::class, 'index']);
    Route::post('/galleries', [GalleryController::class, 'store']);
    Route::put('/galleries/{id}', [GalleryController::class, 'update']);
    Route::delete('/galleries/{id}', [GalleryController::class, 'destroy']);

    // LEAFLET
    Route::get('/sliders', [LeafletController::class, 'index']);
    Route::post('/sliders', [LeafletController::class, 'store']);
    Route::put('/sliders/{id}', [LeafletController::class, 'update']);
    Route::delete('/sliders/{id}', [LeafletController::class, 'destroy']);

    // JOB VACANCY
    Route::get('/job-vacancies', [JobVacancyController::class, 'index']);
    Route::post('/job-vacancies', [JobVacancyController::class, 'store']);
    Route::put('/job-vacancies/{id}', [JobVacancyController::class, 'update']);
    Route::delete('/job-vacancies/{id}', [JobVacancyController::class, 'destroy']);

    // VIDIO
    Route::get('/vidios', [VidioController::class, 'index']);
    Route::post('/vidios', [VidioController::class, 'store']);
    Route::put('/vidios/{id}', [VidioController::class, 'update']);
    Route::delete('/vidios/{id}', [VidioController::class, 'destroy']);

    // SPESIALIS
    Route::get('/spesialis', [SpesialisController::class, 'index']);
    Route::post('/spesialis', [SpesialisController::class, 'store']);
    Route::get('/spesialis/{id}', [SpesialisController::class, 'show']);
    Route::put('/spesialis/{id}', [SpesialisController::class, 'update']);
    Route::delete('/spesialis/{id}', [SpesialisController::class, 'destroy']);

    // DOKTER
    Route::apiResource('dokters', DokterController::class);
    Route::get('get-all-dokter', [DokterController::class, "getAllDokter"]);

    // JADWAL DOKTER
    Route::apiResource('jadwal-dokters', JadwalDokterController::class);

    // JADWAL POLIKLINIK
    Route::apiResource('jadwal-polikliniks', JadwalPoliklinikController::class);

});


/*
|--------------------------------------------------------------------------
| ADMIN PEGAWAI (LAMARAN VIEW ONLY)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:api', 'role:admin_pegawai'])->group(function () {
    // LAMARAN (FULL ACCESS)
    Route::get('/lamaran-admin', [LamaranController::class, 'index']);
    Route::delete('/lamaran-admin/{id}', [LamaranController::class, 'destroy']);
    Route::delete('/lamaran-admin', [LamaranController::class, 'destroyAll']);
    Route::get('/lamaran-admin/export', [LamaranController::class, 'exportExcel']);
});

/*
|--------------------------------------------------------------------------
| SUPER ADMIN ONLY (USER MANAGEMENT)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:api', 'role:super_admin'])->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTE (TANPA LOGIN)
|--------------------------------------------------------------------------
*/
Route::get('/public-artikels/featured', [ArtikelController::class, 'artikelFeatured']);
Route::get('/public-artikels/news', [ArtikelController::class, 'artikelNews']);
Route::get('/public-artikels', [ArtikelController::class, 'home_artikel']);
Route::get('/public-artikels/{slug}', [ArtikelController::class, 'detail_artikel']);

Route::get('/public-promosis', [PromosiController::class, 'publicPromosi']);
Route::get('/public-galleries', [GalleryController::class, 'publicGallery']);
Route::get('/public-sliders', [LeafletController::class, 'publicLeaflet']);

Route::get('/public-job-vacancies', [JobVacancyController::class, 'publicJob']);

Route::get('/public-vidios/featured', [VidioController::class, 'featured']);

Route::post('/lamaran', [LamaranController::class, 'store']);
Route::get('/check-nik', [LamaranController::class, 'checkNik']);

Route::get('/dokter-kami', [JadwalDokterController::class, 'indexPublic']);

Route::get('jadwal-polikliniks-public', [JadwalPoliklinikController::class, "indexPublic"]);









