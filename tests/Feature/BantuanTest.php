<?php

namespace Tests\Feature;

use App\Models\Bantuan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BantuanTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $user = User::inRandomOrder()->first();
        Sanctum::actingAs($user);
    }

    /**
     * A basic feature test example.
     */
    public function test_get_program_bantuan(): void
    {
        $url = '/api/v1/bantuan';
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
                    ],
                ],
            ],
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

    public function test_get_peserta_bantuan(): void
    {
        $url = '/api/v1/bantuan/peserta';
        $response = $this->getJson($url);        
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'attributes' => [
                        'peserta',
                        'nik',
                        'no_kk',
                        'program_id',
                        'program' => [],
                        'no_id_kartu',
                        'kartu_nama',
                        'kartu_tempat_lahir',
                        'kartu_tanggal_lahir',
                        'kartu_alamat',
                        'jenis_kelamin' => [],
                        'keterangan' => [],
                    ],
                ],
            ],
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

    public function test_get_sasaran_bantuan(): void
    {
        $url = '/api/v1/bantuan/sasaran';
        $response = $this->getJson($url);        
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'success',
            'data' => [
                '*' => [
                    'id',
                    'nama',
                ],
            ],
        ]);
    }
    
    public function test_get_tahun_bantuan(): void
    {
        $url = '/api/v1/bantuan/tahun';
        $response = $this->getJson($url);        
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'tahun_awal',
                'tahun_akhir',
                'statistik' => [
                    'laki_laki',
                    'perempuan',
                ],
                'nama_sasaran',
                'jumlah_peserta',
                'nama_status',
                'peserta' => [],
            ],
        ]);
    }
}
