<?php

namespace App\Http\Controllers\Api;

use App\Http\Repository\DataPresisiKetenagakerjaanRepository;
use App\Http\Transformers\DataPresisiKetenagakerjaanTransformer;
use App\Http\Transformers\RtmKetenagakerjaanTransformer;
use App\Models\DataPresisiKetenagakerjaan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataPresisiKetenagakerjaanController extends Controller
{
    public function __construct(protected DataPresisiKetenagakerjaanRepository $ketenagakerjaan)
    {
    }

    public function ketenagakerjaan()
    {
        return $this->fractal($this->ketenagakerjaan->listKetenagakerjaan(), new DataPresisiKetenagakerjaanTransformer(), 'data_presisi_ketenagakerjaan')->respond();
    }

    public function rtm()
    {
        return $this->fractal($this->ketenagakerjaan->listRtm(), new RtmKetenagakerjaanTransformer(), 'data_presisi_rtm')->respond();
    }

    public function update(Request $request, $id)
    {
        try {

            DB::beginTransaction();
            
            $data = $request->get('form'); // Ambil array yang dikirim

            foreach($data as $item){

                $sandang = DataPresisiKetenagakerjaan::where('rtm_id', $id)->where('anggota_id', $item['anggota_id'])->first();

                // Cek apakah semua item tidak kosong
                if (!in_array(null, $item, true) && !in_array('', $item, true)) {
                    // Semua data terisi, tambahkan tanggal hari ini dan status "lengkap"
                    $item['tanggal_pengisian'] = Carbon::now()->format('Y-m-d'); // Format: YYYY-MM-DD
                    $item['status_pengisian'] = 'lengkap';
                }

                // Lakukan update data
                $sandang->update([
                    'jenis_pekerjaan' => $item['jenis_pekerjaan'],
                    'tempat_kerja' => $item['tempat_kerja'],
                    'frekwensi_mengikuti_pelatihan_setahun' => $item['frekwensi_mengikuti_pelatihan_setahun'],
                    'jenis_pelatihan_diikuti_setahun' => $item['jenis_pelatihan_diikuti_setahun'],
                    'tanggal_pengisian' => $item['tanggal_pengisian'] ?? null,
                    'status_pengisian' => $item['status_pengisian'] ?? null,
                ]);

            }


            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil Ubah Data'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

}
