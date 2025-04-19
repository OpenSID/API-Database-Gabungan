<?php

namespace App\Http\Controllers\Api;

use App\Http\Repository\DataPresisiPendidikanRepository;
use App\Http\Transformers\DataPresisiPendidikanTransformer;
use App\Http\Transformers\RtmPendidikanTransformer;
use App\Models\DataPresisiPendidikan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataPresisiPendidikanController extends Controller
{
    public function __construct(protected DataPresisiPendidikanRepository $pendidikan)
    {
    }

    public function pendidikan()
    {
        return $this->fractal($this->pendidikan->listPendidikan(), new DataPresisiPendidikanTransformer(), 'data_presisi_pendidikan')->respond();
    }

    public function rtm()
    {
        return $this->fractal($this->pendidikan->listRtm(), new RtmPendidikanTransformer(), 'data_presisi_rtm')->respond();
    }

    public function update(Request $request, $id)
    {
        try {

            DB::beginTransaction();
            
            $data = $request->get('form'); // Ambil array yang dikirim

            foreach($data as $item){

                $sandang = DataPresisiPendidikan::where('rtm_id', $id)->where('anggota_id', $item['anggota_id'])->first();

                // Cek apakah semua item tidak kosong
                if (!in_array(null, $item, true) && !in_array('', $item, true)) {
                    // Semua data terisi, tambahkan tanggal hari ini dan status "lengkap"
                    $item['tanggal_pengisian'] = Carbon::now()->format('Y-m-d'); // Format: YYYY-MM-DD
                    $item['status_pengisian'] = 'lengkap';
                }

                // Lakukan update data
                $sandang->update([
                    'pendidikan_dalam_kk' => $item['pendidikan_dalam_kk'],
                    'pendidikan_sedang_ditempuh' => $item['pendidikan_sedang_ditempuh'],
                    'keikutsertaan_kip' => $item['keikutsertaan_kip'],
                    'jenis_pendidikan_kesetaraan_yg_diikuti' => $item['jenis_pendidikan_kesetaraan_yg_diikuti'],
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
