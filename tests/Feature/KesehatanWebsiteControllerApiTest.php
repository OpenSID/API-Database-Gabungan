<?php

namespace Tests\Feature;

use Illuminate\Http\Response;
use Tests\TestCase;

class KesehatanWebsiteControllerApiTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_data_kesehatan()
    {
        $url = '/api/v1/data-kesehatan?'.http_build_query([

        ]);
        $response = $this->getJson($url, [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ]);
        // Pastikan responsnya berhasil
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'attributes' => [
                        "bulanIniIbuHamil" ,
                        "bulanIniAnak" ,
                        "totalIbuHamil" ,
                        "totalAnak" ,
                        "totalAnakNormal" ,
                        "totalAnakResiko" ,
                        "totalAnakStunting" ,
                        "widgets" ,
                        "navigasi" ,
                        "dataAnak0sd2Tahun" ,
                        "idPosyandu" ,
                        "posyandu" ,
                        "JTRT" ,
                        "jumlahKekRisti" ,
                        "jumlahGiziBukanNormal" ,
                        "tikar" ,
                        "ibu_hamil" ,
                        "bulanan_anak" ,
                        "dataTahun" ,
                        "kuartal" ,
                        "_tahun" ,
                        "aktif" ,
                        "chartStuntingUmurData" ,
                        "chartStuntingPosyanduData"
                    ],
                ],
            ],
        ]);
    }
}
