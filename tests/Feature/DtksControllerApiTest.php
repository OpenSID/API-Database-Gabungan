<?php

namespace Tests\Feature;

use App\Models\DTKS;
use App\Models\User;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DtksControllerApiTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_data_dtks()
    {
        $user = User::inRandomOrder()->first();
        Sanctum::actingAs($user);
        $url = '/api/v1/satu-data/dtks?'.http_build_query([]);
        $total = DTKS::count();        
        $response = $this->getJson($url);        
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonPath('meta.pagination.total', $total);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'attributes' => [                        
                        'nik_kepala_rtm',
                        'status_kepemilikan_bangunan_tempat_tinggal_yang_ditempati',
                        'luas_lantai_m2',
                        'jenis_lantai_terluas',
                        'jenis_dinding_terluas',
                        'jenis_atap_terluas',
                        'sumber_air_minum',
                        'sumber_penerangan_utama',
                        'bahan_bakar_energi_utama_untuk_memasak',
                        'kepemilikan_dan_penggunaan_fasilitas_tempat_buang_air_besar',
                        'tempat_pembuangan_akhir_tinja',
                    ],
                ],
            ],
        ]);
    }
}
