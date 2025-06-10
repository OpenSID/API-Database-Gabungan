<?php

namespace Tests\Feature;

use App\Models\Bantuan;
use App\Models\BantuanPeserta;
use App\Models\Config;
use Illuminate\Http\Response;
use Tests\TestCase;

class StatistikControllerApiTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_data_list_coordinate()
    {
        $kodeKecamatan = Config::inRandomOrder()->first()->kode_kecamatan;
        $url = '/api/v1/statistik-web/get-list-coordinate?'.http_build_query([
            'filter' => [
                'kecamatan' => $kodeKecamatan,
            ],
        ]);
        $total = Config::where('kode_kecamatan', $kodeKecamatan)->count();
        $response = $this->getJson($url);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals($total, count($response->json() ?? []));
        $response->assertJsonStructure([
            '*' => [
                'kode_propinsi',
                'nama_propinsi',
                'kode_kabupaten',
                'nama_kabupaten',
                'kode_kecamatan',
                'nama_kecamatan',
                'kode_desa',
                'nama_desa',
                'lat',
                'lng',
                'kode_pos',
            ],
        ]);
    }

    public function test_get_data_list_program()
    {
        $url = '/api/v1/statistik-web/get-list-program';
        $total = Bantuan::count();
        $response = $this->getJson($url);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals($total, count($response->json() ?? []));
        $response->assertJsonStructure([
            '*' => [
                'id',
                'nama',
            ],
        ]);
    }

    public function test_get_data_list_tahun()
    {
        $url = '/api/v1/statistik-web/get-list-tahun?'.http_build_query([]);
        $total = Bantuan::selectRaw('distinct YEAR(sdate) as year')->whereNotNull('slug')->get()->count();
        $response = $this->getJson($url);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals($total, count($response->json() ?? []));
        $response->assertJsonStructure([
            '*' => [
                'year',
            ],
        ]);
    }

    public function test_get_data_list_kabupaten()
    {
        $url = '/api/v1/statistik-web/get-list-kabupaten?'.http_build_query([]);
        $total = Config::select(['kode_kabupaten'])->distinct()->get()->count();
        $response = $this->getJson($url);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals($total, count($response->json() ?? []));
        $response->assertJsonStructure([
            '*' => [
                'kode_kabupaten',
                'nama_kabupaten',
            ],
        ]);
    }

    public function test_get_data_list_kecamatan()
    {
        $kodeKabupaten = Config::inRandomOrder()->first()->kode_kabupaten;
        $url = '/api/v1/statistik-web/get-list-kecamatan/'.$kodeKabupaten.'?'.http_build_query([]);
        $total = Config::select(['kode_kecamatan'])->where('kode_kabupaten', $kodeKabupaten)->distinct()->get()->count();
        $response = $this->getJson($url);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals($total, count($response->json() ?? []));
        $response->assertJsonStructure([
            '*' => [
                'kode_kecamatan',
                'nama_kecamatan',
            ],
        ]);
    }

    public function test_get_data_list_desa()
    {
        $kodeKecamatan = Config::inRandomOrder()->first()->kode_kecamatan;
        $url = '/api/v1/statistik-web/get-list-desa/'.$kodeKecamatan.'?'.http_build_query([]);
        $total = Config::where('kode_kecamatan', $kodeKecamatan)->count();
        $response = $this->getJson($url);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals($total, count($response->json() ?? []));
        $response->assertJsonStructure([
            '*' => [
                'kode_desa',
                'nama_desa',
            ],
        ]);
    }

    public function test_get_data_list_penerima()
    {
        $url = '/api/v1/statistik-web/get-list-penerima?'.http_build_query([
            'filter' => [
                'id' => 'penduduk',
            ],
        ]);
        $total = BantuanPeserta::join('program', 'program.id', '=', 'program_peserta.program_id', 'left')
            ->join('config', 'config.id', '=', 'program_peserta.config_id', 'left')
            ->join('tweb_penduduk', 'tweb_penduduk.id', '=', 'program_peserta.kartu_id_pend', 'left')
            ->where('program.sasaran', '=', Bantuan::SASARAN_PENDUDUK)
            ->count();
        $response = $this->getJson($url);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals($total, count($response->json() ?? []));
        $response->assertJsonStructure([
            '*' => [
                'nama_program',
                'nama_penerima',
                'alamat_penerima',
            ],
        ]);
    }
}

//         Route::get('/get-list-desa/{id}', 'getListDesa');
//         Route::get('/get-list-penerima', 'getListPenerimaBantuan');
