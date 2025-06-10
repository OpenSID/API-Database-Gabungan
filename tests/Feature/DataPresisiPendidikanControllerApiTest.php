<?php

namespace Tests\Feature;

use App\Models\Config;
use App\Models\DataPresisiPendidikan;
use App\Models\Rtm;
use App\Models\User;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DataPresisiPendidikanControllerApiTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $user = User::inRandomOrder()->first();
        Sanctum::actingAs($user);
    }

    public function test_get_data_rtm()
    {
        $response = $this->getJson('/api/v1/data-presisi/pendidikan/rtm');

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
    public function test_get_all_pendidikan(): void
    {
        $url = '/api/v1/data-presisi/pendidikan';

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
                        "pendidikan_dalam_kk",
                        "pendidikan_sedang_ditempuh",
                        "keikutsertaan_kip",
                        "jenis_pendidikan_kesetaraan_yg_diikuti",
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

    public function test_get_pendidikan_by_kode_kecamatan(): void
    {
        $kodeKecamatan = Config::inRandomOrder()->first()->kode_kecamatan;

        $url = '/api/v1/data-presisi/pendidikan?'.http_build_query([
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
                        "pendidikan_dalam_kk",
                        "pendidikan_sedang_ditempuh",
                        "keikutsertaan_kip",
                        "jenis_pendidikan_kesetaraan_yg_diikuti",
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

    public function test_get_pendidikan_by_rtm_id(): void
    {
        $idRtm = Rtm::inRandomOrder()->first()->id;

        $url = '/api/v1/data-presisi/pendidikan?'.http_build_query([
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
                        "pendidikan_dalam_kk",
                        "pendidikan_sedang_ditempuh",
                        "keikutsertaan_kip",
                        "jenis_pendidikan_kesetaraan_yg_diikuti",
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

    public function test_updates_data_presisi_pendidikan_successfully()
    {
        $pendidikan = DataPresisiPendidikan::inRandomOrder()->first();
        if(!$pendidikan) {
            $this->markTestSkipped('Tidak ada data pendidikan yang tersedia untuk pengujian.');
        }
        // Act: Kirim request update
        $response = $this->postJson("/api/v1/data-presisi/pendidikan/update/{$pendidikan->rtm_id}", [
            'form' => [
                [
                    'anggota_id' => $pendidikan->anggota_id,
                    'keluarga_id' => $pendidikan->keluarga_id,
                    "pendidikan_dalam_kk" => "SLTP / SEDERAJAT",
                    "pendidikan_sedang_ditempuh" => "SEDANG D-3/SEDERAJAT",
                    "keikutsertaan_kip" => "Peserta",
                    "jenis_pendidikan_kesetaraan_yg_diikuti" => "Kejar Paket B"
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
