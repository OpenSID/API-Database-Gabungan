<?php

namespace Tests\Feature;

use App\Models\DataPresisiAgama;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Schema;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AgamaPresisiControllerApiTest extends TestCase
{
    private array $attributes = [
        "uuid",
        "config_id",
        "data_presisi_tahun_id",
        "rtm_id",
        "keluarga_id",
        "anggota_id",
        "agama_id",
        "agama",
        "frekwensi_mengikuti_kegiatan_setahun"
    ];
    protected function setUp(): void
    {
        parent::setUp();
        if(!Schema::connection('openkab')->hasTable('data_presisi_aktivitas_agama')) {
            $this->markTestSkipped('Tabel data_presisi_aktivitas_agama belum ada');
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
                    [type] => agama
                    [id] => 002832bb-e2d2-4cf9-9c56-3d3d7c3e4800
                    [attributes] => Array
                        (
                            [uuid] => 002832bb-e2d2-4cf9-9c56-3d3d7c3e4800
                            [config_id] => 17
                            [data_presisi_tahun_id] => e6e3145f-7dca-4261-8fe5-54fba737764b
                            [rtm_id] => 2488
                            [keluarga_id] => 3575
                            [anggota_id] => 15256
                            [agama_id] => 4
                            [frekwensi_mengikuti_kegiatan_setahun] =>
                            [created_at] => 2025-04-16T10:50:01.000000Z
                            [created_by] =>
                            [updated_at] => 2025-04-16T10:50:01.000000Z
                            [updated_by] =>
                            [anggota_count] => 4
                            [agama] => HINDU
                        )

                    [relationships] => Array
                        (
                            [keluarga] => Array
                                (
                                    [data] => Array
                                        (
                                            [type] => keluarga
                                            [id] => 3575
                                        )

                                )

                            [rtm] => Array
                                (
                                    [data] => Array
                                        (
                                            [type] => rtm
                                            [id] => 2488
                                        )

                                )

                            [penduduk] => Array
                                (
                                    [data] => Array
                                        (
                                            [type] => penduduk
                                            [id] => 15256
                                        )

                                )

                            [listAnggota] => Array
                                (
                                    [data] => Array
                                        (
                                            [0] => Array
                                                (
                                                    [type] => anggota
                                                    [id] => 002832bb-e2d2-4cf9-9c56-3d3d7c3e4800
                                                )

                                            [1] => Array
                                                (
                                                    [type] => anggota
                                                    [id] => 11f6fee9-5c9a-41f9-b056-d56f81e200b0
                                                )

                                            [2] => Array
                                                (
                                                    [type] => anggota
                                                    [id] => 9ed20138-d8ab-4bff-8196-26d24213e5e1
                                                )

                                            [3] => Array
                                                (
                                                    [type] => anggota
                                                    [id] => da7576e0-5027-4df2-8ae6-968b184d7e1b
                                                )

                                        )

                                )

                        )

                )

            [1] => Array
                (
                    [type] => agama
                    [id] => 0030daf3-76bb-47fd-9eb0-f137320f9d96
                    [attributes] => Array
                        (
                            [uuid] => 0030daf3-76bb-47fd-9eb0-f137320f9d96
                            [config_id] => 17
                            [data_presisi_tahun_id] => e6e3145f-7dca-4261-8fe5-54fba737764b
                            [rtm_id] => 2258
                            [keluarga_id] => 4343
                            [anggota_id] => 12078
                            [agama_id] => 4
                            [frekwensi_mengikuti_kegiatan_setahun] =>
                            [created_at] => 2025-04-16T10:49:56.000000Z
                            [created_by] =>
                            [updated_at] => 2025-04-16T10:49:56.000000Z
                            [updated_by] =>
                            [anggota_count] => 3
                            [agama] => HINDU
                        )

                    [relationships] => Array
                        (
                            [keluarga] => Array
                                (
                                    [data] => Array
                                        (
                                            [type] => keluarga
                                            [id] => 4343
                                        )

                                )

                            [rtm] => Array
                                (
                                    [data] => Array
                                        (
                                            [type] => rtm
                                            [id] => 2258
                                        )

                                )

                            [penduduk] => Array
                                (
                                    [data] => Array
                                        (
                                            [type] => penduduk
                                            [id] => 12078
                                        )

                                )

                            [listAnggota] => Array
                                (
                                    [data] => Array
                                        (
                                            [0] => Array
                                                (
                                                    [type] => anggota
                                                    [id] => 0030daf3-76bb-47fd-9eb0-f137320f9d96
                                                )

                                            [1] => Array
                                                (
                                                    [type] => anggota
                                                    [id] => 1cfedc41-d1f3-4faa-abdc-34304a92bcf6
                                                )

                                            [2] => Array
                                                (
                                                    [type] => anggota
                                                    [id] => 82c6ec84-5b84-41f0-a549-8c963ddc73f7
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
                    [id] => 3575
                    [attributes] => Array
                        (
                            [no_kk] => 5102101903082961
                        )

                )

            [1] => Array
                (
                    [type] => rtm
                    [id] => 2488
                    [attributes] => Array
                        (
                            [nik_kepala] => 12901
                            [no_kk] => 5102102003020431
                            [nama_kepala] =>
                        )

                )

            [2] => Array
                (
                    [type] => penduduk
                    [id] => 15256
                    [attributes] => Array
                        (
                            [nik] => 5102100508000001
                            [nama] => I PUTU SATRIA WIBAWA
                        )

                )

            [3] => Array
                (
                    [type] => anggota
                    [id] => 002832bb-e2d2-4cf9-9c56-3d3d7c3e4800
                    [attributes] => Array
                        (
                            [uuid] => 002832bb-e2d2-4cf9-9c56-3d3d7c3e4800
                            [config_id] => 17
                            [data_presisi_tahun_id] => e6e3145f-7dca-4261-8fe5-54fba737764b
                            [rtm_id] => 2488
                            [keluarga_id] => 3575
                            [anggota_id] => 15256
                            [agama_id] => 4
                            [frekwensi_mengikuti_kegiatan_setahun] =>
                            [created_at] => 2025-04-16T10:50:01.000000Z
                            [created_by] =>
                            [updated_at] => 2025-04-16T10:50:01.000000Z
                            [updated_by] =>
                            [nik] => 5102100508000001
                            [nama] => I PUTU SATRIA WIBAWA
                            [agama] => HINDU
                        )

                )

            [4] => Array
                (
                    [type] => anggota
                    [id] => 11f6fee9-5c9a-41f9-b056-d56f81e200b0
                    [attributes] => Array
                        (
                            [uuid] => 11f6fee9-5c9a-41f9-b056-d56f81e200b0
                            [config_id] => 17
                            [data_presisi_tahun_id] => e6e3145f-7dca-4261-8fe5-54fba737764b
                            [rtm_id] => 2488
                            [keluarga_id] => 3575
                            [anggota_id] => 12899
                            [agama_id] => 4
                            [frekwensi_mengikuti_kegiatan_setahun] =>
                            [created_at] => 2025-04-16T10:50:01.000000Z
                            [created_by] =>
                            [updated_at] => 2025-04-16T10:50:01.000000Z
                            [updated_by] =>
                            [nik] => 5102104505740001
                            [nama] => NI WAYAN SARIWATI
                            [agama] => HINDU
                        )

                )

            [5] => Array
                (
                    [type] => anggota
                    [id] => 9ed20138-d8ab-4bff-8196-26d24213e5e1
                    [attributes] => Array
                        (
                            [uuid] => 9ed20138-d8ab-4bff-8196-26d24213e5e1
                            [config_id] => 17
                            [data_presisi_tahun_id] => e6e3145f-7dca-4261-8fe5-54fba737764b
                            [rtm_id] => 2488
                            [keluarga_id] => 3575
                            [anggota_id] => 12900
                            [agama_id] => 4
                            [frekwensi_mengikuti_kegiatan_setahun] =>
                            [created_at] => 2025-04-16T10:50:01.000000Z
                            [created_by] =>
                            [updated_at] => 2025-04-16T10:50:01.000000Z
                            [updated_by] =>
                            [nik] => 5102106410020001
                            [nama] => NI KADEK MERTA YUSTIA SARI
                            [agama] => HINDU
                        )

                )

            [6] => Array
                (
                    [type] => anggota
                    [id] => da7576e0-5027-4df2-8ae6-968b184d7e1b
                    [attributes] => Array
                        (
                            [uuid] => da7576e0-5027-4df2-8ae6-968b184d7e1b
                            [config_id] => 17
                            [data_presisi_tahun_id] => e6e3145f-7dca-4261-8fe5-54fba737764b
                            [rtm_id] => 2488
                            [keluarga_id] => 3575
                            [anggota_id] => 12901
                            [agama_id] => 4
                            [frekwensi_mengikuti_kegiatan_setahun] =>
                            [created_at] => 2025-04-16T10:50:01.000000Z
                            [created_by] =>
                            [updated_at] => 2025-04-16T10:50:01.000000Z
                            [updated_by] =>
                            [nik] => 5102102609710001
                            [nama] => I GEDE KERTIYUSA
                            [agama] => HINDU
                        )

                )

            [7] => Array
                (
                    [type] => keluarga
                    [id] => 4343
                    [attributes] => Array
                        (
                            [no_kk] => 5102102201240001
                        )

                )

            [8] => Array
                (
                    [type] => rtm
                    [id] => 2258
                    [attributes] => Array
                        (
                            [nik_kepala] => 12076
                            [no_kk] => 5102102003020201
                            [nama_kepala] =>
                        )

                )

            [9] => Array
                (
                    [type] => penduduk
                    [id] => 12078
                    [attributes] => Array
                        (
                            [nik] => 5102101001150003
                            [nama] => I GEDE VIO SAPUTRA
                        )

                )

            [10] => Array
                (
                    [type] => anggota
                    [id] => 0030daf3-76bb-47fd-9eb0-f137320f9d96
                    [attributes] => Array
                        (
                            [uuid] => 0030daf3-76bb-47fd-9eb0-f137320f9d96
                            [config_id] => 17
                            [data_presisi_tahun_id] => e6e3145f-7dca-4261-8fe5-54fba737764b
                            [rtm_id] => 2258
                            [keluarga_id] => 4343
                            [anggota_id] => 12078
                            [agama_id] => 4
                            [frekwensi_mengikuti_kegiatan_setahun] =>
                            [created_at] => 2025-04-16T10:49:56.000000Z
                            [created_by] =>
                            [updated_at] => 2025-04-16T10:49:56.000000Z
                            [updated_by] =>
                            [nik] => 5102101001150003
                            [nama] => I GEDE VIO SAPUTRA
                            [agama] => HINDU
                        )

                )

            [11] => Array
                (
                    [type] => anggota
                    [id] => 1cfedc41-d1f3-4faa-abdc-34304a92bcf6
                    [attributes] => Array
                        (
                            [uuid] => 1cfedc41-d1f3-4faa-abdc-34304a92bcf6
                            [config_id] => 17
                            [data_presisi_tahun_id] => e6e3145f-7dca-4261-8fe5-54fba737764b
                            [rtm_id] => 2258
                            [keluarga_id] => 4343
                            [anggota_id] => 12077
                            [agama_id] => 4
                            [frekwensi_mengikuti_kegiatan_setahun] =>
                            [created_at] => 2025-04-16T10:49:56.000000Z
                            [created_by] =>
                            [updated_at] => 2025-04-16T10:49:56.000000Z
                            [updated_by] =>
                            [nik] => 5102105812970001
                            [nama] => GUSTI AYU KADE CANDRA KARTIKA
                            [agama] => HINDU
                        )

                )

            [12] => Array
                (
                    [type] => anggota
                    [id] => 82c6ec84-5b84-41f0-a549-8c963ddc73f7
                    [attributes] => Array
                        (
                            [uuid] => 82c6ec84-5b84-41f0-a549-8c963ddc73f7
                            [config_id] => 17
                            [data_presisi_tahun_id] => e6e3145f-7dca-4261-8fe5-54fba737764b
                            [rtm_id] => 2258
                            [keluarga_id] => 4343
                            [anggota_id] => 12076
                            [agama_id] => 4
                            [frekwensi_mengikuti_kegiatan_setahun] =>
                            [created_at] => 2025-04-16T10:49:56.000000Z
                            [created_by] =>
                            [updated_at] => 2025-04-16T10:49:56.000000Z
                            [updated_by] =>
                            [nik] => 5102102508960001
                            [nama] => I KADEK DIARTANA
                            [agama] => HINDU
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
            [self] => http://localhost/api/v1/data-presisi/agama?include=keluarga%2Cpenduduk%2Canggota%2Crtm%2ClistAnggota&page%5Bsize%5D=2&page%5Bnumber%5D=1
            [first] => http://localhost/api/v1/data-presisi/agama?include=keluarga%2Cpenduduk%2Canggota%2Crtm%2ClistAnggota&page%5Bsize%5D=2&page%5Bnumber%5D=1
            [next] => http://localhost/api/v1/data-presisi/agama?include=keluarga%2Cpenduduk%2Canggota%2Crtm%2ClistAnggota&page%5Bsize%5D=2&page%5Bnumber%5D=2
            [last] => http://localhost/api/v1/data-presisi/agama?include=keluarga%2Cpenduduk%2Canggota%2Crtm%2ClistAnggota&page%5Bsize%5D=2&page%5Bnumber%5D=1762
        )

)
     */
    public function test_get_data_aktivitas_agama()
    {
        $user = User::inRandomOrder()->first();
        Sanctum::actingAs($user);
        $url = '/api/v1/data-presisi/agama?'.http_build_query([
            'include' => 'keluarga,penduduk,anggota,rtm,listAnggota',
            // 'page[size]' => 2,
        ]);
        $total = DataPresisiAgama::tahunAktif()->count();
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

    public function test_get_data_aktivitas_agama_kepala_rtm()
    {
        $user = User::inRandomOrder()->first();
        Sanctum::actingAs($user);
        $url = '/api/v1/data-presisi/agama?'.http_build_query([
            'filter[kepala_rtm]' => true,
        ]);
        $total = DataPresisiAgama::tahunAktif()->kepalaRtm()->count();
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

    public function test_get_data_aktivitas_agama_kepala_rtm_kecamatan()
    {
        $user = User::inRandomOrder()->first();
        Sanctum::actingAs($user);
        if(!DataPresisiAgama::distinct('config_id')->exists()) {
            $this->markTestSkipped('Tidak ada data presisi agama yang tersedia');
        }
        $kodeKecamatan = DataPresisiAgama::distinct('config_id')->inRandomOrder()->first()->config->kode_kecamatan;
        $url = '/api/v1/data-presisi/agama?'.http_build_query([
            'filter[kepala_rtm]' => true,
            'filter[kode_kecamatan]' => $kodeKecamatan,
        ]);
        $total = DataPresisiAgama::tahunAktif()->kepalaRtm()->whereHas('config', function ($query) use ($kodeKecamatan) {
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

    public function test_get_data_aktivitas_agama_kepala_rtm_desa()
    {
        $user = User::inRandomOrder()->first();
        Sanctum::actingAs($user);
        if(!DataPresisiAgama::distinct('config_id')->exists()) {
            $this->markTestSkipped('Tidak ada data presisi agama yang tersedia');
        }
        $kodeDesa = DataPresisiAgama::distinct('config_id')->inRandomOrder()->first()->config->kode_desa;
        $url = '/api/v1/data-presisi/agama?'.http_build_query([
            'filter[kepala_rtm]' => true,
            'filter[kode_desa]' => $kodeDesa,
        ]);
        $total = DataPresisiAgama::tahunAktif()->kepalaRtm()->whereHas('config', function ($query) use ($kodeDesa) {
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
