<?php

namespace Tests\Feature;

use App\Models\Config;
use App\Models\LaporanSinkronisasi;
use App\Models\User;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LaporanPendudukTest extends TestCase
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
    public function test_get_laporan_penduduk_by_kode_kecamatan(): void
    {
        $kodeKecamatan = Config::inRandomOrder()->first()->kode_kecamatan;

        $url = '/api/v1/opendk/laporan-penduduk?'.http_build_query([
            'filter[kode_kecamatan]' => $kodeKecamatan,
        ]);

        $response = $this->getJson($url);

        // Pastikan responsnya berhasil
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'attributes' => [
                        'judul',
                        'tahun',
                        'bulan',
                        'nama_file',
                        'kirim',
                    ],
                ],
            ],
        ]);
    }
}
