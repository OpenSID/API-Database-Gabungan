<?php

namespace Tests\Feature;

use App\Models\Config;
use App\Models\User;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PosyanduApiTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $user = User::inRandomOrder()->first();
        $this->assertNotNull($user, "User tidak ditemukan di database. Pastikan ada data user.");
        Sanctum::actingAs($user);
    }

    public function test_get_data_posyandu()
    {
        $response = $this->getJson('/api/v1/statistik-web/posyandu');
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
            'data' => [
                '*' => [
                    'type',
                    'id',
                    'attributes' => [
                        'config_id',
                        'nama',
                        'alamat',
                    ],
                ],
            ],
            'meta' => [
                'pagination' => [
                    'total',
                    'count',
                    'per_page',
                    'current_page',
                    'total_pages',
                ],
            ],
            'links' => [
                'self',
                'first',
                'last',
            ],
        ]);
        
    }

    public function test_get_data_posyandu_by_config_id()
    {
        $config_id = Config::inRandomOrder()->first()->id;
        $response = $this->getJson('/api/v1/statistik-web/posyandu', [
           'filter[config_id]' => $config_id
        ]);
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
            'data' => [
                '*' => [
                    'type',
                    'id',
                    'attributes' => [
                        'config_id',
                        'nama',
                        'alamat',
                    ],
                ],
            ],
            'meta' => [
                'pagination' => [
                    'total',
                    'count',
                    'per_page',
                    'current_page',
                    'total_pages',
                ],
            ],
            'links' => [
                'self',
                'first',
                'last',
            ],
        ]);
        
    }
}
