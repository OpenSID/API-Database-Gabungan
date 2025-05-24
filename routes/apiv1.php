<?php

use App\Http\Controllers\Api\ArtikelController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\BantuanController;
use App\Http\Controllers\Api\BantuanKabupatenController;
use App\Http\Controllers\Api\ConfigController;
use App\Http\Controllers\Api\DasborController;
use App\Http\Controllers\Api\DataController;
use App\Http\Controllers\Api\DataPresisiAdatController;
use App\Http\Controllers\Api\DataPresisiAgamaController;
use App\Http\Controllers\Api\DataPresisiJaminanSosialController;
use App\Http\Controllers\Api\DataPresisiKesehatanController;
use App\Http\Controllers\Api\DataPresisiKetenagakerjaanController;
use App\Http\Controllers\Api\DataPresisiPanganController;
use App\Http\Controllers\Api\DataPresisiPendidikanController;
use App\Http\Controllers\Api\DataPresisiSeniBudayaController;
use App\Http\Controllers\Api\DDKController;
use App\Http\Controllers\Api\DesaController;
use App\Http\Controllers\Api\DokumenController;
use App\Http\Controllers\Api\DTKSController;
use App\Http\Controllers\Api\GolonganDarahController;
use App\Http\Controllers\Api\InfrastrukturController;
use App\Http\Controllers\Api\KategoriController;
use App\Http\Controllers\Api\KategoriDesaController;
use App\Http\Controllers\Api\KecamatanController;
use App\Http\Controllers\Api\KelasSosialController;
use App\Http\Controllers\Api\KelembagaanController;
use App\Http\Controllers\Api\KeluargaController;
use App\Http\Controllers\Api\KesehatanWebsiteController;
use App\Http\Controllers\Api\KetenagakerjaanController;
use App\Http\Controllers\Api\KeuanganController;
use App\Http\Controllers\Api\LaporanPendudukController;
use App\Http\Controllers\Api\LogController;
use App\Http\Controllers\Api\OpendkSynchronizeController;
use App\Http\Controllers\Api\PapanPresisiController;
use App\Http\Controllers\Api\PariwisataController;
use App\Http\Controllers\Api\PekerjaanController;
use App\Http\Controllers\Api\PembangunanController;
use App\Http\Controllers\Api\PendidikanController;
use App\Http\Controllers\Api\PendudukController;
use App\Http\Controllers\Api\PengaturanController;
use App\Http\Controllers\Api\PlanController;
use App\Http\Controllers\Api\PointController;
use App\Http\Controllers\Api\RtmController;
use App\Http\Controllers\Api\StatusKawinController;
use App\Http\Controllers\Api\PrasaranaSaranaController;
use App\Http\Controllers\Api\SandangController;
use App\Http\Controllers\Api\SettingModulController;
use App\Http\Controllers\Api\StatistikController;
use App\Http\Controllers\Api\SummaryController;
use App\Http\Controllers\Api\SuplemenController;
use App\Http\Controllers\Api\WebsiteController;
use App\Http\Controllers\Api\WilayahController;
use Illuminate\Http\Request;
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
Route::post('/signin', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('validate-token', function (Request $request) {
        $user = $request->user();

        // Check if the user has an authenticated token
        if ($user && $user->currentAccessToken()) {
            // Get the current access token
            $token = $user->currentAccessToken();

            // Fetch the abilities associated with the token
            $abilities = $token->abilities;

            return response()->json([
                'user' => $user,
                'abilities' => $abilities,
            ]);
        }

        return response()->json([
            'message' => 'No active token found.',
        ], 401);
    });

    // Dasbor
    Route::prefix('dasbor')->group(function () {
        Route::get('/', DasborController::class);
    });

    Route::get('setting-modul', [SettingModulController::class, 'index']);

    Route::get('/pariwisata', PariwisataController::class);
    Route::get('/infrastruktur', [InfrastrukturController::class, 'data']);

    // API Data Presisi
    Route::get('/ketenagakerjaan', KetenagakerjaanController::class);
    Route::get('/pendidikan', PendidikanController::class);

    Route::prefix('data-presisi')->group(function () {
        Route::controller(SandangController::class)
            ->prefix('sandang')->group(function () {
                Route::get('/', 'sandang');
                Route::post('/update/{id}', 'update');
                Route::get('/rtm', 'rtm');
            });

        Route::controller(DataPresisiKesehatanController::class)
            ->prefix('kesehatan')->group(function () {
                Route::get('/', 'kesehatan');
                Route::post('/update/{id}', 'update');
                Route::get('/rtm', 'rtm');
            });

        Route::controller(DataPresisiSeniBudayaController::class)
            ->prefix('seni-budaya')->group(function () {
                Route::get('/', 'seniBudaya');
                Route::post('/update/{id}', 'update');
                Route::get('/rtm', 'rtm');
            });

        Route::controller(DataPresisiKetenagakerjaanController::class)
            ->prefix('ketenagakerjaan')->group(function () {
                Route::get('/', 'ketenagakerjaan');
                Route::post('/update/{id}', 'update');
                Route::get('/rtm', 'rtm');
            });

        Route::controller(DataPresisiAdatController::class)
            ->prefix('adat')->group(function () {
                Route::get('/', 'index');
            });

        Route::controller(DataPresisiAgamaController::class)
        ->prefix('agama')->group(function () {
            Route::get('/', 'index');
        });

        Route::controller(DataPresisiSeniBudayaController::class)
            ->prefix('seni-budaya')->group(function () {
                Route::get('/', 'seniBudaya');
                Route::post('/update/{id}', 'update');
                Route::get('/rtm', 'rtm');
            });

        Route::controller(DataPresisiJaminanSosialController::class)
            ->prefix('jaminan-sosial')->group(function () {
                Route::get('/', 'index');
            });

        Route::controller(DataPresisiPendidikanController::class)
            ->prefix('pendidikan')->group(function () {
                Route::get('/', 'pendidikan');
                Route::post('/update/{id}', 'update');
                Route::get('/rtm', 'rtm');
            });

        Route::controller(DataPresisiPanganController::class)
            ->prefix('pangan')->group(function () {
                Route::get('/', 'pangan');
                Route::post('/update/{id}', 'update');
                Route::get('/rtm', 'rtm');
            });
    });

    // Wilayah
    Route::prefix('wilayah')->middleware([])->group(function () {
        Route::get('desa', [WilayahController::class, 'desa']);
        Route::get('dusun', [WilayahController::class, 'dusun']);
        Route::get('rw', [WilayahController::class, 'rw']);
        Route::get('rt', [WilayahController::class, 'rt']);
        Route::get('id', [WilayahController::class, 'wilayahId']);
        Route::post('store/dusun', [WilayahController::class, 'storeDusun']);
        // Route::get('id/{config_id}', [WilayahController::class, 'wilayahId']);
    });

    // RTM
    Route::prefix('rtm')->middleware([])->group(function () {
        Route::post('/store', [RtmController::class, 'store']);
    });

    // penduduk
    // config
    Route::prefix('config')->middleware([])->group(function () {
        Route::get('desa', [ConfigController::class, 'index']);
        Route::get('kecamatan', [ConfigController::class, 'kecamatan']);
        Route::get('kabupaten', [ConfigController::class, 'kabupaten']);
    });

    Route::prefix('penduduk')->middleware([])->group(function () {
        Route::get('/', [PendudukController::class, 'index']);
        Route::get('/kepala-keluarga', [PendudukController::class, 'pendudukDemoSeeder']);
        Route::post('/update-penduduk-by-kk-level', [PendudukController::class, 'updatePendudukByKkLevel']);
        Route::post('/store', [PendudukController::class, 'store']);

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

    // Log
    Route::controller(LogController::class)
        ->prefix('log')->group(function () {
            Route::get('/penduduk/{config_id}', 'generateLogPenduduk');
            Route::get('/keluarga/{config_id}', 'generateLogKeluarga');
        });

    // Keluarga
    Route::controller(KeluargaController::class)
        ->prefix('keluarga')->group(function () {
            Route::get('/', 'keluarga');
            Route::get('/show', 'show');
            Route::get('/summary', 'summary');
            Route::post('/store', 'store');
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
            Route::post('/store-seeder', 'insertKategoriSeeder');
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

    // Prodeskel
    Route::prefix('prodeskel')->group(function () {
        Route::prefix('ddk')->group(function () {
            Route::get('pangan', [DDKController::class, 'pangan']);
        });
        Route::prefix('potensi')->group(function () {
            Route::get('prasarana-sarana', [PrasaranaSaranaController::class, 'prasaranaSarana']);
            Route::get('kelembagaan', [KelembagaanController::class, 'kelembagaan']);
            Route::get('kelembagaan/penduduk', [KelembagaanController::class, 'kelembagaan_penduduk']);
        });
    });

    // Satu Data
    Route::prefix('satu-data')->group(function () {
        Route::get('dtks', DTKSController::class);
    });

    Route::prefix('presisi')->group(function () {
        Route::get('papan', PapanPresisiController::class);
    });

    Route::prefix('data')->group(function () {
        Route::controller(DataController::class)->group(function () {
            Route::get('/kesehatan', 'kesehatan');
            Route::get('/jaminan-sosial', 'jaminanSosial');
            Route::get('/penduduk-potensi-kelembagaan', 'pendudukPotensiKelembagaan');
        });
    });

    // Sinkronisasi OpenDK
    Route::prefix('opendk')->group(function () {
        Route::get('', [OpendkSynchronizeController::class, 'index'])->name('synchronize.opendk.index');
        Route::middleware(['abilities:synchronize-opendk-create'])->group(function () {
            Route::get('data', [OpendkSynchronizeController::class, 'getData']);
            Route::get('/sync-penduduk-opendk', [PendudukController::class, 'syncPendudukOpenDk']);
            Route::get('laporan-penduduk', [LaporanPendudukController::class, 'index']);
        });
    });

    // Sinkronisasi OpenDK
    Route::prefix('opendk')->group(function () {
        Route::get('', [OpendkSynchronizeController::class, 'index'])->name('synchronize.opendk.index');
        Route::middleware(['abilities:synchronize-opendk-create'])->group(function () {
            Route::get('data', [OpendkSynchronizeController::class, 'getData']);
            Route::get('/sync-penduduk-opendk', [PendudukController::class, 'syncPendudukOpenDk']);
            Route::get('laporan-penduduk', [LaporanPendudukController::class, 'index']);
        });
    });

    Route::middleware(['abilities:synchronize-opendk-create'])->group(function () {
        Route::get('desa', [DesaController::class, 'index']);
        Route::prefix('opendk')->group(function () {
            Route::get('profile/{kec}', [KecamatanController::class, 'all']);
            Route::get('desa/{kec}', [DesaController::class, 'all']);
            Route::get('pembangunan', [PembangunanController::class, 'syncPembangunanOpenDk']);
            Route::get('pembangunan/{id}', [PembangunanController::class, 'getPembangunanOpenDk']);
            Route::get('pembangunan-rincian/{id}/{kode_desa}', [PembangunanController::class, 'getPembangunanRincianOpenDk']);
            Route::get('bantuan', [BantuanController::class, 'syncBantuanOpenDk']);
            Route::get('bantuan/{id}', [BantuanController::class, 'getBantuanOpenDk']);
            Route::get('bantuan-peserta', [BantuanController::class, 'syncBantuanPesertaOpenDk']);
            Route::get('bantuan-peserta/{id}/{kode_desa}', [BantuanController::class, 'getBantuanPesertaOpenDk']);
        });
        Route::prefix('keuangan')->group(function () {
            Route::get('apbdes', [KeuanganController::class, 'apbdes']);
            Route::get('laporan_apbdes', [KeuanganController::class, 'laporan_apbdes']);
            Route::get('summary', [KeuanganController::class, 'summary']);
        });
    });

    Route::prefix('suplemen')->group(function () {
        Route::post('/', [SuplemenController::class, 'store']);
        Route::post('terdata/hapus', [SuplemenController::class, 'delete_multiple'])->name('suplemen-terdata.delete-multiple');
        Route::post('update/{id}', [SuplemenController::class, 'update']);
        Route::get('', [SuplemenController::class, 'index']);
        Route::get('terdata/{sasaran}/{id}', [SuplemenController::class, 'detail']);
        Route::get('sasaran', [SuplemenController::class, 'sasaran']);
        Route::get('status', [SuplemenController::class, 'status']);
        Route::delete('hapus/{id}', [SuplemenController::class, 'destroy'])->name('suplemen.hapus');
    });

    Route::prefix('point')->group(function () {
        Route::get('', [PointController::class, 'index']);
        Route::get('status', [PointController::class, 'status']);
        Route::delete('hapus/{id}', [PointController::class, 'destroy'])->name('point.hapus');
        Route::post('store', [PointController::class, 'store'])->name('point.store');
        Route::post('multiple-delete', [PointController::class, 'delete_multiple'])->name('point.delete-multiple');
        Route::put('/update/{id}', [PointController::class, 'update']);
        Route::put('/lock/{id}', [PointController::class, 'lock']);
        Route::post('', [PointController::class, 'store']);
    });

    Route::get('/subpoint/{id}', [PointController::class, 'detail']);
    Route::get('/plan', [PlanController::class, 'index']);


    // Status Kawin
    Route::prefix('status-kawin')->group(function () {
        Route::get('/count', [StatusKawinController::class, 'count']);
    });

    // Pekerjaan
    Route::prefix('pekerjaan')->group(function () {
        Route::get('/count', [PekerjaanController::class, 'count']);
    });

    // Pendidikan KK
    Route::prefix('pendidikan-kk')->group(function () {
        Route::get('/count', [PendidikanController::class, 'countPendidikanKK']);
    });

    // Golongan darah
    Route::prefix('golongan-darah')->group(function () {
        Route::get('/count', [GolonganDarahController::class, 'count']);
    });
    
    // Kelas Sosial
    Route::prefix('kelas-sosial')->group(function () {
        Route::get('/count', [KelasSosialController::class, 'count']);
    });
    
    // config
    Route::prefix('config')->group(function () {
        Route::get('/', [ConfigController::class, 'index']);
    });

    Route::get('/pendidikan/count', [PendidikanController::class, 'countPendidikan']);

});

Route::get('/plan/get-list-coordinate/{parrent?}/{id?}', [PlanController::class, 'getListCoordinate']);

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
        Route::prefix('posyandu')->group(function () {
            Route::get('/', 'posyandu');
        });
        Route::get('/bantuan', 'bantuan');
        Route::get('/bantuan/tahun', [BantuanController::class, 'tahun']);
        Route::get('/get-list-coordinate', 'getListCoordinate');
        Route::get('/get-list-program', 'getListProgram');
        Route::get('/get-list-tahun', 'getListTahun');
        Route::get('/get-list-kabupaten', 'getListKabupaten');
        Route::get('/get-list-kecamatan/{id}', 'getListKecamatan');
        Route::get('/get-list-desa/{id}', 'getListDesa');
        Route::get('/get-list-penerima', 'getListPenerimaBantuan');
    });

// wilayah
// Wilayah
Route::prefix('wilayah')->group(function () {
    Route::get('penduduk', [WilayahController::class, 'penduduk']);
    Route::get('penduduk-kecamatan', [WilayahController::class, 'kecamatan']);
});

// Bantuan
// Data utama website
Route::get('data-website', WebsiteController::class);
Route::get('data-summary', SummaryController::class);
Route::get('data-kesehatan', KesehatanWebsiteController::class);
// Desa teraktif
Route::get('/desa-aktif', [KategoriDesaController::class, 'index']);

Route::prefix('opendk')->group(function () {
    Route::post('/penduduk-nik-tanggalahir', [PendudukController::class, 'pendudukNikTanggalahir']);
});
