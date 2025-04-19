<?php

namespace App\Http\Controllers\Api;

use App\Http\Repository\DataPresisiPanganRepository;
use App\Http\Transformers\DataPresisiPanganTransformer;
use App\Http\Transformers\RtmPanganTransformer;
use App\Models\DataPresisiPangan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataPresisiPanganController extends Controller
{
    public function __construct(protected DataPresisiPanganRepository $pangan)
    {
    }

    public function pangan()
    {
        return $this->fractal($this->pangan->listPangan(), new DataPresisiPanganTransformer(), 'data_presisi_pangan')->respond();
    }

    public function rtm()
    {
        return $this->fractal($this->pangan->listRtm(), new RtmPanganTransformer(), 'data_presisi_rtm')->respond();
    }

    public function update(Request $request, $id)
    {
        try {

            DB::beginTransaction();
            
            $data = $request->all();

            $sandang = DataPresisiPangan::where('rtm_id', $id)->first();

            $data['tanggal_pengisian'] = Carbon::now()->format('Y-m-d');
            $data['status_pengisian'] = 'lengkap';

            $sandang->update($data);


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
