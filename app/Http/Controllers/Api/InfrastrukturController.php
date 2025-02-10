<?php

namespace App\Http\Controllers\Api;

use App\Http\Repository\InfrastrukturRepository;
use App\Http\Transformers\InfrastrukturTransformer;
use App\Models\Komoditas;
use App\Models\Potensi;

class InfrastrukturController extends Controller
{
    public function __construct(protected InfrastrukturRepository $prasarana)
    {
    }

    /**
     * Mengambil data infrastruktur: jalan, jembatan, sanitasi, dan air bersih.
     */
    public function data()
    {
        // Data Jalan Raya Aspal
        $jalanNegara = Komoditas::filterBySession()
            ->join('config', 'config.id', '=', 'prodeskel_komoditas.config_id', 'left')
            ->where('komoditas', '5__497')
            ->get();

        $jalanProvinsi = Komoditas::filterBySession()
            ->join('config', 'config.id', '=', 'prodeskel_komoditas.config_id', 'left')
            ->where('komoditas', '4__491')
            ->get();

        $jalanBaik = $jalanNegara->sum(fn ($item) => $item->data['kondisi_baik']) +
                     $jalanProvinsi->sum(fn ($item) => $item->data['kondisi_baik']);

        $jalanRusak = $jalanNegara->sum(fn ($item) => $item->data['kondisi_rusak']) +
                      $jalanProvinsi->sum(fn ($item) => $item->data['kondisi_rusak']);

        $jalanJumlah = $jalanBaik + $jalanRusak;

        // Data Jembatan Besi Beton
        $jembatanBeton = Komoditas::filterBySession()
            ->join('config', 'config.id', '=', 'prodeskel_komoditas.config_id', 'left')
            ->where('komoditas', '6__503')
            ->get();

        $jembatanBesi = Komoditas::filterBySession()
            ->join('config', 'config.id', '=', 'prodeskel_komoditas.config_id', 'left')
            ->where('komoditas', '6__504')
            ->get();

        $jembatanBaik = $jembatanBeton->sum(fn ($item) => $item->data['kondisi_baik']) +
                        $jembatanBesi->sum(fn ($item) => $item->data['kondisi_baik']);

        $jembatanRusak = $jembatanBeton->sum(fn ($item) => $item->data['kondisi_rusak']) +
                         $jembatanBesi->sum(fn ($item) => $item->data['kondisi_rusak']);

        $jembatanJumlah = $jembatanBaik + $jembatanRusak;

        // Data Sanitasi (Potensi)
        $sanitasi = Potensi::filterBySession()
            ->join('config', 'config.id', '=', 'prodeskel_potensi.config_id', 'left')
            ->sanitasi()
            ->get();

        $sumurResapan = $sanitasi->sum(fn ($item) => $item->data['sumur_resapan']) ?: '-';
        $mckUmum = $sanitasi->sum(fn ($item) => $item->data['mck_umum']) ?: '-';

        // Data Air Bersih (Potensi)
        $airBersih = Potensi::filterBySession()
            ->join('config', 'config.id', '=', 'prodeskel_potensi.config_id', 'left')
            ->airBersih()
            ->get();

        $sumurPompa = $airBersih->sum(fn ($item) => $item->data['air_bersih']) ?: '-';
        $embung = $airBersih->sum(fn ($item) => $item->data['embung']) ?: '-';

        // Data Infrastruktur
        $data = [
            [
                'kategori' => 'Transportasi Darat',
                'jenis_sarana' => 'Jalan Raya Aspal',
                'kondisi_baik' => $jalanBaik,
                'kondisi_rusak' => $jalanRusak,
                'jumlah' => $jalanJumlah,
                'satuan' => 'KM',
            ],
            [
                'kategori' => 'Transportasi Darat',
                'jenis_sarana' => 'Jembatan Besi Beton',
                'kondisi_baik' => $jembatanBaik,
                'kondisi_rusak' => $jembatanRusak,
                'jumlah' => $jembatanJumlah,
                'satuan' => 'Unit',
            ],
            [
                'kategori' => 'Sanitasi',
                'jenis_sarana' => 'Sumur Resapan',
                'kondisi_baik' => $sumurResapan,
                'kondisi_rusak' => '-',
                'jumlah' => $sumurResapan,
                'satuan' => 'Unit',
            ],
            [
                'kategori' => 'Sanitasi',
                'jenis_sarana' => 'MCK Umum',
                'kondisi_baik' => $mckUmum,
                'kondisi_rusak' => '-',
                'jumlah' => $mckUmum,
                'satuan' => 'Unit',
            ],
            [
                'kategori' => 'Air Bersih',
                'jenis_sarana' => 'Sumur Pompa',
                'kondisi_baik' => $sumurPompa,
                'kondisi_rusak' => '-',
                'jumlah' => $sumurPompa,
                'satuan' => 'Unit',
            ],
            [
                'kategori' => 'Air Bersih',
                'jenis_sarana' => 'Embung',
                'kondisi_baik' => $embung,
                'kondisi_rusak' => '-',
                'jumlah' => $embung,
                'satuan' => 'Unit',
            ],
        ];

        // Mengubah nilai 0 menjadi '-'
        $data = array_map(function ($item) {
            foreach ($item as $key => $value) {
                if ($value === 0 || $value === '0') {
                    $item[$key] = '-';
                }
            }

            return $item;
        }, $data);

        return response()->json($data);
    }

    /**
     * Mengambil data prasarana dan sarana.
     */
    public function prasaranaSarana()
    {
        return $this->fractal(
            $this->prasarana->index(),
            new InfrastrukturTransformer(),
            'prasarana-sarana'
        )->toArray();
    }
}
