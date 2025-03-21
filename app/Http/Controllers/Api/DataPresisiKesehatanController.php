<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repository\DataPresisiKesehatanRepository;
use App\Http\Transformers\DataPresisiKesehatanTransformer;
use App\Http\Transformers\RtmTransformer;
use App\Models\DataPresisiKesehatan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataPresisiKesehatanController extends Controller
{
    public function __construct(protected DataPresisiKesehatanRepository $kesehatan)
    {
    }

    public function kesehatan()
    {
        return $this->fractal($this->kesehatan->listKesehatan(), new DataPresisiKesehatanTransformer(), 'data_presisi_kesehatan')->respond();
    }

    public function rtm()
    {
        return $this->fractal($this->kesehatan->listRtm(), new RtmTransformer(), 'data_presisi_rtm')->respond();
    }

    public function update(Request $request, $id)
    {
        try {

            DB::beginTransaction();
            
            $data = $request->get('form'); // Ambil array yang dikirim

            foreach($data as $item){

                $sandang = DataPresisiKesehatan::where('rtm_id', $id)->where('anggota_id', $item['anggota_id'])->first();

                // Cek apakah semua item tidak kosong
                if (!in_array(null, $item, true) && !in_array('', $item, true)) {
                    // Semua data terisi, tambahkan tanggal hari ini dan status "lengkap"
                    $item['tanggal_pengisian'] = Carbon::now()->format('Y-m-d'); // Format: YYYY-MM-DD
                    $item['status_pengisian'] = 'lengkap';
                }

                // Lakukan update data
                $sandang->update([
                    'jns_ansuransi' => $item['jns_ansuransi'],
                    'jns_penggunaan_alat_kontrasepsi' => $item['jns_penggunaan_alat_kontrasepsi'],
                    'jns_penyakit_diderita' => $item['jns_penyakit_diderita'],
                    'frekwensi_kunjungan_faskes_pertahun' => $item['frekwensi_kunjungan_faskes_pertahun'],
                    'frekwensi_rawat_inap_pertahun' => $item['frekwensi_rawat_inap_pertahun'],
                    'frekwensi_kunjungan_dokter_pertahun' => $item['frekwensi_kunjungan_dokter_pertahun'],
                    'kondisi_fisik_sejak_lahir' => $item['kondisi_fisik_sejak_lahir'],
                    'status_gizi_balita' => $item['status_gizi_balita'],
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
