<?php

namespace Tests\Feature;

use App\Models\Config;
use App\Models\DataPresisiPangan;
use App\Models\Rtm;
use App\Models\User;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DataPresisiPanganControllerApiTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $user = User::inRandomOrder()->first();
        Sanctum::actingAs($user);
    }

    public function test_get_data_rtm()
    {
        $response = $this->getJson('/api/v1/data-presisi/pangan/rtm');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'type',
                        'id',
                        'attributes' => [
                            'config_id',
                            'nik',
                            'no_kk',
                            'kepala_keluarga',
                            'dtks',
                            'jumlah_anggota',
                            'jumlah_kk',
                            'alamat',
                            'dusun',
                            'rw',
                            'rt',
                            'tgl_daftar',
                        ],
                    ],
                ],
                'meta' => [
                    'pagination' => [
                        'total',
                        'count',
                        'per_page',
                        'current_page',
                        'total_pages',
                    ],
                ],
                'links' => [
                    'self',
                    'first',
                    'next',
                    'last',
                ],
            ]);
    }

    



    /**
     * A basic feature test example.
     */
    public function test_get_all_pangan(): void
    {
        $url = '/api/v1/data-presisi/pangan';

        $response = $this->getJson($url);

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'type',
                    'id',
                    'attributes' => [
                        'config_id',
                        'nik',
                        'no_kk',
                        'nama',
                        'rtm_id',
                        'keluarga_id',
                        "jenis_lahan",
                        "luas_lahan",
                        "luas_tanam",
                        "status_lahan",
                        "komoditi_utama_tanaman_pangan",
                        "komoditi_tanaman_pangan_lainnya",
                        "jumlah_berdasarkan_jenis_komoditi",
                        "usia_komoditi",
                        "jenis_peternakan",
                        "jumlah_populasi",
                        "jenis_perikanan",
                        "frekwensi_makanan_perhari",
                        "frekwensi_konsumsi_sayur_perhari",
                        "frekwensi_konsumsi_buah_perhari",
                        "frekwensi_konsumsi_daging_perhari",
                        "tanggal_pengisian",
                        "status_pengisian"
                    ],
                ],
            ],
            'meta' => [
                'pagination' => [
                    'total',
                    'count',
                    'per_page',
                    'current_page',
                    'total_pages',
                ],
            ],
            'links' => [
                'self',
                'first',
                'last',
            ],
        ]);

    }

    public function test_get_pangan_by_kode_kecamatan(): void
    {
        $kodeKecamatan = Config::inRandomOrder()->first()->kode_kecamatan;

        $url = '/api/v1/data-presisi/pangan?'.http_build_query([
            'filter[kode_kecamatan]' => $kodeKecamatan
        ]);

        $response = $this->getJson($url);

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'type',
                    'id',
                    'attributes' => [
                        'config_id',
                        'nik',
                        'no_kk',
                        'nama',
                        'rtm_id',
                        'keluarga_id',
                        "jenis_lahan",
                        "luas_lahan",
                        "luas_tanam",
                        "status_lahan",
                        "komoditi_utama_tanaman_pangan",
                        "komoditi_tanaman_pangan_lainnya",
                        "jumlah_berdasarkan_jenis_komoditi",
                        "usia_komoditi",
                        "jenis_peternakan",
                        "jumlah_populasi",
                        "jenis_perikanan",
                        "frekwensi_makanan_perhari",
                        "frekwensi_konsumsi_sayur_perhari",
                        "frekwensi_konsumsi_buah_perhari",
                        "frekwensi_konsumsi_daging_perhari",
                        "tanggal_pengisian",
                        "status_pengisian"
                    ],
                ],
            ],
            'meta' => [
                'pagination' => [
                    'total',
                    'count',
                    'per_page',
                    'current_page',
                    'total_pages',
                ],
            ],
            'links' => [
                'self',
                'first',
                'last',
            ],
        ]);

    }

    public function test_get_pangan_by_rtm_id(): void
    {
        $idRtm = Rtm::inRandomOrder()->first()->id;

        $url = '/api/v1/data-presisi/pangan?'.http_build_query([
            'filter[rtm_id]' => $idRtm
        ]);

        $response = $this->getJson($url);

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'type',
                    'id',
                    'attributes' => [
                        'config_id',
                        'nik',
                        'no_kk',
                        'nama',
                        'rtm_id',
                        'keluarga_id',
                        "jenis_lahan",
                        "luas_lahan",
                        "luas_tanam",
                        "status_lahan",
                        "komoditi_utama_tanaman_pangan",
                        "komoditi_tanaman_pangan_lainnya",
                        "jumlah_berdasarkan_jenis_komoditi",
                        "usia_komoditi",
                        "jenis_peternakan",
                        "jumlah_populasi",
                        "jenis_perikanan",
                        "frekwensi_makanan_perhari",
                        "frekwensi_konsumsi_sayur_perhari",
                        "frekwensi_konsumsi_buah_perhari",
                        "frekwensi_konsumsi_daging_perhari",
                        "tanggal_pengisian",
                        "status_pengisian"
                    ],
                ],
            ],
            'meta' => [
                'pagination' => [
                    'total',
                    'count',
                    'per_page',
                    'current_page',
                    'total_pages',
                ],
            ],
            'links' => [
                'self',
                'first',
                'last',
            ],
        ]);

    }

    public function test_updates_data_presisi_pangan_successfully()
    {
        $pangan = DataPresisiPangan::inRandomOrder()->first();

        // Act: Kirim request update
        $response = $this->postJson("/api/v1/data-presisi/pangan/update/{$pangan->rtm_id}", [
            'keluarga_id' => $pangan->keluarga_id,
            "jenis_lahan" => "Perikanan",
            "luas_lahan" => "34",
            "luas_tanam" => "57",
            "status_lahan" => "Milik Sendiri",
            "komoditi_utama_tanaman_pangan" => "Bawang Merah",
            "komoditi_tanaman_pangan_lainnya" => "Brokoli",
            "jumlah_berdasarkan_jenis_komoditi" => "200",
            "usia_komoditi" => "1 bulan s/d 5 bulan",
            "jenis_peternakan" => "Angsa",
            "jumlah_populasi" => "100",
            "jenis_perikanan" => "Barabara",
            "frekwensi_makanan_perhari" => "2 Kali",
            "frekwensi_konsumsi_sayur_perhari" => "1 Kali",
            "frekwensi_konsumsi_buah_perhari" => "2 Kali",
            "frekwensi_konsumsi_daging_perhari" => "3 Kali"
        ]);
        

        // Assert: Periksa response sukses
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Berhasil Ubah Data'
            ]);
    }


}
