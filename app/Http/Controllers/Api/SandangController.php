<?php

namespace App\Http\Controllers\Api;

use App\Http\Repository\PendudukRepository;
use App\Http\Repository\SandangRepository;
use App\Http\Transformers\RtmTransformer;
use App\Http\Transformers\SandangTransformer;
use App\Models\Sandang;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SandangController extends Controller
{
    public function __construct(protected SandangRepository $sandang, protected PendudukRepository $penduduk)
    {
    }

    public function sandang()
    {
        return $this->fractal($this->penduduk->listPenduduk(), new SandangTransformer(), 'data_presisi_sandang')->respond();
        // return $this->fractal($this->sandang->listSandang(), new SandangTransformer(), 'data_presisi_sandang')->respond();
    }

    public function rtm()
    {
        return $this->fractal($this->sandang->listRtm(), new RtmTransformer(), 'data_presisi_rtm')->respond();
    }

    public function update(Request $request, $id)
    {
        try {

            DB::beginTransaction();
            
            $data = $request->get('form'); // Ambil array yang dikirim

            foreach($data as $item){

                $sandang = Sandang::where('rtm_id', $id)->where('anggota_id', $item['anggota_id'])->first();

                // Cek apakah semua item tidak kosong
                if (!in_array(null, $item, true) && !in_array('', $item, true)) {
                    // Semua data terisi, tambahkan tanggal hari ini dan status "lengkap"
                    $item['tanggal_pengisian'] = Carbon::now()->format('Y-m-d'); // Format: YYYY-MM-DD
                    $item['status_pengisian'] = 'lengkap';
                }

                // Lakukan update data
                $sandang->update([
                    'jml_pakaian_yg_dimiliki'       => $item['jml_pakaian_yg_dimiliki'],
                    'frekwensi_beli_pakaian_pertahun' => $item['frekwensi_beli_pakaian_pertahun'],
                    'jenis_pakaian'                 => $item['jenis_pakaian'],
                    'frekwensi_ganti_pakaian'        => $item['frekwensi_ganti_pakaian'],
                    'tmpt_cuci_pakaian'              => $item['tmpt_cuci_pakaian'],
                    'jml_pakaian_seragam'            => $item['jml_pakaian_seragam'],
                    'jml_pakaian_sembahyang'         => $item['jml_pakaian_sembahyang'],
                    'jml_pakaian_kerja'              => $item['jml_pakaian_kerja'],
                    'tanggal_pengisian'              => $item['tanggal_pengisian'] ?? null,
                    'status_pengisian'               => $item['status_pengisian'] ?? null,
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
