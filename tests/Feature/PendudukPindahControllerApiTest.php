<?php

namespace Tests\Feature;

use App\Models\Config;
use App\Models\Penduduk;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PendudukPindahControllerApiTest extends TestCase
{
    use DatabaseTransactions;    

    public function test_get_data_penduduk_pindah(): void
    {        
        $user = User::inRandomOrder()->first();
        Sanctum::actingAs($user);
        $penduduk = Penduduk::inRandomOrder()->whereHas('keluarga')->first();
        $desaTujuan = Config::inRandomOrder()->where('id', '!=', $penduduk->config_id)->first();
        $response = $this->postJson('/api/v1/penduduk/aksi/pindah', [
            'id' => $penduduk->id,
            'config_asal' => $penduduk->config_id,
            'kelurahan_tujuan' => $desaTujuan->id,
            'tgl_peristiwa' => now()->format('Y-m-d'),
            'tgl_lapor' => now()->format('Y-m-d'),            
            'alamat_tujuan' => 'alamat baru',  
            'catatan' => 'catatan',
            'ref_pindah' => 1,
            'status' => 1,
        ]);        
        $response->assertStatus(Response::HTTP_OK);        
        $this->assertDatabaseHas('tweb_penduduk', [
            'nik' => $penduduk->nik,
            'config_id' => $desaTujuan->id,
        ], 'openkab');
        $this->assertDatabaseHas('tweb_penduduk', [
            'nik' => $penduduk->nik,
            'config_id' => $penduduk->config_id,
            'status_dasar' => 3,
        ], 'openkab');
        $this->assertDatabaseMissing('tweb_penduduk', [
            'nik' => $penduduk->nik,
            'config_id' => $penduduk->config_id,
            'status_dasar' => 1,
        ], 'openkab');        
    }
}
