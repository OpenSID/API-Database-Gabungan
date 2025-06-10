<?php

namespace Tests\Feature;

use App\Models\BantuanSaja;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BantuanOpenDKTest extends TestCase
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
    public function test_get_bantuan_opendk(): void
    {
        $url = '/api/v1/opendk/bantuan';
        $response = $this->getJson($url);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'attributes' => [
                        'nama',
                        'sasaran',
                        'nama_sasaran',
                        'jumlah_peserta',
                        'ndesc',
                        'sdate',
                        'edate',
                        'status',
                        'nama_status',
                        'asaldana',
                        'masa_berlaku',
                        'desa',
                        'kode_desa',
                    ],
                ],
            ],
        ]);
    }

    public function test_get_bantuan_opendk_by_id(): void
    {

        $url = '/api/v1/opendk/bantuan';
        $response = $this->getJson($url);
        $response->assertStatus(Response::HTTP_OK);

        $bantuan = $response->json();

        $bantuanList = $bantuan['data'] ?? [];

        if (!empty($bantuanList)) {
            $bantuan = collect($bantuanList)->random();

            // Pastikan key 'id' ada
            if (isset($bantuan['id'])) {
                $url = "/api/v1/opendk/bantuan/{$bantuan['id']}";
            } else {
                $this->fail('Key ID tidak ditemukan dalam Bantuan OpenDK.');
            }
        } else {
            $this->fail('Response Bantuan kosong.');
        }

        $response = $this->getJson($url);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                'attributes' => [
                    'nama',
                    'sasaran',
                    'nama_sasaran',
                    'jumlah_peserta',
                    'ndesc',
                    'sdate',
                    'edate',
                    'status',
                    'nama_status',
                    'asaldana',
                    'masa_berlaku',
                    'desa',
                    'kode_desa',
                ],
            ],
        ]);
    }

    public function test_get_bantuan_peserta(): void
    {
        $kodeKecamatan = BantuanSaja::distinct('config_id')->inRandomOrder()->first()->config->kode_kecamatan;
        $url = '/api/v1/opendk/bantuan-peserta?'.http_build_query([
            'filter[kode_kecamatan]' => $kodeKecamatan,
            'page[size]' => 5,
        ]);

        $response = $this->getJson($url);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'attributes' => [
                        'peserta',
                        'nama',
                        'nik',
                        'no_kk',
                        'program_id',
                        'program' => [],
                        'no_id_kartu',
                        'kartu_nama',
                        'kartu_nik',
                        'kartu_tempat_lahir',
                        'kartu_tanggal_lahir',
                        'kartu_alamat',
                        'jenis_kelamin',
                        'keterangan',
                    ],
                ],
            ],
        ]);
    }

    public function test_get_bantuan_opendk_by_id_and_kode_desa(): void
    {

        $url = '/api/v1/opendk/bantuan';
        $response = $this->getJson($url);
        $response->assertStatus(Response::HTTP_OK);

        $bantuan = $response->json();

        $bantuanList = $bantuan['data'] ?? [];

        if (!empty($bantuanList)) {
            $bantuan = collect($bantuanList)->random();
            $randomBantuan = $bantuanList[array_rand($bantuanList)]['attributes'] ?? [];

            // Pastikan key 'id' ada
            if (isset($bantuan['id'])) {
                $url = "/api/v1/opendk/bantuan-peserta/{$bantuan['id']}/{$randomBantuan['kode_desa']}";
            } else {
                $this->fail('Key ID tidak ditemukan dalam Bantuan OpenDK.');
            }
        } else {
            $this->fail('Response Bantuan kosong.');
        }

        $response = $this->getJson($url);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data',
            'meta' => [
                'pagination' => [
                    'total',
                    'count',
                    'per_page',
                    'current_page',
                    'total_pages',
                ],
            ],
            'links' => [
                'self',
                'first',
                'last',
            ],
        ]);


    }
}
