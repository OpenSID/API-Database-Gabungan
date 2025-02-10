<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PariwisataTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_get_data_pariwisata(): void
    {
        $user = User::inRandomOrder()->first();

        Sanctum::actingAs($user);

        $url = '/api/v1/pariwisata';

        $response = $this->getJson($url);

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'attributes' => [                        
                        'nama_desa',
                        'kode_desa',
                        'jenis_hiburan',                        
                        'jumlah_penginapan',                        
                        'lokasi_tempat_area_wisata',                        
                        'keberadaan',                        
                        'luas',                        
                        'tingkat_pemanfaatan',                        
                    ],
                ],
            ],
        ]);
    }
}
