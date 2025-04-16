<?php

namespace App\Http\Controllers\Api;

use App\Http\Repository\DataPresisiSeniBudayaRepository;
use App\Http\Transformers\DataPresisiSeniBudayaTransformer;
use App\Http\Transformers\RtmSeniBudayaTransformer;
use App\Models\DataPresisiSeniBudaya;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataPresisiSeniBudayaController extends Controller
{
    public function __construct(protected DataPresisiSeniBudayaRepository $seniBudaya)
    {
    }

    public function seniBudaya()
    {
        return $this->fractal($this->seniBudaya->listSeniBudaya(), new DataPresisiSeniBudayaTransformer(), 'data_presisi_seni_budaya')->respond();
    }

    public function rtm()
    {
        return $this->fractal($this->seniBudaya->listRtm(), new RtmSeniBudayaTransformer(), 'data_presisi_rtm')->respond();
    }

    public function update(Request $request, $id)
    {
        try {

            DB::beginTransaction();
            
            $data = $request->get('form'); // Ambil array yang dikirim

            foreach($data as $item){

                $seniBudaya = DataPresisiSeniBudaya::where('rtm_id', $id)->where('anggota_id', $item['anggota_id'])->first();

                // Cek apakah semua item tidak kosong
                if (!in_array(null, $item, true) && !in_array('', $item, true)) {
                    // Semua data terisi, tambahkan tanggal hari ini dan status "lengkap"
                    $item['tanggal_pengisian'] = Carbon::now()->format('Y-m-d'); // Format: YYYY-MM-DD
                    $item['status_pengisian'] = 'lengkap';
                }

                // Lakukan update data
                $seniBudaya->update([
                    'jenis_seni_yang_dikuasai' => $item['jenis_seni_yang_dikuasai'],
                    'jumlah_penghasilan_dari_seni' => $item['jumlah_penghasilan_dari_seni'],
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
