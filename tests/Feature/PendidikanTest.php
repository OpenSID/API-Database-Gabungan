<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Response;

class PendidikanTest extends TestCase
{
    protected $token;

    public function setUp(): void
    {
        parent::setUp();

        $baseUrl = config('services.openkab.base_url');

        // Kirim permintaan ke API login untuk mendapatkan token
        $response = Http::post("{$baseUrl}/api/v1/signin", [
            'email' => config('services.openkab.email'),
            'password' => config('services.openkab.password'),
        ]);

        // Jika gagal, tampilkan error dan hentikan test
        if ($response->failed()) {
            $this->fail('Gagal login ke API eksternal: ' . $response->body());
        }

        // Simpan token untuk digunakan di test lain
        $this->token = $response->json('access_token');

    }

    /**
     * A basic feature test example.
     */
    public function test_get_api_pendidikan(): void
    {
        $url = '/api/v1/pendidikan';

        $response = $this->getJson($url, [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$this->token,
        ]); 

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
