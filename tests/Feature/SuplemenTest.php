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

    public function test_create_data_suplemen(): void
    {
        $url = '/api/v1/suplemen';
        $response = $this->postJson($url);        
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
}
