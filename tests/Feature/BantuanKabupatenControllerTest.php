<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BantuanKabupatenControllerTest extends TestCase
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
    public function test_get_bantuan_kabupaten(): void
    {
        $url = '/api/v1/bantuan-kabupaten';

        $response = $this->getJson($url);

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJsonStructure([
            'data' => [],
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

    public function test_get_create_update_and_delete_bantuan_kabupaten(): void
    {
        $url = '/api/v1/bantuan-kabupaten/tambah';
        $urlUpdate = '/api/v1/bantuan-kabupaten/perbarui/';
        $urlDelete = '/api/v1/bantuan-kabupaten/hapus';

        $response = $this->postJson($url, [
            'nama' => "Bantuan Pendidikan",
            'sasaran' => 2,
            'ndesc' => "Bantuan untuk sekolah di daerah terpencil",
            'sdate' => "2025-02-12",
            'edate' => "2025-12-31",
            'asaldana' => "APBD",
        ]);

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJson([
            'success' => true,
        ]);
        
         // Ambil ID dari response jika tersedia
         $createdData = $response->json();

         $createdId = $createdData['data']['id'] ?? null;
         $this->assertNotNull($createdId, "ID dari data yang baru dibuat harus tersedia.");

        //  UPDATE
         $responseUpdate = $this->putJson($urlUpdate . $createdId, [
            'nama' => "Bantuan Pendidikan Update",
            'sasaran' => 1,
            'ndesc' => "Bantuan untuk sekolah di daerah terpencil Update",
            'sdate' => "2025-02-12",
            'edate' => "2025-12-31",
            'asaldana' => "APBD Update",
        ]);

        $responseUpdate->assertStatus(Response::HTTP_OK);

        $responseUpdate->assertJson([
            'success' => true,
        ]);
 
         // 3. DELETE - Hapus data yang baru dibuat
         $response = $this->postJson($urlDelete, [
            'id' => $createdId
         ]);
         $response->assertStatus(Response::HTTP_OK);
         $response->assertJson([
             'success' => true,
         ]);
    }
}
