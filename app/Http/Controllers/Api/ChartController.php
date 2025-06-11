<?php

namespace App\Http\Controllers\Api;

use App\Models\Kecamatan;
use App\Models\Penduduk;
use App\Models\Umur;
use Illuminate\Support\Str;

class ChartController extends Controller
{
    protected $kode_kabupaten;
    protected $tipe;
    protected $label;
    protected $judul;

    public function __construct() {
        $this->kode_kabupaten = request()->input('filter')['kode_kabupaten'] ?? null;
        $this->tipe = request()->input('filter')['tipe'] ?? null;
        $this->label = request()->input('filter')['label'] ?? null;
        $this->judul = request()->input('filter')['judul'] ?? null;
    }

    public function kecamatan()
    {
        $kecamatans = Kecamatan::select(
            'kode_kecamatan',
            'nama_kecamatan'
        )
            ->when($this->kode_kabupaten, function($query){
                $query->where('kode_kabupaten', $this->kode_kabupaten);
            })
            ->groupBy('kode_kecamatan', 'nama_kecamatan')
            ->get()
            ->map(function ($item) {
                return [
                    'kode_kecamatan' => $item->kode_kecamatan,
                    'nama_kecamatan' => $item->nama_kecamatan,
                    'data' => $this->dataset($item->kode_kecamatan),
                    'label' => $this->judul
                ];
            });

        return response()->json([
            'data' => $kecamatans,
            'label' => $this->label,
        ]);
    }

    public function dataset($kode_kecamatan)
    {
        $data = 0;
        $tipe = strtolower($this->tipe);

        switch($tipe){
            case 'rentang umur':
                $data = $this->caseRentangUmur($kode_kecamatan);
            break;
        }

        return $data;
    }

    // public function listStatistik(): array|object
    // {
    //     $tipe = strtolower($this->tipe);
    //     $kategori = Str::slug($tipe);

    //     return collect(match ($kategori) {
    //         'rentang-umur' => $this->caseRentangUmur(),
    //         'kategori-umur' => $this->caseKategoriUmur(),
    //         'akta-kelahiran' => $this->caseAktaKelahiran(),
    //         'akta-nikah' => $this->caseAktaNikah(),
    //         'status-covid' => $this->caseStatusCovid(),
    //         'suku' => $this->caseSuku(),
    //         'ktp' => $this->caseKtp(),
    //         default => $this->caseWithReferensi($kategori),
    //     })->toArray();
    // }
    
    private function caseRentangUmur($kode_kecamatan): int
    {
        return Umur::countStatistikUmur()->status(Umur::RENTANG)->count();

        
    }
}
