<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;

class PendidikanTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_get_api_pendidikan(): void
    {
        $user = User::inRandomOrder()->first();
        
        Sanctum::actingAs($user);

        $url = '/api/v1/pendidikan';

        $response = $this->getJson($url); 

        // Pastikan responsnya berhasil
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'attributes' => [                        
                        'nik',
                        'pendidikan_kk_id',
                        'pendidikan_sedang_id',                        
                        'partisipasi_sekolah',                        
                        'pendidikan_tertinggi',                        
                        'kelas_tertinggi',                        
                        'ijazah_tertinggi',                        
                    ],
                ],
            ],
        ]);
    }
}
