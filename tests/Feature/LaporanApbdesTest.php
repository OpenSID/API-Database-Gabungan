<?php

namespace Tests\Feature;

use App\Models\Config;
use App\Models\LaporanSinkronisasi;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Response;
use Tests\TestCase;

class LaporanApbdesTest extends TestCase
{
    use WithoutMiddleware;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_laporan_apbdes_by_kode_kecamatan()
    {
        $kodeKecamatan = Config::inRandomOrder()->first()->kode_kecamatan;

        $total = LaporanSinkronisasi::whereRelation('desa', 'kode_kecamatan', $kodeKecamatan)->apbdes()->get()->count();

        $url = '/api/v1/keuangan/laporan_apbdes?'.http_build_query([
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
                        'tipe',
                        'judul',
                        'tahun',
                        'semester',
                        'nama_file',
                        'created_at_local',
                        'url_file',
                        'kirim'
                    ],
                ],
            ],
        ]);
    }
}
