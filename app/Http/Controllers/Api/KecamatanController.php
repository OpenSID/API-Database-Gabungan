<?php

namespace App\Http\Controllers\Api;

use App\Http\Repository\KecamatanRepository;
use App\Http\Transformers\KecamatanTransformer;
use App\Models\Config;

class KecamatanController extends Controller
{
    public function __construct(protected KecamatanRepository $kec)
    {
    }

    public function index()
    {
        return $this->fractal($this->kec->list(), new KecamatanTransformer(), 'daftar kecamatan')->respond();
    }

    public function all($kec = '')
    {
        $query = Config::where('kode_kecamatan', $kec)
        ->whereNotNull('path')
        ->where('path', '<>', '')
        ->get();

        if ($query->isEmpty()) {
            return ['data' => null]; // Pastikan respons tetap konsisten
        }

        $groupedData = $query->groupBy('kode_kecamatan')->map(function ($items) {
            return (object) [
                'kode_kecamatan' => $items->first()->kode_kecamatan,
                'nama_kecamatan' => $items->first()->nama_kecamatan,
                'path' => json_encode(
                $items->pluck('path')
                    ->filter()
                    ->map(fn ($p) => json_decode($p, true)) // Konversi JSON ke array
                    ->filter()
                    ->flatten(1) // Gabungkan semua array path
                    ->values() // Reset indeks array
                ),
            ];
        })->values()->first();

        return ['data' => $groupedData];
    }


}
