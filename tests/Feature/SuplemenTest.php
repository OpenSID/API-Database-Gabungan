<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SuplemenTest extends TestCase
{
    /**
     * Test untuk mendapatkan data suplemen.
     */
    public function test_get_data_suplemen(): void
    {
        // Pastikan ada user di database
        $user = User::inRandomOrder()->first();
        $this->assertNotNull($user, "User tidak ditemukan di database. Pastikan ada data user.");

        // Autentikasi dengan Sanctum
        Sanctum::actingAs($user);

        // URL API yang akan diuji
        $url = '/api/v1/suplemen';

        // Kirim request ke API
        $response = $this->getJson($url);

        // Pastikan respons status adalah 200 (OK)
        $response->assertStatus(Response::HTTP_OK);

        // Periksa apakah struktur JSON sesuai
        $response->assertJsonStructure([
            'data' => [ // Sesuaikan jika API memiliki key "data"
                '*' => [
                    'nama',
                    'sasaran',
                    'status',
                    'keterangan'
                ]
            ]
        ]);
    }
}
