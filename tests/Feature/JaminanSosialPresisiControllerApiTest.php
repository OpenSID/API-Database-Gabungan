<?php

namespace Tests\Feature;

use App\Models\DataPresisiJaminanSosial;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Schema;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class JaminanSosialPresisiControllerApiTest extends TestCase
{
    private array $attributes = [
        "uuid",
        "config_id",
        "data_presisi_tahun_id",
        "rtm_id",
        "keluarga_id",
        "anggota_id",
        "jns_bantuan",
        "jns_gangguan_mental",
        "terapi_gangguan_mental"
    ];
    protected function setUp(): void
    {
        parent::setUp();
        if(!Schema::connection('openkab')->hasTable('data_presisi_jaminan_sosial')) {
            $this->markTestSkipped('Tabel data_presisi_jaminan_sosial belum ada');
        }
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_data_jaminan_sosial()
    {
        $user = User::inRandomOrder()->first();
        Sanctum::actingAs($user);
        $url = '/api/v1/data-presisi/jaminan-sosial?'.http_build_query([
            'include' => 'keluarga,penduduk',

        ]);
        $total = DataPresisiJaminanSosial::tahunAktif()->count();
        $response = $this->getJson($url);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonPath('meta.pagination.total', $total);
        //print_r($response->json());
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'attributes' => $this->attributes,
                ],
            ],
        ]);
    }

    public function test_get_data_jaminan_sosial_kepala_rtm()
    {
        $user = User::inRandomOrder()->first();
        Sanctum::actingAs($user);
        $url = '/api/v1/data-presisi/jaminan-sosial?'.http_build_query([
            'filter[kepala_rtm]' => true,
        ]);
        $total = DataPresisiJaminanSosial::tahunAktif()->kepalaRtm()->count();
        $response = $this->getJson($url);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonPath('meta.pagination.total', $total);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'attributes' => $this->attributes,
                ],
            ],
        ]);
    }

    public function test_get_data_jaminan_sosial_kepala_rtm_kecamatan()
    {
        $user = User::inRandomOrder()->first();
        Sanctum::actingAs($user);
        $kodeKecamatan = DataPresisiJaminanSosial::distinct('config_id')->inRandomOrder()->first()->config->kode_kecamatan;
        $url = '/api/v1/data-presisi/jaminan-sosial?'.http_build_query([
            'filter[kepala_rtm]' => true,
            'filter[kode_kecamatan]' => $kodeKecamatan,
        ]);
        $total = DataPresisiJaminanSosial::tahunAktif()->kepalaRtm()->whereHas('config', function ($query) use ($kodeKecamatan) {
            $query->where('kode_kecamatan', $kodeKecamatan);
        })->count();
        $response = $this->getJson($url);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonPath('meta.pagination.total', $total);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'attributes' => $this->attributes,
                ],
            ],
        ]);
    }

    public function test_get_data_jaminan_sosial_kepala_rtm_desa()
    {
        $user = User::inRandomOrder()->first();
        Sanctum::actingAs($user);
        $kodeDesa = DataPresisiJaminanSosial::distinct('config_id')->inRandomOrder()->first()->config->kode_desa;
        $url = '/api/v1/data-presisi/jaminan-sosial?'.http_build_query([
            'filter[kepala_rtm]' => true,
            'filter[kode_desa]' => $kodeDesa,
        ]);
        $total = DataPresisiJaminanSosial::tahunAktif()->kepalaRtm()->whereHas('config', function ($query) use ($kodeDesa) {
            $query->where('kode_desa', $kodeDesa);
        })->count();
        $response = $this->getJson($url);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonPath('meta.pagination.total', $total);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'attributes' => $this->attributes,
                ],
            ],
        ]);
    }
}
