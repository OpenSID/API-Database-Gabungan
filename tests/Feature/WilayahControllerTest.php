<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class WilayahControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $user = User::inRandomOrder()->first();

        Sanctum::actingAs($user);
    }
    /**
     * A basic feature test example.
     */
    public function test_wilayah_desa(): void
    {
        $response = $this->get('/api/v1/wilayah/desa');

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'attributes' => [
                        'nama_desa',
                        'kode_desa',
                        'kode_pos',
                        'nama_kecamatan',
                        'kode_kecamatan',
                        'website',
                        'path',
                    ],
                ],
            ],
        ]);
    }

    public function test_wilayah_dusun(): void
    {
        $response = $this->get('/api/v1/wilayah/dusun');

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'attributes' => [
                        'config_id',
                        'rt',
                        'rw',
                        'dusun',
                        'dusun',
                        'lat',
                        'lng',
                        'zoom',
                        'path',
                        'map_tipe',
                        'warna',
                        'border',
                        'urut',
                        'urut_cetak',
                    ],
                ],
            ],
        ]);
    }

    public function test_wilayah_rt(): void
    {
        $response = $this->get('/api/v1/wilayah/rt');

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'attributes' => [
                        'config_id',
                        'rt',
                        'rw',
                        'dusun',
                        'dusun',
                        'lat',
                        'lng',
                        'zoom',
                        'path',
                        'map_tipe',
                        'warna',
                        'border',
                        'urut',
                        'urut_cetak',
                    ],
                ],
            ],
        ]);
    }

    public function test_wilayah_rw(): void
    {
        $response = $this->get('/api/v1/wilayah/rw');

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'attributes' => [
                        'config_id',
                        'rt',
                        'rw',
                        'dusun',
                        'dusun',
                        'lat',
                        'lng',
                        'zoom',
                        'path',
                        'map_tipe',
                        'warna',
                        'border',
                        'urut',
                        'urut_cetak',
                    ],
                ],
            ],
        ]);
    }

    public function test_wilayah_penduduk(): void
    {
        $response = $this->get('/api/v1/wilayah/penduduk');

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'attributes' => [
                        'app_key',
                        'nama_desa',
                        'kode_desa',
                        'kode_pos',
                        'nama_kecamatan',
                        'kode_kecamatan',
                        'nama_kepala_camat',
                        'nip_kepala_camat',
                        'nama_kabupaten',
                        'kode_kabupaten',
                        'nama_propinsi',
                        'kode_propinsi',
                        'logo',
                        'lat',
                        'lng',
                        'zoom',
                        'map_tipe',
                        'path',
                        'alamat_kantor',
                        'email_desa',
                        'telepon',
                        'nomor_operator',
                        'website',
                        'kantor_desa',
                        'warna',
                        'border',
                        'created_at',
                        'created_by',
                        'updated_at',
                        'updated_by',
                        'nama_kontak',
                        'hp_kontak',
                        'jabatan_kontak',
                        'penduduk_count',
                    ],
                ],
            ],
        ]);
    }
}
