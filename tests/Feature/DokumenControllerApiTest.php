<?php

namespace Tests\Feature;

use App\Models\Penduduk;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DokumenControllerApiTest extends TestCase
{
    use WithoutMiddleware;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_data_dokumen()
    {
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
