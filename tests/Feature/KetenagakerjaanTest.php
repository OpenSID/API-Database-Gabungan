<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;

class KetenagakerjaanTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_get_api_ketenagakerjaan(): void
    {
        $user = User::inRandomOrder()->first();
        
        Sanctum::actingAs($user);

        $url = '/api/v1/ketenagakerjaan';

        $response = $this->getJson($url);

        // Pastikan responsnya berhasil
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'attributes' => [                        
                        'nik',
                        'pekerjaan',
                        'jabatan',                        
                        'jumlah_penghasilan',                        
                        'pelatihan',                        
                    ],
                ],
            ],
        ]);
    }
}
