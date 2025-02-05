<?php

namespace Tests\Feature;

use App\Models\KeluargaDDK;
use App\Models\User;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DDKControllerApiTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_data_ddk()
    {
        $user = User::inRandomOrder()->first();
        Sanctum::actingAs($user);
        $url = '/api/v1/prodeskel/ddk/pangan?'.http_build_query([]);
        $total = KeluargaDDK::whereHas('prodeskelDDK', fn ($query) => $query->with(['produksi', 'detail', 'bahanGalianAnggota']))->count();        
        $response = $this->getJson($url);        
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonPath('meta.pagination.total', $total);        
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'attributes' => [
                        'nik',
                        'kepemilikan_lahan',
                        'produksi_tanaman_pangan',
                        'produksi_buah_buahan',
                        'produksi_tanaman_obat',
                        'produksi_perkebunan',
                        'produksi_hasil_ternak',
                        'produksi_perikanan',
                        'pola_makan_keluarga'
                    ],
                ],
            ],
        ]);
    }
}
