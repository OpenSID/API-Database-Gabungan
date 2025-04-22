<?php

namespace Tests\Feature;

use App\Models\Config;
use App\Models\DataPresisiSeniBudaya;
use App\Models\Rtm;
use App\Models\User;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DataPresisiSeniBudayaControllerApiTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $user = User::inRandomOrder()->first();
        Sanctum::actingAs($user);
    }

    public function test_get_data_rtm()
    {
        $response = $this->getJson('/api/v1/data-presisi/seni-budaya/rtm');

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
    public function test_get_all_seni_budaya(): void
    {
        $url = '/api/v1/data-presisi/seni-budaya';

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
                        'jenis_seni_yang_dikuasai',
                        'jumlah_penghasilan_dari_seni',
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

    public function test_get_kesehatan_by_kode_kecamatan(): void
    {
        $kodeKecamatan = Config::inRandomOrder()->first()->kode_kecamatan;

        $url = '/api/v1/data-presisi/seni-budaya?'.http_build_query([
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
                        'jenis_seni_yang_dikuasai',
                        'jumlah_penghasilan_dari_seni',
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

    public function test_get_seni_budaya_by_rtm_id(): void
    {
        $idRtm = Rtm::inRandomOrder()->first()->id;

        $url = '/api/v1/data-presisi/seni-budaya?'.http_build_query([
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
                        'jenis_seni_yang_dikuasai',
                        'jumlah_penghasilan_dari_seni',
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

    public function test_updates_data_presisi_seni_budaya_successfully()
    {
        $seniBudaya = DataPresisiSeniBudaya::inRandomOrder()->first();

        // Act: Kirim request update
        $response = $this->postJson("/api/v1/data-presisi/seni-budaya/update/{$seniBudaya->rtm_id}", [
            'form' => [
                [
                    'anggota_id' => $seniBudaya->anggota_id,
                    'keluarga_id' => $seniBudaya->keluarga_id,
                    'jenis_seni_yang_dikuasai' => [
                        'jenis_seni_id' => '2',
                        'jenis_seni_value' => 'Seni Pertunjukan',
                        'sub_jenis_seni' => 'Musisi',
                    ],
                    'jumlah_penghasilan_dari_seni' => 500000
                ],
                [
                    'anggota_id' => $seniBudaya->anggota_id,
                    'keluarga_id' => $seniBudaya->keluarga_id,
                    'jenis_seni_yang_dikuasai' => [
                        'jenis_seni_id' => '3',
                        'jenis_seni_value' => 'Desain',
                        'sub_jenis_seni' => 'Desainer Produk',
                    ],
                    'jumlah_penghasilan_dari_seni' => 1800000
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
