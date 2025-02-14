<?php

namespace Tests\Feature;

use App\Models\Config;
use App\Models\KeluargaDDK;
use App\Models\Penduduk;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Schema;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DokumenControllerApiTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_data_dokumen()
    {
        $user = User::inRandomOrder()->first();
        Sanctum::actingAs($user);
        $penduduk = Penduduk::inRandomOrder()->whereHas('dokumenHidup')->first();
        $url = '/api/v1/dokumen?'.http_build_query([
            'id_pend' => $penduduk->id,
        ]);
        $response = $this->getJson($url);
        $response->assertStatus(Response::HTTP_OK);
        $columDokumen = Schema::getColumnListing('dokumen_hidup');
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'attributes' => $columDokumen,
                ],
            ],
        ]);
    }
}
