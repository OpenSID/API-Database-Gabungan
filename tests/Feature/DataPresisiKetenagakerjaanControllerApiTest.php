<?php

namespace Tests\Feature;

use App\Models\Config;
use App\Models\DataPresisiKetenagakerjaan;
use App\Models\Rtm;
use App\Models\User;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DataPresisiKetenagakerjaanControllerApiTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $user = User::inRandomOrder()->first();
        Sanctum::actingAs($user);
    }

    public function test_get_data_rtm()
    {
        $response = $this->getJson('/api/v1/data-presisi/ketenagakerjaan/rtm');

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
    public function test_get_all_ketenagakerjaan(): void
    {
        $url = '/api/v1/data-presisi/ketenagakerjaan';

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
                        'anggota_id',
                        'jenis_pekerjaan',
                        'tempat_kerja',
                        'frekwensi_mengikuti_pelatihan_setahun',
                        'jenis_pelatihan_diikuti_setahun',
                        'tanggal_pengisian',
                        'status_pengisian',
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

    public function test_get_ketenagakerjaan_by_kode_kecamatan(): void
    {
        $kodeKecamatan = Config::inRandomOrder()->first()->kode_kecamatan;

        $url = '/api/v1/data-presisi/ketenagakerjaan?'.http_build_query([
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
                        'anggota_id',
                        'jenis_pekerjaan',
                        'tempat_kerja',
                        'frekwensi_mengikuti_pelatihan_setahun',
                        'jenis_pelatihan_diikuti_setahun',
                        'tanggal_pengisian',
                        'status_pengisian',
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

    public function test_get_ketenagakerjaan_by_rtm_id(): void
    {
        $idRtm = Rtm::inRandomOrder()->first()->id;

        $url = '/api/v1/data-presisi/ketenagakerjaan?'.http_build_query([
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
                        'anggota_id',
                        'jenis_pekerjaan',
                        'tempat_kerja',
                        'frekwensi_mengikuti_pelatihan_setahun',
                        'jenis_pelatihan_diikuti_setahun',
                        'tanggal_pengisian',
                        'status_pengisian',
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

    public function test_updates_data_presisi_ketenagakerjaan_successfully()
    {
        $ketenagakerjaan = DataPresisiKetenagakerjaan::inRandomOrder()->first();
        if(!$ketenagakerjaan) {
            $this->markTestSkipped('Tidak ada data Ketenagakerjaan yang tersedia untuk pengujian.');
        }
        // Act: Kirim request update
        $response = $this->postJson("/api/v1/data-presisi/ketenagakerjaan/update/{$ketenagakerjaan->rtm_id}", [
            'form' => [
                [
                    'anggota_id' => $ketenagakerjaan->anggota_id,
                    'keluarga_id' => $ketenagakerjaan->keluarga_id,
                    'jenis_pekerjaan' => 'KARYAWAN SWASTA',
                    'tempat_kerja' => 'Di Dalam Desa',
                    'frekwensi_mengikuti_pelatihan_setahun' => '1 Kali',
                    'jenis_pelatihan_diikuti_setahun' => 'Menjahit',
                ]
            ]
        ]);

        // Assert: Periksa response sukses
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Berhasil Ubah Data'
            ]);
    }


}
