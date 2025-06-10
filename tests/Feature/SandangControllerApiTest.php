<?php

namespace Tests\Feature;

use App\Models\Config;
use App\Models\Rtm;
use App\Models\Sandang;
use App\Models\User;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SandangControllerApiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $user = User::inRandomOrder()->first();
        Sanctum::actingAs($user);
    }

    public function test_get_data_rtm()
    {
        $response = $this->getJson('/api/v1/data-presisi/sandang/rtm');

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
    public function test_get_all_sandang(): void
    {
        $url = '/api/v1/data-presisi/sandang';

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
                        'jml_pakaian_yg_dimiliki',
                        'frekwensi_beli_pakaian_pertahun',
                        'jenis_pakaian',
                        'frekwensi_ganti_pakaian',
                        'tmpt_cuci_pakaian',
                        'jml_pakaian_seragam',
                        'jml_pakaian_sembahyang',
                        'jml_pakaian_kerja',
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

    public function test_get_sandang_by_kode_kecamatan(): void
    {
        $kodeKecamatan = Config::inRandomOrder()->first()->kode_kecamatan;

        $url = '/api/v1/data-presisi/sandang?'.http_build_query([
            'filter[kode_kecamatan]' => $kodeKecamatan,
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
                        'jml_pakaian_yg_dimiliki',
                        'frekwensi_beli_pakaian_pertahun',
                        'jenis_pakaian',
                        'frekwensi_ganti_pakaian',
                        'tmpt_cuci_pakaian',
                        'jml_pakaian_seragam',
                        'jml_pakaian_sembahyang',
                        'jml_pakaian_kerja',
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

    public function test_get_sandang_by_rtm_id(): void
    {
        $idRtm = Rtm::inRandomOrder()->first()->id;

        $url = '/api/v1/data-presisi/sandang?'.http_build_query([
            'filter[rtm_id]' => $idRtm,
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
                        'jml_pakaian_yg_dimiliki',
                        'frekwensi_beli_pakaian_pertahun',
                        'jenis_pakaian',
                        'frekwensi_ganti_pakaian',
                        'tmpt_cuci_pakaian',
                        'jml_pakaian_seragam',
                        'jml_pakaian_sembahyang',
                        'jml_pakaian_kerja',
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

    public function test_updates_data_presisi_sandang_successfully()
    {
        $sandang = Sandang::inRandomOrder()->first();
        if (!$sandang) {
            $this->markTestSkipped('No Sandang data available for testing.');
        }
        // Act: Kirim request update
        $response = $this->postJson("/api/v1/data-presisi/sandang/update/{$sandang->rtm_id}", [
            'form' => [
                [
                    'frekwensi_beli_pakaian_pertahun' => '2 Kali',
                    'frekwensi_ganti_pakaian' => '1 Kali',
                    'anggota_id' => $sandang->anggota_id,
                    'keluarga_id' => $sandang->keluarga_id,
                    'jenis_pakaian' => 'Pakaian Jadi',
                    'jml_pakaian_kerja' => '2 Stel',
                    'jml_pakaian_sembahyang' => '1 Stel',
                    'jml_pakaian_seragam' => '1 Stel',
                    'jml_pakaian_yg_dimiliki' => '3 Stel',
                    'tmpt_cuci_pakaian' => 'Cuci Sendiri',
                ],
            ],
        ]);

        // Assert: Periksa response sukses
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Berhasil Ubah Data',
            ]);
    }
}
