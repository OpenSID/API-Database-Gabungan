<?php

namespace Tests\Feature;

use App\Models\Config;
use App\Models\DataPresisiKesehatan;
use App\Models\Rtm;
use App\Models\User;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DataPresisiKesehatanControllerApiTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $user = User::inRandomOrder()->first();
        Sanctum::actingAs($user);
    }

    public function test_get_data_rtm()
    {
        $response = $this->getJson('/api/v1/data-presisi/kesehatan/rtm');

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
    public function test_get_all_kesehatan(): void
    {
        $url = '/api/v1/data-presisi/kesehatan';

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
                        'jns_ansuransi',
                        'jns_penggunaan_alat_kontrasepsi',
                        'jns_penyakit_diderita',
                        'frekwensi_kunjungan_faskes_pertahun',
                        'frekwensi_rawat_inap_pertahun',
                        'frekwensi_kunjungan_dokter_pertahun',
                        'kondisi_fisik_sejak_lahir',
                        'status_gizi_balita',
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

        $url = '/api/v1/data-presisi/kesehatan?'.http_build_query([
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
                        'jns_ansuransi',
                        'jns_penggunaan_alat_kontrasepsi',
                        'jns_penyakit_diderita',
                        'frekwensi_kunjungan_faskes_pertahun',
                        'frekwensi_rawat_inap_pertahun',
                        'frekwensi_kunjungan_dokter_pertahun',
                        'kondisi_fisik_sejak_lahir',
                        'status_gizi_balita',
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

    public function test_get_kesehatan_by_rtm_id(): void
    {
        $idRtm = Rtm::inRandomOrder()->first()->id;

        $url = '/api/v1/data-presisi/kesehatan?'.http_build_query([
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
                        'jns_ansuransi',
                        'jns_penggunaan_alat_kontrasepsi',
                        'jns_penyakit_diderita',
                        'frekwensi_kunjungan_faskes_pertahun',
                        'frekwensi_rawat_inap_pertahun',
                        'frekwensi_kunjungan_dokter_pertahun',
                        'kondisi_fisik_sejak_lahir',
                        'status_gizi_balita',
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

    public function test_updates_data_presisi_kesehatan_successfully()
    {
        $kesehatan = DataPresisiKesehatan::inRandomOrder()->first();
        if (!$kesehatan) {
            $this->markTestSkipped('Tidak ada data kesehatan yang tersedia untuk pengujian.');
        }
        // Act: Kirim request update
        $response = $this->postJson("/api/v1/data-presisi/kesehatan/update/{$kesehatan->rtm_id}", [
            'form' => [
                [
                    'jns_ansuransi' => 'JKN Mandiri',
                    'jns_penggunaan_alat_kontrasepsi' => 'Pil',
                    'anggota_id' => $kesehatan->anggota_id,
                    'keluarga_id' => $kesehatan->keluarga_id,
                    'jns_penyakit_diderita' => 'Kanker',
                    'frekwensi_kunjungan_faskes_pertahun' => '> 3 Kali',
                    'frekwensi_rawat_inap_pertahun' => '2 Kali',
                    'frekwensi_kunjungan_dokter_pertahun' => '3 Kali',
                    'kondisi_fisik_sejak_lahir' => 'Lengkap',
                    'status_gizi_balita' => 'Sehat'
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
