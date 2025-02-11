<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SuplemenTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $user = User::inRandomOrder()->first();
        Sanctum::actingAs($user);
    }
    /**
     * A basic feature test example.
     */
    public function test_get_data_suplemen(): void
    {
        $url = '/api/v1/suplemen';
        $response = $this->getJson($url);        
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'nama',
                    'sasaran',
                    'status',
                    'keterangan',
                    'terdata_count',
                    'aksi'
                ]
            ],
            'meta' => [
                'message',
                'pagination' => [
                    'total',
                    'count',
                    'per_page',
                    'current_page',
                    'total_pages',
                    'links'
                ]
            ]
        ]);
    }

    public function test_create_and_delete_suplemen(): void
    {
        $url = '/api/v1/suplemen';

        // 1. Create Suplemen (Sesuai dengan Validasi)
        $payload = [
            'sasaran' => 1, // Hanya bisa 1 atau 2
            'nama' => 'Suplemen Test',
            'keterangan' => 'Keterangan test',
            'status' => 1, // Hanya bisa 0 atau 1
            'sumber' => 'OpenKab',
            'form_isian' => '[]', // Bisa kosong atau JSON
        ];

        $response = $this->postJson($url, $payload);
        $response->assertStatus(201) // Karena di store() return 201
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'id',
                        'sasaran',
                        'nama',
                        'keterangan',
                        'status',
                        'sumber',
                        'form_isian',
                    ]
                ]);

        // Ambil ID dari respons
        $suplemenId = $response->json('data.id');
        $this->assertNotNull($suplemenId, 'ID Suplemen tidak ditemukan');

        // 2. Hapus Suplemen yang baru dibuat
        $deleteUrl = "/api/v1/suplemen/hapus/{$suplemenId}";
        $deleteResponse = $this->deleteJson($deleteUrl);

        // Pastikan berhasil dihapus
        $deleteResponse->assertStatus(200)
               ->assertJson([
                   'success' => true
               ]);
    }



    public function test_data_suplemen_sasaran(): void
    {
        $url = '/api/v1/suplemen/sasaran';
        $response = $this->getJson($url);        
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'success',
            'data' => [
                '*' => [
                    'id',
                    'nama',
                ]
            ]
        ]);
    }

    public function test_data_suplemen_status(): void
    {
        $url = '/api/v1/suplemen/status';
        $response = $this->getJson($url);        
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'success',
            'data' => [
                '*' => [
                    'id',
                    'nama',
                ]
            ]
        ]);
    }

    public function test_data_suplemen_terdata(): void
    {
        // get Suplemen
        $url = '/api/v1/suplemen';
        $res = $this->getJson($url);  
        $res->assertStatus(Response::HTTP_OK);

        // get sasaran
        $urlSasaran = '/api/v1/suplemen/sasaran';
        $resSasaran = $this->getJson($urlSasaran);  
        $resSasaran->assertStatus(Response::HTTP_OK);

        // Ambil semua data dari API
        $sasaran = $resSasaran->json();
        $suplemen = $res->json();

        $suplemenList = $suplemen['data'] ?? [];
        $sasaranList = $sasaran['data'] ?? [];

        if (!empty($sasaranList) && !empty($suplemenList)) {
            $sasaran = collect($sasaranList)->random();
            $suplemen = collect($suplemenList)->random();

            // Pastikan key 'id' ada
            if (isset($sasaran['id'], $suplemen['id'])) {
                $urlTerdata = "/api/v1/suplemen/terdata/{$sasaran['id']}/{$suplemen['id']}";
                dump($urlTerdata); // Debugging (opsional)
            } else {
                $this->fail('Key ID tidak ditemukan dalam Sasaran atau Suplemen.');
            }
        } else {
            $this->fail('Response Sasaran atau Suplemen kosong.');
        }

        $response = $this->getJson($urlTerdata);        
        $response->assertStatus(Response::HTTP_OK);

        $response->assertJsonStructure([
            'data' => [],
            'meta' => [
                'message',
                'pagination' => [
                    'total',
                    'count',
                    'per_page',
                    'current_page',
                    'total_pages',
                    'links' => [],
                ],
            ],
        ]);
        
    }
}
