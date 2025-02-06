<?php

namespace Tests\Feature;

use App\Models\DTKS;
use App\Models\KeluargaDDK;
use App\Models\Komoditas;
use App\Models\User;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PrasaranaSaranaControllerApiTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_data_prasarana()
    {
        $user = User::inRandomOrder()->first();
        Sanctum::actingAs($user);
        $url = '/api/v1/prodeskel/potensi/prasarana-sarana?'.http_build_query([]);
        $total = Komoditas::count();        
        $response = $this->getJson($url);        
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonPath('meta.pagination.total', $total);        
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'attributes' => [
                        'config_id',
                        'tahun',
                        'komoditas',
                        'kategori',
                        'data',
                    ],
                ],
            ],
        ]);
    }
}
