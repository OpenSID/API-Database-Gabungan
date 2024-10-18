<?php

use App\Http\Controllers\Api\ArtikelController;
use App\Http\Controllers\Api\BantuanController;
use App\Http\Controllers\Api\BantuanKabupatenController;
use App\Http\Controllers\Api\DasborController;
use App\Http\Controllers\Api\DokumenController;
use App\Http\Controllers\Api\KategoriController;
use App\Http\Controllers\Api\KategoriDesaController;
use App\Http\Controllers\Api\KeluargaController;
use App\Http\Controllers\Api\PendudukController;
use App\Http\Controllers\Api\PengaturanController;
use App\Http\Controllers\Api\StatistikController;
use App\Http\Controllers\Api\SummaryController;
use App\Http\Controllers\Api\WebsiteController;
use App\Http\Controllers\Api\WilayahController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware([])->group(function () {
    // Dasbor
    Route::prefix('dasbor')->group(function () {
        Route::get('/', DasborController::class);
    });

    // Wilayah
    Route::prefix('wilayah')->middleware([])->group(function () {
        Route::get('desa', [WilayahController::class, 'desa']);
        Route::get('dusun', [WilayahController::class, 'dusun']);
        Route::get('rw', [WilayahController::class, 'rw']);
        Route::get('rt', [WilayahController::class, 'rt']);
        Route::get('penduduk', [WilayahController::class, 'penduduk']);
    });

    Route::prefix('penduduk')->middleware([])->group(function () {
        Route::get('/', [PendudukController::class, 'index']);

        // Referensi
        Route::prefix('referensi')->group(function () {
            Route::get('sex', [PendudukController::class, 'pendudukSex']);
            Route::get('status', [PendudukController::class, 'pendudukStatus']);
            Route::get('status-dasar', [PendudukController::class, 'pendudukStatusDasar']);
        });

        Route::prefix('aksi')->group(function () {
            Route::post('pindah', [PendudukController::class, 'pindah']);
        });
    });

    // Dokumen
    Route::prefix('dokumen')->middleware([])->group(function () {
        Route::get('/', DokumenController::class);
    });

    // Keluarga
    Route::controller(KeluargaController::class)
        ->prefix('keluarga')->group(function () {
            Route::get('/show', 'show')->name('api.keluarga.detail');
        });

    // Statistik
    Route::controller(StatistikController::class)
        ->prefix('statistik')->group(function () {
            Route::get('/kategori-statistik', 'kategoriStatistik');
            Route::prefix('penduduk')->group(function () {
                Route::get('/', 'penduduk');
                Route::get('/tahun', 'refTahunPenduduk');
            });
            Route::prefix('keluarga')->group(function () {
                Route::get('/', 'keluarga');
                Route::get('/tahun', 'refTahunKeluarga');
            });
            Route::prefix('rtm')->group(function () {
                Route::get('/', 'rtm');
                Route::get('/tahun', 'refTahunRtm');
            });
            Route::get('/bantuan', 'bantuan');
            Route::get('/bantuan/tahun', [BantuanController::class, 'tahun']);
        });

    // Bantuan
    Route::controller(BantuanController::class)
        ->prefix('bantuan')->group(function () {
            Route::get('/', 'index');
            Route::get('/peserta', 'peserta');
            Route::get('/sasaran', 'sasaran');
            Route::get('/tahun', 'tahun');
            Route::get('/cetak', 'cetakBantuan');
        });

    // Master Data Kategori Artikel
    Route::controller(KategoriController::class)
        ->prefix('kategori')->group(function () {
            Route::get('/', 'index');
            Route::get('/tampil', 'show');
            Route::post('/buat', 'store');
            Route::put('/perbarui/{id}', 'update');
            Route::post('/hapus', 'destroy');
        });

    // Master Data Bantuan
    Route::controller(BantuanKabupatenController::class)
        ->prefix('bantuan-kabupaten')->group(function () {
            Route::get('/', 'index');
            Route::post('/tambah', 'store');
            Route::put('/perbarui/{id}', 'update');
            Route::post('/hapus', 'destroy');
        });

    // Artikel
    Route::controller(ArtikelController::class)
        ->prefix('artikel')->group(function () {
            Route::get('/', 'index');
            Route::get('/tahun', 'tahun');
        });

    // Pengaturan Aplikasi
    Route::controller(PengaturanController::class)
        ->prefix('pengaturan')->group(function () {
            Route::get('/', 'index')->name('api.pengaturan_aplikasi');
            Route::post('/update', 'update');
        });
});

// Statistik
Route::controller(StatistikController::class)
    ->prefix('statistik-web')->group(function () {
        Route::get('/kategori-statistik', 'kategoriStatistik');
        Route::prefix('penduduk')->group(function () {
            Route::get('/', 'penduduk');
            Route::get('/tahun', 'refTahunPenduduk');
        });
        Route::prefix('keluarga')->group(function () {
            Route::get('/', 'keluarga');
            Route::get('/tahun', 'refTahunKeluarga');
        });
        Route::prefix('rtm')->group(function () {
            Route::get('/', 'rtm');
            Route::get('/tahun', 'refTahunRtm');
        });
        Route::get('/bantuan', 'bantuan');
        Route::get('/bantuan/tahun', [BantuanController::class, 'tahun']);
    });

// Bantuan
// Data utama website
Route::get('data-website', WebsiteController::class);
Route::get('data-summary', SummaryController::class);
// Desa teraktif
Route::get('/desa-aktif', [KategoriDesaController::class, 'index']);
