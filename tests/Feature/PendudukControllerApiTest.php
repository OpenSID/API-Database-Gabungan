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
}
