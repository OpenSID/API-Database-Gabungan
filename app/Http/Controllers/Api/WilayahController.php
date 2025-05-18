<?php

namespace App\Http\Controllers\Api;

use App\Http\Repository\WilayahRepository;
use App\Http\Transformers\WilayahTransformer;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class WilayahController extends Controller
{
    public function __construct(protected WilayahRepository $wilayah)
    {
    }

    public function dusun()
    {
        return $this->fractal($this->wilayah->listDusun(), function ($wilayah) {
            return $wilayah->toArray();
        }, 'dusun')->respond();
    }

    public function rw()
    {
        return $this->fractal($this->wilayah->listRW(), function ($wilayah) {
            return $wilayah->toArray();
        }, 'rw')->respond();
    }

    public function rt()
    {
        return $this->fractal($this->wilayah->listRT(), function ($wilayah) {
            return $wilayah->toArray();
        }, 'rt')->respond();
    }

    public function desa()
    {
        return $this->fractal($this->wilayah->listDesa(), function ($wilayah) {
            return $wilayah->toArray();
        }, 'desa')->respond();
    }

    public function penduduk()
    {
        return $this->fractal($this->wilayah->listTotalPenduduk(), function ($wilayah) {
            $wilayah->penduduk_count = angka_lokal($wilayah->penduduk_count);

            return $wilayah->toArray();
        }, 'desa')->respond();
    }

    public function kecamatan()
    {
        return $this->fractal($this->wilayah->listTotalPendudukKecamatan(), function ($wilayah) {
            $wilayah->penduduk_count = angka_lokal($wilayah->penduduk_count);
            return $wilayah->toArray();
        }, 'kecamatan')->respond();
    }
    
    public function wilayahId()
    {
        return $this->fractal($this->wilayah->listWilayahId(), new WilayahTransformer, 'wilayah')->respond();
    }

    public function storeDusun(Request $request)
    {
        try {
            $data = $request->validate([
                '*.config_id' => 'required',
                '*.rt' => 'required',
                '*.rw' => 'required',
                '*.dusun' => 'required'
            ]);
            
            $wilayah = Wilayah::insert($data);

            return response()->json([
                'success' => true,
                'data' => $wilayah
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
}
