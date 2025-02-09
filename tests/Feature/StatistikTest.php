<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StatistikTest extends TestCase
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
    public function test_get_statistik_all_menu_all_kategori_id(): void
    {
        // ambil semua menu statistik
        $menu = [
            'penduduk',
            'keluarga',
            'rtm',
            'bantuan',
        ];

        // ambil secara random
        $randomKategori = $menu[array_rand($menu)];

        // ambil kategori statistik berdasarkan menu yang di pilih
        $urlKategori = '/api/v1/statistik/kategori-statistik?'.http_build_query([
            'filter[id]' => $randomKategori,
        ]);

        $response = $this->getJson($urlKategori);

        $response->assertStatus(Response::HTTP_OK);

        $data = $response->json('data');

        // ambil id kategori
        $ids = collect($data)->pluck('id')->toArray(); // Mengambil semua ID dari data

        // buat random id kategori
        $randomId = $ids[array_rand($ids)];

        // get endpoint berdasarkan menu dan id kategori
        $url = "/api/v1/statistik/{$randomKategori}?".http_build_query([
            'filter[id]' => $randomId,
            'filter[tahun]' => '',
            'filter[bulan]' => '',
        ]);

        $res = $this->getJson($url); 

        $res->assertStatus(Response::HTTP_OK);

    }

    public function test_get_statistik_all_menu_all_kategori_id_by_filter_tahun_bulan(): void
    {
        // ambil semua menu statistik
        $menu = [
            'penduduk',
            'keluarga',
            'rtm',
            'bantuan',
        ];

        // ambil secara random
        $randomKategori = $menu[array_rand($menu)];

        // ambil kategori statistik berdasarkan menu yang di pilih
        $urlKategori = '/api/v1/statistik/kategori-statistik?'.http_build_query([
            'filter[id]' => $randomKategori,
        ]);

        $response = $this->getJson($urlKategori);

        $response->assertStatus(Response::HTTP_OK);

        $data = $response->json('data');

        // ambil id kategori
        $ids = collect($data)->pluck('id')->toArray(); // Mengambil semua ID dari data

        // buat random id kategori
        $randomId = $ids[array_rand($ids)];

        // get endpoint berdasarkan menu dan id kategori
        $url = "/api/v1/statistik/{$randomKategori}?".http_build_query([
            'filter[id]' => $randomId,
            'filter[tahun]' => date('Y'),
            'filter[bulan]' => date('n'),
        ]);

        $res = $this->getJson($url); 

        $res->assertStatus(Response::HTTP_OK);

    }
}
