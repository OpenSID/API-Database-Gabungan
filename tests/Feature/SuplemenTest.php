<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Suplemen;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Illuminate\Http\Response;

class SuplemenTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $user = User::inRandomOrder()->first();
        $this->assertNotNull($user, "User tidak ditemukan di database. Pastikan ada data user.");
        Sanctum::actingAs($user);
    }

    public function test_get_data_suplemen()
    {
        $response = $this->getJson('/api/v1/suplemen');
        $response->assertStatus(Response::HTTP_OK)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['nama', 'sasaran', 'status', 'keterangan']
                     ]
                 ]);
    }

    public function test_store_suplemen()
    {
        $data = [
            'sasaran' => 1,
            'nama' => 'Suplemen A',
            'keterangan' => 'Keterangan Suplemen A',
            'status' => 1,
            'sumber' => 'OpenKab',
            'form_isian' => null
        ];

        $response = $this->postJson('/api/v1/suplemen', $data);
        $response->assertStatus(Response::HTTP_CREATED)
                 ->assertJson(['success' => true, 'message' => 'Data berhasil disimpan.']);
    }

    public function test_update_suplemen()
    {
        $suplemen = Suplemen::factory()->create();

        $updateData = ['sasaran' => 2,
            'nama' => 'Suplemen B',
            'keterangan' => 'Keterangan Suplemen B',
            'status' => 0,
            'sumber' => 'OpenSID',
            'form_isian' => null];
        $response = $this->postJson("/api/v1/suplemen/update/{$suplemen->id}", $updateData);

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJson(['success' => true, 'message' => 'Data berhasil diperbarui.']);
    }

    public function test_delete_suplemen()
    {
        $suplemen = Suplemen::factory()->create();
        $response = $this->deleteJson("/api/v1/suplemen/hapus/{$suplemen->id}");
        $response->assertStatus(Response::HTTP_OK)
                 ->assertJson(['success' => true]);
    }

    public function test_get_sasaran()
    {
        $response = $this->getJson('/api/v1/suplemen/sasaran');
        $response->assertStatus(Response::HTTP_OK)
                 ->assertJsonStructure(['success', 'data' => [['id', 'nama']]]);
    }

    public function test_get_status()
    {
        $response = $this->getJson('/api/v1/suplemen/status');
        $response->assertStatus(Response::HTTP_OK)
                 ->assertJsonStructure(['success', 'data' => [['id', 'nama']]]);
    }
}
