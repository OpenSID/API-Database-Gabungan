<?php

namespace App\Http\Controllers\Api;

use App\Models\Config;
use App\Models\Potensi;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SummaryController extends Controller
{
    private $defaultRequest = [
        'luas_wilayah' => 1,
        'luas_pertanian' => 1,
        'luas_perkebunan' => 1,
        'luas_hutan' => 1,
        'luas_peternakan' => 1,
    ];

    private $luasKebun;

    private $luasHutan;

    private $luasTernak;

    public function __invoke(Request $request)
    {
        return response()->json([
            'data' => $this->data($request),
            'message' => 'Berhasil mengambil data summary',
        ], Response::HTTP_OK);
    }

    private function data($request)
    {
        $result = [];
        $userRequest = [];
        $filter = $request->get('filter');
        if ($filter) {
            if($filter['kode_desa']) {
                $configDesa = Config::where('kode_desa', $filter['kode_desa'])->first();
                request()->merge(['config_desa' => $configDesa->id]);
            }else {
                if($filter['kode_kecamatan']) {
                    request()->merge(['kode_kecamatan' => $filter['kode_kecamatan']]);
                }else{
                    if($filter['kode_kabupaten']) {
                        request()->merge(['kode_kabupaten' => $filter['kode_kabupaten']]);
                    }
                }
            }
        }

        if ($request->get('search')) {
            $userRequest = array_intersect($request->get('search'), $this->defaultRequest);
        } else {
            $userRequest = $this->defaultRequest;
        }
        $tahunTerakhir = Potensi::filterWilayah()->max('tahun');
        if (isset($userRequest['luas_wilayah'])) {
            $result['luas_wilayah'] = $this->getLuasWilayah($tahunTerakhir);
        }
        if (isset($userRequest['luas_pertanian'])) {
            $result['luas_pertanian'] = $this->getLuasPertanian($tahunTerakhir);
        }
        if (isset($userRequest['luas_perkebunan'])) {
            $result['luas_perkebunan'] = $this->getLuasPerkebunan($tahunTerakhir);
        }
        if (isset($userRequest['luas_hutan'])) {
            $result['luas_hutan'] = $this->getLuasHutan($tahunTerakhir);
        }
        if (isset($userRequest['luas_peternakan'])) {
            $result['luas_peternakan'] = $this->getLuasPeternakan($tahunTerakhir);
        }

        return $result;
    }

    private function getLuasWilayah($tahun)
    {
        $total = 0;
        $batasWilayah = Potensi::filterWilayah()->where('kategori', 'batas-wilayah')->where('tahun', $tahun)->get();
        if (! $batasWilayah->isEmpty()) {
            $batasWilayah->groupBy('config_id')->each(function ($item) use (&$total) {
                $luas = $item->sortByDesc('bulan')->first()->data['luas_desa'];
                $total += money($luas, 'IDR', true)->getValue();
            });
        }

        return angka_lokal($total);
    }

    private function getLuasPertanian($tahun)
    {
        if (! $this->luasHutan) {
            $this->getLuasHutan($tahun);
        }
        if (! $this->luasKebun) {
            $this->getLuasPerkebunan($tahun);
        }
        if (! $this->luasTernak) {
            $this->getLuasPeternakan($tahun);
        }

        $total = $this->luasHutan + $this->luasKebun + $this->luasTernak;

        return angka_lokal($total);
    }

    private function getLuasPerkebunan($tahun)
    {
        $total = 0;
        if ($this->luasKebun) {
            $total = $this->luasKebun;

            return angka_lokal($total);
        }
        $batasWilayah = Potensi::filterWilayah()->where('kategori', 'jenis-lahan')->where('tahun', $tahun)->get();
        if (! $batasWilayah->isEmpty()) {
            $batasWilayah->groupBy('config_id')->each(function ($item) use (&$total) {
                $luas = $item->sortByDesc('bulan')->first()->data['luas_tanah_perkebunan'];
                $total += money($luas, 'IDR', true)->getValue();
            });
        }
        $this->luasKebun = $total;

        return angka_lokal($total);
    }

    private function getLuasHutan($tahun)
    {
        $total = 0;
        if ($this->luasHutan) {
            $total = $this->luasHutan;

            return angka_lokal($total);
        }
        $batasWilayah = Potensi::filterWilayah()->where('kategori', 'kepemilikan-lahan-hutan')->where('tahun', $tahun)->get();
        if (! $batasWilayah->isEmpty()) {
            $batasWilayah->groupBy('config_id')->each(function ($item) use (&$total) {
                $luas = $item->sortByDesc('bulan')->first()->data['jumlah_luas_hutan'];
                $total += money($luas, 'IDR', true)->getValue();
            });
        }
        $this->luasHutan = $total;

        return angka_lokal($total);
    }

    private function getLuasPeternakan($tahun)
    {
        $total = 0;
        if ($this->luasTernak) {
            $total = $this->luasTernak;

            return angka_lokal($total);
        }
        $batasWilayah = Potensi::filterWilayah()->where('kategori', 'lahan-dan-pakan-ternak')->where('tahun', $tahun)->get();
        if (! $batasWilayah->isEmpty()) {
            $batasWilayah->groupBy('config_id')->each(function ($item) use (&$total) {
                $luas = $item->sortByDesc('bulan')->first()->data['luas_lahan_gembalan'];
                $total += money($luas, 'IDR', true)->getValue();
            });
        }
        $this->luasTernak = $total;

        return angka_lokal($total);
    }
}
