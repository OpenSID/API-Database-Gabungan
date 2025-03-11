<?php

namespace Tests\Feature;

use App\Models\Keluarga;
use App\Models\User;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class KeluargaControllerApiTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $user = User::inRandomOrder()->first();
        Sanctum::actingAs($user);
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_data_keluarga_with_kode_kecamatan()
    {
        $keluarga = Keluarga::with('config')->inRandomOrder()->first();
        $url = '/api/v1/keluarga?'.http_build_query([
            'filter[kode_kecamatan]' => $keluarga->config->kode_kecamatan,
        ]);
        $response = $this->getJson($url);
        $response->assertStatus(Response::HTTP_OK);        
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'attributes' => [
                        'no_kk',                        
                        'alamat_plus_dusun',
                        'rt',
                        'rw',
                        'kecamatan',
                        'kabupaten',
                        'provinsi',
                        'kode_pos',
                        'anggota',
                        'desa',
                    ],
                ],
            ],
        ]);
    }

    public function test_get_data_keluarga_with_id()
    {
        $keluarga = Keluarga::inRandomOrder()->first();
        $url = '/api/v1/keluarga?'.http_build_query([
            'filter[id]' => $keluarga->id,
        ]);
        $response = $this->getJson($url);
        $response->assertStatus(Response::HTTP_OK);        
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'attributes' => [
                        'no_kk',                        
                        'alamat_plus_dusun',
                        'rt',
                        'rw',
                        'kecamatan',
                        'kabupaten',
                        'provinsi',
                        'kode_pos',
                        'anggota',
                        'desa',
                    ],
                ],
            ],
        ]);
    }

    public function test_get_list_data_keluarga()
    {
        $keluarga = Keluarga::inRandomOrder()->whereHas('anggota')->first();
        $url = '/api/v1/keluarga/show?'.http_build_query([
            'no_kk' => $keluarga->no_kk,
        ]);
        $response = $this->getJson($url);
        $response->assertStatus(Response::HTTP_OK);        
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'attributes' => [
                        'no_kk',                        
                        'alamat_plus_dusun',
                        'rt',
                        'rw',
                        'kecamatan',
                        'kabupaten',
                        'provinsi',
                        'kode_pos',
                        'anggota',
                        'desa',
                    ],
                ],
            ],
        ]);
    }
}
