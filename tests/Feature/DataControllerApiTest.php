<?php

namespace Tests\Feature;

use App\Models\Config;
use App\Models\KeluargaDDK;
use App\Models\Penduduk;
use App\Models\User;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DataControllerApiTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_data_kesehatan()
    {
        $user = User::inRandomOrder()->first();
        Sanctum::actingAs($user);
        $kodeKecamatan = Config::inRandomOrder()->first()->kode_kecamatan;
        $url = '/api/v1/data/kesehatan?'.http_build_query([
            'kode_kecamatan' => $kodeKecamatan,
        ]);
        $total = Penduduk::whereHas('config', static fn($q) => $q->where('kode_kecamatan', $kodeKecamatan))->count();        
        $response = $this->getJson($url);        
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonPath('meta.pagination.total', $total);        
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'attributes' => [                        
                        'nama',
                        'nik',
                        'golongan_darah',
                        'cacat',
                        'sakit_menahun',
                        'kb',
                        'hamil',
                        'asuransi',
                        'no_asuransi',
                        'status_gizi',
                    ],
                ],
            ],
        ]);
    }

    public function test_get_data_jaminan_sosial()
    {
        $user = User::inRandomOrder()->first();
        Sanctum::actingAs($user);
        $kodeKecamatan = Config::inRandomOrder()->first()->kode_kecamatan;
        $url = '/api/v1/data/jaminan-sosial?'.http_build_query([
            'kode_kecamatan' => $kodeKecamatan,
        ]);
        $total = Penduduk::whereHas('config', static fn($q) => $q->where('kode_kecamatan', $kodeKecamatan))->count();        
        $response = $this->getJson($url);        
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonPath('meta.pagination.total', $total);        
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'attributes' => [                        
                        'nama',
                        'nik',
                        'dtks',
                        'asuransi',
                        'no_asuransi',
                        'kd_ikut_prakerja',
                        'kd_kur',
                        'kd_umi',
                        'bpjs_ketenagakerjaan',
                        'cacat',
                    ],
                ],
            ],
        ]);
    }

    public function test_get_data_potensi_kelembagaan()
    {
        $user = User::inRandomOrder()->first();
        Sanctum::actingAs($user);
        $kodeKecamatan = Config::inRandomOrder()->first()->kode_kecamatan;
        $url = '/api/v1/data/penduduk-potensi-kelembagaan?'.http_build_query([
            'kode_kecamatan' => $kodeKecamatan,
        ]);
        $total = Penduduk::whereHas('config', static fn($q) => $q->where('kode_kecamatan', $kodeKecamatan))->count();        
        $response = $this->getJson($url);        
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonPath('meta.pagination.total', $total);        
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'attributes' => [                        
                        'nama',
                        'nik',
                        'agama',
                        'suku',
                        'lembaga_adat',
                        'prasarana_peribadatan',              
                        'jumlah',
                    ],
                ],
            ],
        ]);
    }
}
