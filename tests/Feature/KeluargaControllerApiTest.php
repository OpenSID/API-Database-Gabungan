<?php

namespace Tests\Feature;

use App\Models\Config;
use App\Models\Keluarga;
use App\Models\KeluargaDDK;
use App\Models\Penduduk;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Schema;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class KeluargaControllerApiTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_data_keluarga()
    {
        $user = User::inRandomOrder()->first();
        Sanctum::actingAs($user);
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
