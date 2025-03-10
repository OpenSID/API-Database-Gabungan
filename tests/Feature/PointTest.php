<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Point;
use App\Models\Lokasi;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Illuminate\Http\Response;

class PointTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $user = User::inRandomOrder()->first();
        $this->assertNotNull($user, "User tidak ditemukan di database. Pastikan ada data user.");
        Sanctum::actingAs($user);
    }

    public function test_get_data_point()
    {
        $response = $this->getJson('/api/v1/point');
        $response->assertStatus(Response::HTTP_OK)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['nama', 'enabled', 'path_simbol']
                     ]
                 ]);
    }

    public function test_get_status_point()
    {
        $response = $this->getJson('/api/v1/point/status');
        $response->assertStatus(Response::HTTP_OK)
                 ->assertJsonStructure(['success', 'data' => [['id', 'nama']]]);
    }

    public function test_store_point()
    {
        $data = [
            'nama' => 'test point',
            'simbol' => 'gambar',
            'parrent' => '0',
            'tipe' => 1,
            'sumber' => 'OpenKab'
        ];

        $response = $this->postJson('/api/v1/point', $data);
        $response->assertStatus(Response::HTTP_CREATED)
                 ->assertJson(['success' => true, 'message' => 'Data berhasil disimpan.']);
    }

    public function test_get_data_plan()
    {
        $response = $this->getJson('/api/v1/plan');
        $response->assertStatus(Response::HTTP_OK)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['nama', 'enabled', 'jenis']
                     ]
                 ]);
    }
    
}
