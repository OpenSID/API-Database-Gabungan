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
     * contoh response
     *  🟨 Array
(
    [data] => Array
        (
            [0] => Array
                (
                    [type] => data_presisi_jaminan_sosial
                    [id] => 001d8bf1-d36d-414a-9808-6ea8c13e1ce9
                    [attributes] => Array
                        (
                            [uuid] => 001d8bf1-d36d-414a-9808-6ea8c13e1ce9
                            [config_id] => 17
                            [data_presisi_tahun_id] => e6e3145f-7dca-4261-8fe5-54fba737764b
                            [rtm_id] => 2158
                            [keluarga_id] => 3288
                            [anggota_id] => 11799
                            [jns_bantuan] =>
                            [jns_gangguan_mental] =>
                            [terapi_gangguan_mental] =>
                            [created_at] => 2025-04-15T15:05:46.000000Z
                            [created_by] =>
                            [updated_at] => 2025-04-15T15:05:46.000000Z
                            [updated_by] =>
                            [anggota_count] => 2
                        )

                    [relationships] => Array
                        (
                            [keluarga] => Array
                                (
                                    [data] => Array
                                        (
                                            [type] => keluarga
                                            [id] => 3288
                                        )

                                )

                            [rtm] => Array
                                (
                                    [data] => Array
                                        (
                                            [type] => rtm
                                            [id] => 2158
                                        )

                                )

                            [penduduk] => Array
                                (
                                    [data] => Array
                                        (
                                            [type] => penduduk
                                            [id] => 11799
                                        )

                                )

                            [listAnggota] => Array
                                (
                                    [data] => Array
                                        (
                                            [0] => Array
                                                (
                                                    [type] => anggota
                                                    [id] => 001d8bf1-d36d-414a-9808-6ea8c13e1ce9
                                                )

                                            [1] => Array
                                                (
                                                    [type] => anggota
                                                    [id] => 4b7cb35c-722a-4552-8465-72255810dcff
                                                )

                                        )

                                )

                        )

                )

            [1] => Array
                (
                    [type] => data_presisi_jaminan_sosial
                    [id] => 001eac46-9886-4f9d-9af8-3b823f461656
                    [attributes] => Array
                        (
                            [uuid] => 001eac46-9886-4f9d-9af8-3b823f461656
                            [config_id] => 17
                            [data_presisi_tahun_id] => e6e3145f-7dca-4261-8fe5-54fba737764b
                            [rtm_id] => 2485
                            [keluarga_id] => 3572
                            [anggota_id] => 12890
                            [jns_bantuan] =>
                            [jns_gangguan_mental] =>
                            [terapi_gangguan_mental] =>
                            [created_at] => 2025-04-15T15:05:51.000000Z
                            [created_by] =>
                            [updated_at] => 2025-04-15T15:05:51.000000Z
                            [updated_by] =>
                            [anggota_count] => 3
                        )

                    [relationships] => Array
                        (
                            [keluarga] => Array
                                (
                                    [data] => Array
                                        (
                                            [type] => keluarga
                                            [id] => 3572
                                        )

                                )

                            [rtm] => Array
                                (
                                    [data] => Array
                                        (
                                            [type] => rtm
                                            [id] => 2485
                                        )

                                )

                            [penduduk] => Array
                                (
                                    [data] => Array
                                        (
                                            [type] => penduduk
                                            [id] => 12890
                                        )

                                )

                            [listAnggota] => Array
                                (
                                    [data] => Array
                                        (
                                            [0] => Array
                                                (
                                                    [type] => anggota
                                                    [id] => 001eac46-9886-4f9d-9af8-3b823f461656
                                                )

                                            [1] => Array
                                                (
                                                    [type] => anggota
                                                    [id] => 07e39a6d-6d64-4d04-95e8-2e78b7d7b4c5
                                                )

                                            [2] => Array
                                                (
                                                    [type] => anggota
                                                    [id] => f29123ae-a2d9-40b1-8332-d97d0208ac9a
                                                )

                                        )

                                )

                        )

                )

            [2] => Array
                (
                    [type] => data_presisi_jaminan_sosial
                    [id] => 0032e46d-8ba8-4866-a44b-c001227142ee
                    [attributes] => Array
                        (
                            [uuid] => 0032e46d-8ba8-4866-a44b-c001227142ee
                            [config_id] => 17
                            [data_presisi_tahun_id] => e6e3145f-7dca-4261-8fe5-54fba737764b
                            [rtm_id] => 2220
                            [keluarga_id] => 4203
                            [anggota_id] => 12171
                            [jns_bantuan] =>
                            [jns_gangguan_mental] =>
                            [terapi_gangguan_mental] =>
                            [created_at] => 2025-04-15T15:05:46.000000Z
                            [created_by] =>
                            [updated_at] => 2025-04-15T15:05:46.000000Z
                            [updated_by] =>
                            [anggota_count] => 3
                        )

                    [relationships] => Array
                        (
                            [keluarga] => Array
                                (
                                    [data] => Array
                                        (
                                            [type] => keluarga
                                            [id] => 4203
                                        )

                                )

                            [rtm] => Array
                                (
                                    [data] => Array
                                        (
                                            [type] => rtm
                                            [id] => 2220
                                        )

                                )

                            [penduduk] => Array
                                (
                                    [data] => Array
                                        (
                                            [type] => penduduk
                                            [id] => 12171
                                        )

                                )

                            [listAnggota] => Array
                                (
                                    [data] => Array
                                        (
                                            [0] => Array
                                                (
                                                    [type] => anggota
                                                    [id] => 0032e46d-8ba8-4866-a44b-c001227142ee
                                                )

                                            [1] => Array
                                                (
                                                    [type] => anggota
                                                    [id] => 2c450565-a236-4511-8e76-78c7a64ce37c
                                                )

                                            [2] => Array
                                                (
                                                    [type] => anggota
                                                    [id] => 8828fa65-1aa3-42ce-8364-49152ddd3491
                                                )

                                        )

                                )

                        )

                )

        )

    [included] => Array
        (
            [0] => Array
                (
                    [type] => keluarga
                    [id] => 3288
                    [attributes] => Array
                        (
                            [no_kk] => 5102100701110029
                        )

                )

            [1] => Array
                (
                    [type] => rtm
                    [id] => 2158
                    [attributes] => Array
                        (
                            [nik_kepala] => 11799
                            [no_kk] => 5102102003020101
                            [nama_kepala] => I KETUT SERIKAT
                        )

                )

            [2] => Array
                (
                    [type] => penduduk
                    [id] => 11799
                    [attributes] => Array
                        (
                            [nik] => 5102100107450005
                            [nama] => I KETUT SERIKAT
                        )

                )

            [3] => Array
                (
                    [type] => anggota
                    [id] => 001d8bf1-d36d-414a-9808-6ea8c13e1ce9
                    [attributes] => Array
                        (
                            [uuid] => 001d8bf1-d36d-414a-9808-6ea8c13e1ce9
                            [config_id] => 17
                            [data_presisi_tahun_id] => e6e3145f-7dca-4261-8fe5-54fba737764b
                            [rtm_id] => 2158
                            [keluarga_id] => 3288
                            [anggota_id] => 11799
                            [jns_bantuan] =>
                            [jns_gangguan_mental] =>
                            [terapi_gangguan_mental] =>
                            [created_at] => 2025-04-15T15:05:46.000000Z
                            [created_by] =>
                            [updated_at] => 2025-04-15T15:05:46.000000Z
                            [updated_by] =>
                            [nik] => 5102100107450005
                            [nama] => I KETUT SERIKAT
                        )

                )

            [4] => Array
                (
                    [type] => anggota
                    [id] => 4b7cb35c-722a-4552-8465-72255810dcff
                    [attributes] => Array
                        (
                            [uuid] => 4b7cb35c-722a-4552-8465-72255810dcff
                            [config_id] => 17
                            [data_presisi_tahun_id] => e6e3145f-7dca-4261-8fe5-54fba737764b
                            [rtm_id] => 2158
                            [keluarga_id] => 3288
                            [anggota_id] => 11800
                            [jns_bantuan] =>
                            [jns_gangguan_mental] =>
                            [terapi_gangguan_mental] =>
                            [created_at] => 2025-04-15T15:05:46.000000Z
                            [created_by] =>
                            [updated_at] => 2025-04-15T15:05:46.000000Z
                            [updated_by] =>
                            [nik] => 5102104107550007
                            [nama] => NI KETUT SUTINI
                        )

                )

            [5] => Array
                (
                    [type] => keluarga
                    [id] => 3572
                    [attributes] => Array
                        (
                            [no_kk] => 5102101903082958
                        )

                )

            [6] => Array
                (
                    [type] => rtm
                    [id] => 2485
                    [attributes] => Array
                        (
                            [nik_kepala] => 12888
                            [no_kk] => 5102102003020428
                            [nama_kepala] => I NYOMAN SULANDRA
                        )

                )

            [7] => Array
                (
                    [type] => penduduk
                    [id] => 12890
                    [attributes] => Array
                        (
                            [nik] => 5102101608950002
                            [nama] => I KAYAN PUTRA SWANDARA
                        )

                )

            [8] => Array
                (
                    [type] => anggota
                    [id] => 001eac46-9886-4f9d-9af8-3b823f461656
                    [attributes] => Array
                        (
                            [uuid] => 001eac46-9886-4f9d-9af8-3b823f461656
                            [config_id] => 17
                            [data_presisi_tahun_id] => e6e3145f-7dca-4261-8fe5-54fba737764b
                            [rtm_id] => 2485
                            [keluarga_id] => 3572
                            [anggota_id] => 12890
                            [jns_bantuan] =>
                            [jns_gangguan_mental] =>
                            [terapi_gangguan_mental] =>
                            [created_at] => 2025-04-15T15:05:51.000000Z
                            [created_by] =>
                            [updated_at] => 2025-04-15T15:05:51.000000Z
                            [updated_by] =>
                            [nik] => 5102101608950002
                            [nama] => I KAYAN PUTRA SWANDARA
                        )

                )

            [9] => Array
                (
                    [type] => anggota
                    [id] => 07e39a6d-6d64-4d04-95e8-2e78b7d7b4c5
                    [attributes] => Array
                        (
                            [uuid] => 07e39a6d-6d64-4d04-95e8-2e78b7d7b4c5
                            [config_id] => 17
                            [data_presisi_tahun_id] => e6e3145f-7dca-4261-8fe5-54fba737764b
                            [rtm_id] => 2485
                            [keluarga_id] => 3572
                            [anggota_id] => 12889
                            [jns_bantuan] =>
                            [jns_gangguan_mental] =>
                            [terapi_gangguan_mental] =>
                            [created_at] => 2025-04-15T15:05:51.000000Z
                            [created_by] =>
                            [updated_at] => 2025-04-15T15:05:51.000000Z
                            [updated_by] =>
                            [nik] => 5102107112690038
                            [nama] => NI NYOMAN SUANDRI
                        )

                )

            [10] => Array
                (
                    [type] => anggota
                    [id] => f29123ae-a2d9-40b1-8332-d97d0208ac9a
                    [attributes] => Array
                        (
                            [uuid] => f29123ae-a2d9-40b1-8332-d97d0208ac9a
                            [config_id] => 17
                            [data_presisi_tahun_id] => e6e3145f-7dca-4261-8fe5-54fba737764b
                            [rtm_id] => 2485
                            [keluarga_id] => 3572
                            [anggota_id] => 12888
                            [jns_bantuan] =>
                            [jns_gangguan_mental] =>
                            [terapi_gangguan_mental] =>
                            [created_at] => 2025-04-15T15:05:51.000000Z
                            [created_by] =>
                            [updated_at] => 2025-04-15T15:05:51.000000Z
                            [updated_by] =>
                            [nik] => 5102103112680028
                            [nama] => I NYOMAN SULANDRA
                        )

                )

            [11] => Array
                (
                    [type] => keluarga
                    [id] => 4203
                    [attributes] => Array
                        (
                            [no_kk] => 5102101410210003
                        )

                )

            [12] => Array
                (
                    [type] => rtm
                    [id] => 2220
                    [attributes] => Array
                        (
                            [nik_kepala] => 12171
                            [no_kk] => 5102102003020163
                            [nama_kepala] => I NYOMAN SUNAYA
                        )

                )

            [13] => Array
                (
                    [type] => penduduk
                    [id] => 12171
                    [attributes] => Array
                        (
                            [nik] => 5102101408800001
                            [nama] => I NYOMAN SUNAYA
                        )

                )

            [14] => Array
                (
                    [type] => anggota
                    [id] => 0032e46d-8ba8-4866-a44b-c001227142ee
                    [attributes] => Array
                        (
                            [uuid] => 0032e46d-8ba8-4866-a44b-c001227142ee
                            [config_id] => 17
                            [data_presisi_tahun_id] => e6e3145f-7dca-4261-8fe5-54fba737764b
                            [rtm_id] => 2220
                            [keluarga_id] => 4203
                            [anggota_id] => 12171
                            [jns_bantuan] =>
                            [jns_gangguan_mental] =>
                            [terapi_gangguan_mental] =>
                            [created_at] => 2025-04-15T15:05:46.000000Z
                            [created_by] =>
                            [updated_at] => 2025-04-15T15:05:46.000000Z
                            [updated_by] =>
                            [nik] => 5102101408800001
                            [nama] => I NYOMAN SUNAYA
                        )

                )

            [15] => Array
                (
                    [type] => anggota
                    [id] => 2c450565-a236-4511-8e76-78c7a64ce37c
                    [attributes] => Array
                        (
                            [uuid] => 2c450565-a236-4511-8e76-78c7a64ce37c
                            [config_id] => 17
                            [data_presisi_tahun_id] => e6e3145f-7dca-4261-8fe5-54fba737764b
                            [rtm_id] => 2220
                            [keluarga_id] => 4203
                            [anggota_id] => 12170
                            [jns_bantuan] =>
                            [jns_gangguan_mental] =>
                            [terapi_gangguan_mental] =>
                            [created_at] => 2025-04-15T15:05:46.000000Z
                            [created_by] =>
                            [updated_at] => 2025-04-15T15:05:46.000000Z
                            [updated_by] =>
                            [nik] => 5102106612470001
                            [nama] => NI WAYAN RASIP
                        )

                )

            [16] => Array
                (
                    [type] => anggota
                    [id] => 8828fa65-1aa3-42ce-8364-49152ddd3491
                    [attributes] => Array
                        (
                            [uuid] => 8828fa65-1aa3-42ce-8364-49152ddd3491
                            [config_id] => 17
                            [data_presisi_tahun_id] => e6e3145f-7dca-4261-8fe5-54fba737764b
                            [rtm_id] => 2220
                            [keluarga_id] => 4203
                            [anggota_id] => 15127
                            [jns_bantuan] =>
                            [jns_gangguan_mental] =>
                            [terapi_gangguan_mental] =>
                            [created_at] => 2025-04-15T15:05:46.000000Z
                            [created_by] =>
                            [updated_at] => 2025-04-15T15:05:46.000000Z
                            [updated_by] =>
                            [nik] => 5108034703860003
                            [nama] => ANAK AGUNG RAKA TRISNA DEWI
                        )

                )

        )

    [meta] => Array
        (
            [pagination] => Array
                (
                    [total] => 3523
                    [count] => 3
                    [per_page] => 3
                    [current_page] => 1
                    [total_pages] => 1175
                )

        )

    [links] => Array
        (
            [self] => http://localhost/api/v1/data-presisi/jaminan-sosial?include=keluarga%2Cpenduduk%2Canggota%2Crtm%2ClistAnggota&page%5Bnumber%5D=1
            [first] => http://localhost/api/v1/data-presisi/jaminan-sosial?include=keluarga%2Cpenduduk%2Canggota%2Crtm%2ClistAnggota&page%5Bnumber%5D=1
            [next] => http://localhost/api/v1/data-presisi/jaminan-sosial?include=keluarga%2Cpenduduk%2Canggota%2Crtm%2ClistAnggota&page%5Bnumber%5D=2
            [last] => http://localhost/api/v1/data-presisi/jaminan-sosial?include=keluarga%2Cpenduduk%2Canggota%2Crtm%2ClistAnggota&page%5Bnumber%5D=1175
        )

)
     */
    public function test_get_data_jaminan_sosial()
    {
        $user = User::inRandomOrder()->first();
        Sanctum::actingAs($user);
        $url = '/api/v1/data-presisi/jaminan-sosial?'.http_build_query([
            'include' => 'keluarga,penduduk,anggota,rtm,listAnggota',
        ]);
        $total = DataPresisiJaminanSosial::tahunAktif()->count();
        $response = $this->getJson($url);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonPath('meta.pagination.total', $total);
        // print_r($response->json());
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
        if(!DataPresisiJaminanSosial::distinct('config_id')->exists()) {
            $this->markTestSkipped('Tidak ada data jaminan sosial yang tersedia');
        }
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
        if(!DataPresisiJaminanSosial::distinct('config_id')->exists()) {
            $this->markTestSkipped('Tidak ada data jaminan sosial yang tersedia');
        }
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
