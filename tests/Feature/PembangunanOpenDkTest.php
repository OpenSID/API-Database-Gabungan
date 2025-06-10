<?php

namespace Tests\Feature;

use App\Models\Config;
use App\Models\Pembangunan;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Response;
use Tests\TestCase;

class PembangunanOpenDkTest extends TestCase
{
    use WithoutMiddleware;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_data_pembangunan_by_kode_kecamatan()
    {
        $kodeKecamatan = Config::inRandomOrder()->first()->kode_kecamatan;

        $totalKecamatan = Pembangunan::whereRelation('config', 'kode_kecamatan', $kodeKecamatan)->count();

        $url = '/api/v1/opendk/pembangunan?'.http_build_query([
            'filter[kode_kecamatan]' => $kodeKecamatan,
        ]);

        // Kirim permintaan sync penduduk dengan header Authorization
        $response = $this->getJson($url, [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ]);

        // Pastikan responsnya berhasil
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonPath('meta.pagination.total', $totalKecamatan);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'attributes' => [
                        'judul',
                        'sumber_dana',
                    ],
                ],
            ],
        ]);
    }
}
