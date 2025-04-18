<?php

namespace Tests\Feature;

use App\Models\Papan;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Schema;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PapanPresisiControllerApiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        if(!Schema::connection('openkab')->hasTable('data_presisi_papan')) {
            $this->markTestSkipped('Tabel data_presisi_papan belum ada');
        }
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_data_papan_presisi()
    {
        $user = User::inRandomOrder()->first();
        Sanctum::actingAs($user);
        $url = '/api/v1/presisi/papan?'.http_build_query([]);
        $total = Papan::count();
        $response = $this->getJson($url);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonPath('meta.pagination.total', $total);
               
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'attributes' => array_values(Papan::$dtksFieldMapping),
                ],
            ],
        ]);
    }
}
