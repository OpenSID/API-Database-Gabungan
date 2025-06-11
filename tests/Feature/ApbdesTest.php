<?php

namespace Tests\Feature;

use App\Models\Config;
use App\Models\Keuangan;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Response;
use Tests\TestCase;

class ApbdesTest extends TestCase
{
    use WithoutMiddleware;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_apbdes_by_kode_kecamatan()
    {
        $kodeKecamatan = Config::inRandomOrder()->first()->kode_kecamatan;

        $total = Keuangan::whereHas('template', static fn($q) => $q->apbdes() )->whereRelation('desa', 'kode_kecamatan', $kodeKecamatan)->count();

        $url = '/api/v1/keuangan/apbdes?'.http_build_query([
            'filter[kode_kecamatan]' => $kodeKecamatan,
        ]);

        // Kirim permintaan sync penduduk dengan header Authorization
        $response = $this->getJson($url, [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ]);

        // Pastikan responsnya berhasil
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonPath('meta.pagination.total', $total);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'attributes' => [
                        'template_uuid',
                        'tahun',
                        'anggaran',
                        'realisasi',
                        'nama_desa',
                        'uraian',
                    ],
                ],
            ],
        ]);
    }
}
