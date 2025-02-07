<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class InfrastrukturTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_get_data_infrastruktur(): void
    {
        $user = User::inRandomOrder()->first();

        Sanctum::actingAs($user);

        $url = '/api/v1/infrastruktur';

        $response = $this->getJson($url);

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJsonStructure([
            '*' => [
                'kategori',
                'jenis_sarana',
                'kondisi_baik',
                'kondisi_rusak',
                'jumlah',
                'satuan'
            ]
        ]);
    }
}
