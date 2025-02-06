<?php

namespace Tests\Feature;

use App\Models\KeluargaDDK;
use App\Models\Penduduk;
use App\Models\Potensi;
use App\Models\User;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class KelembagaanControllerApiTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_data_kelembagaan()
    {
        $user = User::inRandomOrder()->first();
        Sanctum::actingAs($user);
        $url = '/api/v1/prodeskel/potensi/kelembagaan?'.http_build_query([]);
        $total = Potensi::where('kategori', 'lembaga-adat')->count();        
        $response = $this->getJson($url);        
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonPath('meta.pagination.total', $total);        
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'attributes' => [
                        'data',                        
                    ],
                ],
            ],
        ]);
    }

    public function test_get_data_kelembagaan_penduduk()
    {
        $user = User::inRandomOrder()->first();
        Sanctum::actingAs($user);
        $url = '/api/v1/prodeskel/potensi/kelembagaan/penduduk?'.http_build_query([]);
        $total = Penduduk::count();        
        $response = $this->getJson($url);        
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonPath('meta.pagination.total', $total);        
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'attributes' => [
                        'nik',
                        'agama', 
                        'suku',                       
                    ],
                ],
            ],
        ]);
    }
}
