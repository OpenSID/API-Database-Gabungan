<?php

namespace Tests\Feature;

use App\Models\DataPresisiAdat;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Schema;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AdatPresisiControllerApiTest extends TestCase
{
    private array $attributes = [
        "uuid",
        "config_id",
        "data_presisi_tahun_id",
        "rtm_id",
        "keluarga_id",
        "anggota_id",
        "kelompok_id",
        "status_keanggotaan",
        "frekwensi_mengikuti_kegiatan_setahun"
    ];
    protected function setUp(): void
    {
        parent::setUp();
        if(!Schema::connection('openkab')->hasTable('data_presisi_aktivitas_adat')) {
            $this->markTestSkipped('Tabel data_presisi_aktivitas_adat belum ada');
        }
    }
    /**
     * A basic feature test example.
     *
     * @return void
     * contoh response
     *  Array
(
    [data] => Array
        (
            [0] => Array
                (
                    [type] => agama
                    [id] => 000b5d51-8477-4d54-8c7d-5ce4ab760454
                    [attributes] => Array
                        (
                            [uuid] => 000b5d51-8477-4d54-8c7d-5ce4ab760454
                            [config_id] => 17
                            [data_presisi_tahun_id] => e6e3145f-7dca-4261-8fe5-54fba737764b
                            [rtm_id] => 2581
                            [keluarga_id] => 3640
                            [anggota_id] => 15232
                            [kelompok_id] =>
                            [status_keanggotaan] =>
                            [frekwensi_mengikuti_kegiatan_setahun] =>
                            [created_at] => 2025-04-16T13:52:16.000000Z
                            [created_by] =>
                            [updated_at] => 2025-04-16T13:52:16.000000Z
                            [updated_by] =>
                            [anggota_count] => 4
                            [frekwensi] =>
                        )

                    [relationships] => Array
                        (
                            [keluarga] => Array
                                (
                                    [data] => Array
                                        (
                                            [type] => keluarga
                                            [id] => 3640
                                        )

                                )

                            [rtm] => Array
                                (
                                    [data] => Array
                                        (
                                            [type] => rtm
                                            [id] => 2581
                                        )

                                )

                            [penduduk] => Array
                                (
                                    [data] => Array
                                        (
                                            [type] => penduduk
                                            [id] => 15232
                                        )

                                )

                            [listAnggota] => Array
                                (
                                    [data] => Array
                                        (
                                            [0] => Array
                                                (
                                                    [type] => anggota
                                                    [id] => 000b5d51-8477-4d54-8c7d-5ce4ab760454
                                                )

                                            [1] => Array
                                                (
                                                    [type] => anggota
                                                    [id] => 920a1af4-5cb0-437b-a470-8b5f66d1f627
                                                )

                                            [2] => Array
                                                (
                                                    [type] => anggota
                                                    [id] => aa96a2b3-c1ca-44b3-9640-45b18e6b78b7
                                                )

                                            [3] => Array
                                                (
                                                    [type] => anggota
                                                    [id] => eadddf46-4abc-4ae5-b195-a0ef2c2c4494
                                                )

                                        )

                                )

                        )

                )

            [1] => Array
                (
                    [type] => agama
                    [id] => 002b06ba-0f2d-49c4-ba65-65d5521aed11
                    [attributes] => Array
                        (
                            [uuid] => 002b06ba-0f2d-49c4-ba65-65d5521aed11
                            [config_id] => 17
                            [data_presisi_tahun_id] => e6e3145f-7dca-4261-8fe5-54fba737764b
                            [rtm_id] => 2593
                            [keluarga_id] => 3650
                            [anggota_id] => 13178
                            [kelompok_id] =>
                            [status_keanggotaan] =>
                            [frekwensi_mengikuti_kegiatan_setahun] =>
                            [created_at] => 2025-04-16T13:52:16.000000Z
                            [created_by] =>
                            [updated_at] => 2025-04-16T13:52:16.000000Z
                            [updated_by] =>
                            [anggota_count] => 3
                            [frekwensi] =>
                        )

                    [relationships] => Array
                        (
                            [keluarga] => Array
                                (
                                    [data] => Array
                                        (
                                            [type] => keluarga
                                            [id] => 3650
                                        )

                                )

                            [rtm] => Array
                                (
                                    [data] => Array
                                        (
                                            [type] => rtm
                                            [id] => 2593
                                        )

                                )

                            [penduduk] => Array
                                (
                                    [data] => Array
                                        (
                                            [type] => penduduk
                                            [id] => 13178
                                        )

                                )

                            [listAnggota] => Array
                                (
                                    [data] => Array
                                        (
                                            [0] => Array
                                                (
                                                    [type] => anggota
                                                    [id] => 002b06ba-0f2d-49c4-ba65-65d5521aed11
                                                )

                                            [1] => Array
                                                (
                                                    [type] => anggota
                                                    [id] => 43e2e923-e94f-46fc-839b-4fd24435193a
                                                )

                                            [2] => Array
                                                (
                                                    [type] => anggota
                                                    [id] => b2b1f7ad-5479-42bb-93d3-c94ba50cdec2
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
                    [id] => 3640
                    [attributes] => Array
                        (
                            [no_kk] => 5102102711180004
                        )

                )

            [1] => Array
                (
                    [type] => rtm
                    [id] => 2581
                    [attributes] => Array
                        (
                            [nik_kepala] => 13142
                            [no_kk] => 5102102003020524
                            [nama_kepala] =>
                        )

                )

            [2] => Array
                (
                    [type] => penduduk
                    [id] => 15232
                    [attributes] => Array
                        (
                            [nik] => 5102106301220002
                            [nama] => NI KADEK KHAYRA PRAMESTI PUTRI ATMAJA
                        )

                )

            [3] => Array
                (
                    [type] => anggota
                    [id] => 000b5d51-8477-4d54-8c7d-5ce4ab760454
                    [attributes] => Array
                        (
                            [uuid] => 000b5d51-8477-4d54-8c7d-5ce4ab760454
                            [config_id] => 17
                            [data_presisi_tahun_id] => e6e3145f-7dca-4261-8fe5-54fba737764b
                            [rtm_id] => 2581
                            [keluarga_id] => 3640
                            [anggota_id] => 15232
                            [kelompok_id] =>
                            [status_keanggotaan] =>
                            [frekwensi_mengikuti_kegiatan_setahun] =>
                            [created_at] => 2025-04-16T13:52:16.000000Z
                            [created_by] =>
                            [updated_at] => 2025-04-16T13:52:16.000000Z
                            [updated_by] =>
                            [nik] => 5102106301220002
                            [nama] => NI KADEK KHAYRA PRAMESTI PUTRI ATMAJA
                            [frekwensi] =>
                        )

                )

            [4] => Array
                (
                    [type] => anggota
                    [id] => 920a1af4-5cb0-437b-a470-8b5f66d1f627
                    [attributes] => Array
                        (
                            [uuid] => 920a1af4-5cb0-437b-a470-8b5f66d1f627
                            [config_id] => 17
                            [data_presisi_tahun_id] => e6e3145f-7dca-4261-8fe5-54fba737764b
                            [rtm_id] => 2581
                            [keluarga_id] => 3640
                            [anggota_id] => 15104
                            [kelompok_id] =>
                            [status_keanggotaan] =>
                            [frekwensi_mengikuti_kegiatan_setahun] =>
                            [created_at] => 2025-04-16T13:52:16.000000Z
                            [created_by] =>
                            [updated_at] => 2025-04-16T13:52:16.000000Z
                            [updated_by] =>
                            [nik] => 5102100602210001
                            [nama] => I WAYAN BAGUS RADHEVA PUTRA ATMAJA
                            [frekwensi] =>
                        )

                )

            [5] => Array
                (
                    [type] => anggota
                    [id] => aa96a2b3-c1ca-44b3-9640-45b18e6b78b7
                    [attributes] => Array
                        (
                            [uuid] => aa96a2b3-c1ca-44b3-9640-45b18e6b78b7
                            [config_id] => 17
                            [data_presisi_tahun_id] => e6e3145f-7dca-4261-8fe5-54fba737764b
                            [rtm_id] => 2581
                            [keluarga_id] => 3640
                            [anggota_id] => 13142
                            [kelompok_id] =>
                            [status_keanggotaan] =>
                            [frekwensi_mengikuti_kegiatan_setahun] =>
                            [created_at] => 2025-04-16T13:52:16.000000Z
                            [created_by] =>
                            [updated_at] => 2025-04-16T13:52:16.000000Z
                            [updated_by] =>
                            [nik] => 5102100802910001
                            [nama] => PUTU ARTHA WIRA ATMAJA
                            [frekwensi] =>
                        )

                )

            [6] => Array
                (
                    [type] => anggota
                    [id] => eadddf46-4abc-4ae5-b195-a0ef2c2c4494
                    [attributes] => Array
                        (
                            [uuid] => eadddf46-4abc-4ae5-b195-a0ef2c2c4494
                            [config_id] => 17
                            [data_presisi_tahun_id] => e6e3145f-7dca-4261-8fe5-54fba737764b
                            [rtm_id] => 2581
                            [keluarga_id] => 3640
                            [anggota_id] => 13143
                            [kelompok_id] =>
                            [status_keanggotaan] =>
                            [frekwensi_mengikuti_kegiatan_setahun] =>
                            [created_at] => 2025-04-16T13:52:16.000000Z
                            [created_by] =>
                            [updated_at] => 2025-04-16T13:52:16.000000Z
                            [updated_by] =>
                            [nik] => 5102104407950001
                            [nama] => NI WAYAN YULIANTI
                            [frekwensi] =>
                        )

                )

            [7] => Array
                (
                    [type] => keluarga
                    [id] => 3650
                    [attributes] => Array
                        (
                            [no_kk] => 5102102912160002
                        )

                )

            [8] => Array
                (
                    [type] => rtm
                    [id] => 2593
                    [attributes] => Array
                        (
                            [nik_kepala] => 13177
                            [no_kk] => 5102102003020536
                            [nama_kepala] =>
                        )

                )

            [9] => Array
                (
                    [type] => penduduk
                    [id] => 13178
                    [attributes] => Array
                        (
                            [nik] => 5102106409890001
                            [nama] => NI KAYAN SUGIARTINI
                        )

                )

            [10] => Array
                (
                    [type] => anggota
                    [id] => 002b06ba-0f2d-49c4-ba65-65d5521aed11
                    [attributes] => Array
                        (
                            [uuid] => 002b06ba-0f2d-49c4-ba65-65d5521aed11
                            [config_id] => 17
                            [data_presisi_tahun_id] => e6e3145f-7dca-4261-8fe5-54fba737764b
                            [rtm_id] => 2593
                            [keluarga_id] => 3650
                            [anggota_id] => 13178
                            [kelompok_id] =>
                            [status_keanggotaan] =>
                            [frekwensi_mengikuti_kegiatan_setahun] =>
                            [created_at] => 2025-04-16T13:52:16.000000Z
                            [created_by] =>
                            [updated_at] => 2025-04-16T13:52:16.000000Z
                            [updated_by] =>
                            [nik] => 5102106409890001
                            [nama] => NI KAYAN SUGIARTINI
                            [frekwensi] =>
                        )

                )

            [11] => Array
                (
                    [type] => anggota
                    [id] => 43e2e923-e94f-46fc-839b-4fd24435193a
                    [attributes] => Array
                        (
                            [uuid] => 43e2e923-e94f-46fc-839b-4fd24435193a
                            [config_id] => 17
                            [data_presisi_tahun_id] => e6e3145f-7dca-4261-8fe5-54fba737764b
                            [rtm_id] => 2593
                            [keluarga_id] => 3650
                            [anggota_id] => 13179
                            [kelompok_id] =>
                            [status_keanggotaan] =>
                            [frekwensi_mengikuti_kegiatan_setahun] =>
                            [created_at] => 2025-04-16T13:52:16.000000Z
                            [created_by] =>
                            [updated_at] => 2025-04-16T13:52:16.000000Z
                            [updated_by] =>
                            [nik] => 5102101105150002
                            [nama] => I PUTU HEGI ANANTA ARIENDRA
                            [frekwensi] =>
                        )

                )

            [12] => Array
                (
                    [type] => anggota
                    [id] => b2b1f7ad-5479-42bb-93d3-c94ba50cdec2
                    [attributes] => Array
                        (
                            [uuid] => b2b1f7ad-5479-42bb-93d3-c94ba50cdec2
                            [config_id] => 17
                            [data_presisi_tahun_id] => e6e3145f-7dca-4261-8fe5-54fba737764b
                            [rtm_id] => 2593
                            [keluarga_id] => 3650
                            [anggota_id] => 13177
                            [kelompok_id] =>
                            [status_keanggotaan] =>
                            [frekwensi_mengikuti_kegiatan_setahun] =>
                            [created_at] => 2025-04-16T13:52:16.000000Z
                            [created_by] =>
                            [updated_at] => 2025-04-16T13:52:16.000000Z
                            [updated_by] =>
                            [nik] => 5102102303830003
                            [nama] => I PUTU HERI KENCANA PUTRA
                            [frekwensi] =>
                        )

                )

        )

    [meta] => Array
        (
            [pagination] => Array
                (
                    [total] => 3523
                    [count] => 2
                    [per_page] => 2
                    [current_page] => 1
                    [total_pages] => 1762
                )

        )

    [links] => Array
        (
            [self] => http://localhost/api/v1/data-presisi/adat?include=keluarga%2Cpenduduk%2Canggota%2Crtm%2ClistAnggota&page%5Bsize%5D=2&page%5Bnumber%5D=1
            [first] => http://localhost/api/v1/data-presisi/adat?include=keluarga%2Cpenduduk%2Canggota%2Crtm%2ClistAnggota&page%5Bsize%5D=2&page%5Bnumber%5D=1
            [next] => http://localhost/api/v1/data-presisi/adat?include=keluarga%2Cpenduduk%2Canggota%2Crtm%2ClistAnggota&page%5Bsize%5D=2&page%5Bnumber%5D=2
            [last] => http://localhost/api/v1/data-presisi/adat?include=keluarga%2Cpenduduk%2Canggota%2Crtm%2ClistAnggota&page%5Bsize%5D=2&page%5Bnumber%5D=1762
        )

)
     */
    public function test_get_data_aktivitas_adat()
    {
        $user = User::inRandomOrder()->first();
        Sanctum::actingAs($user);
        $url = '/api/v1/data-presisi/adat?'.http_build_query([
            'include' => 'keluarga,penduduk,anggota,rtm,listAnggota',
            // 'page[size]' => 2,
        ]);
        $total = DataPresisiAdat::tahunAktif()->count();
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

    public function test_get_data_aktivitas_adat_kepala_rtm()
    {
        $user = User::inRandomOrder()->first();
        Sanctum::actingAs($user);
        $url = '/api/v1/data-presisi/adat?'.http_build_query([
            'filter[kepala_rtm]' => true,
        ]);
        $total = DataPresisiAdat::tahunAktif()->kepalaRtm()->count();
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

    public function test_get_data_aktivitas_adat_kepala_rtm_kecamatan()
    {
        $user = User::inRandomOrder()->first();
        Sanctum::actingAs($user);
        if(!DataPresisiAdat::exists()) {
            $this->markTestSkipped('Tidak ada data presisi adat');
        }
        $kodeKecamatan = DataPresisiAdat::distinct('config_id')->inRandomOrder()->first()->config->kode_kecamatan;
        $url = '/api/v1/data-presisi/adat?'.http_build_query([
            'filter[kepala_rtm]' => true,
            'filter[kode_kecamatan]' => $kodeKecamatan,
        ]);
        $total = DataPresisiAdat::tahunAktif()->kepalaRtm()->whereHas('config', function ($query) use ($kodeKecamatan) {
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

    public function test_get_data_aktivitas_adat_kepala_rtm_desa()
    {
        $user = User::inRandomOrder()->first();
        Sanctum::actingAs($user);
        if(!DataPresisiAdat::exists()) {
            $this->markTestSkipped('Tidak ada data presisi adat');
        }
        $kodeDesa = DataPresisiAdat::distinct('config_id')->inRandomOrder()->first()->config->kode_desa;
        $url = '/api/v1/data-presisi/adat?'.http_build_query([
            'filter[kepala_rtm]' => true,
            'filter[kode_desa]' => $kodeDesa,
        ]);
        $total = DataPresisiAdat::tahunAktif()->kepalaRtm()->whereHas('config', function ($query) use ($kodeDesa) {
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
