<?php

namespace Tests\Feature;

use App\Models\Penduduk;
use App\Models\PendudukStatus;
use App\Models\PendudukStatusDasar;
use App\Models\Sex;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Schema;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PendudukControllerApiTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_data_penduduk(): void
    {
        $user = User::inRandomOrder()->first();
        Sanctum::actingAs($user);
        $url = '/api/v1/penduduk';
        $total = Penduduk::count();
        $response = $this->getJson($url);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonPath('meta.pagination.total', $total);
        $columnPenduduk = Schema::getColumnListing('tweb_penduduk');
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'attributes' => $columnPenduduk,
                ],
            ],
        ]);
    }

    public function test_get_data_penduduk_filter_id(): void
    {
        $user = User::inRandomOrder()->first();
        Sanctum::actingAs($user);
        $id = Penduduk::inRandomOrder()->first()->id;
        $url = '/api/v1/penduduk?'.http_build_query([
            'filter[id]' => $id,
        ]);
        $response = $this->getJson($url);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonPath('meta.pagination.total', 1);
        $columnPenduduk = Schema::getColumnListing('tweb_penduduk');
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'attributes' => $columnPenduduk,
                ],
            ],
        ]);
    }

    public function test_get_data_penduduk_referensi_sex(): void
    {
        $user = User::inRandomOrder()->first();
        Sanctum::actingAs($user);
        $url = '/api/v1/penduduk/referensi/sex?'.http_build_query([]);
        $response = $this->getJson($url);
        $total = Sex::count();
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonPath('meta.pagination.total', $total);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'attributes' => [
                        'nama',
                    ],
                ],
            ],
        ]);
    }

    public function test_get_data_penduduk_referensi_status(): void
    {
        $user = User::inRandomOrder()->first();
        Sanctum::actingAs($user);
        $url = '/api/v1/penduduk/referensi/status?'.http_build_query([]);
        $response = $this->getJson($url);
        $total = PendudukStatus::count();
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonPath('meta.pagination.total', $total);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'attributes' => [
                        'nama',
                    ],
                ],
            ],
        ]);
    }

    public function test_get_data_penduduk_referensi_status_dasar(): void
    {
        $user = User::inRandomOrder()->first();
        Sanctum::actingAs($user);
        $url = '/api/v1/penduduk/referensi/status-dasar?'.http_build_query([]);
        $response = $this->getJson($url);
        $total = PendudukStatusDasar::count();
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonPath('meta.pagination.total', $total);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'attributes' => [
                        'nama',
                    ],
                ],
            ],
        ]);
    }

    public function test_get_data_penduduk_with_nik_kode_kecamatan_tanggal_lahir(): void
    {
        $user = User::inRandomOrder()->first();
        Sanctum::actingAs($user);

        $penduduk = Penduduk::with('config')->inRandomOrder()->first();

        $url = '/api/v1/opendk/penduduk-nik-tanggalahir';

        $response = $this->postJson($url, [
            'kode_kecamatan' => $penduduk?->config?->kode_kecamatan,
            'nik' => $penduduk?->nik,
            'tanggallahir' => $penduduk?->tanggallahir,
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'id',
                'config_id',
                'nama',
                'id_kk',
                'kk_level',
                'id_rtm',
                'rtm_level',
                'sex',
                'tempatlahir',
                'tanggallahir',
                'agama_id',
                'pendidikan_kk_id',
                'pendidikan_sedang_id',
                'pekerjaan_id',
                'status_kawin',
                'warganegara_id',
                'dokumen_pasport',
                'dokumen_kitas',
                'ayah_nik',
                'ibu_nik',
                'nama_ayah',
                'nama_ibu',
                'foto',
                'golongan_darah_id',
                'id_cluster',
                'status',
                'alamat_sebelumnya',
                'alamat_sekarang',
                'status_dasar',
                'hamil',
                'cacat_id',
                'sakit_menahun_id',
                'akta_lahir',
                'akta_perkawinan',
                'tanggalperkawinan',
                'akta_perceraian',
                'tanggalperceraian',
                'cara_kb_id',
                'telepon',
                'tanggal_akhir_paspor',
                'no_kk_sebelumnya',
                'ktp_el',
                'status_rekam',
                'waktu_lahir',
                'tempat_dilahirkan',
                'jenis_kelahiran',
                'kelahiran_anak_ke',
                'penolong_kelahiran',
                'berat_lahir',
                'panjang_lahir',
                'tag_id_card',
                'created_at',
                'created_by',
                'updated_at',
                'updated_by',
                'id_asuransi',
                'no_asuransi',
                'status_asuransi',
                'email',
                'email_token',
                'email_tgl_kadaluarsa',
                'email_tgl_verifikasi',
                'telegram',
                'telegram_token',
                'telegram_tgl_kadaluarsa',
                'telegram_tgl_verifikasi',
                'bahasa_id',
                'ket',
                'negara_asal',
                'tempat_cetak_ktp',
                'tanggal_cetak_ktp',
                'suku',
                'bpjs_ketenagakerjaan',
                'hubung_warga',
                'namaTempatDilahirkan',
                'namaJenisKelahiran',
                'namaPenolongKelahiran',
                'wajibKTP',
                'elKTP',
                'statusPerkawinan',
                'statusHamil',
                'namaAsuransi',
                'namaSakitMenahun',
                'umur',
                'tanggalLahirId',
                'urlFoto',
                'alamat_wilayah',
                'keluarga' => [
                    'id',
                    'config_id',
                    'no_kk',
                    'nik_kepala',
                    'tgl_daftar',
                    'kelas_sosial',
                    'tgl_cetak_kk',
                    'alamat',
                    'id_cluster',
                    'updated_at',
                    'updated_by',
                    'wilayah' => [
                        'id',
                        'config_id',
                        'rt',
                        'rw',
                        'dusun',
                        'id_kepala',
                        'lat',
                        'lng',
                        'zoom',
                        'path',
                        'map_tipe',
                        'warna',
                        'border',
                        'urut',
                        'urut_cetak',
                    ],
                ],
            ],
        ]);

    }


}
