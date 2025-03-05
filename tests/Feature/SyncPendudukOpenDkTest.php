<?php

namespace Tests\Feature;

use App\Models\Config;
use App\Models\Penduduk;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SyncPendudukOpenDkTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $user = User::inRandomOrder()->first();

        // Generate token dengan ability yang diperlukan
        $token = $user->createToken('auth_token', ['synchronize-opendk-create'])->plainTextToken;

        // Set token ke dalam Sanctum agar bisa digunakan dalam testing
        Sanctum::actingAs($user, ['synchronize-opendk-create']);
    }
    /**
     * A basic feature test example.
     */
    public function test_get_data_penduduk_by_kode_kecamatan()
    {
        $kodeKecamatan = Config::inRandomOrder()->first()->kode_kecamatan;

        $totalKecamatan = Penduduk::whereRelation('config', 'kode_kecamatan', $kodeKecamatan)->count();

        $url = '/api/v1/opendk/sync-penduduk-opendk?'.http_build_query([
            'filter[kode_kecamatan]' => $kodeKecamatan,
        ]);

        $response = $this->getJson($url);

        // Pastikan responsnya berhasil
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonPath('meta.pagination.total', $totalKecamatan);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'attributes' => [
                        'config_id',
                        'nama',
                        'nik',
                    ],
                ],
            ],
        ]);
    }
}
