<?php

namespace App\Http\Repository;

use App\Models\Bantuan;
use App\Models\Enums\StatusDasarEnum;
use App\Models\Keluarga;
use App\Models\Penduduk;
use App\Models\Rtm;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class DasborRepository
{
    public function listDasbor()
    {
        $penduduk = Penduduk::status()->countStatistik()->filterWilayah()->first();

        return [
            'jumlah_penduduk_laki_laki' => $penduduk->laki_laki,
            'jumlah_penduduk_perempuan' => $penduduk->perempuan,
            'jumlah_penduduk' => Penduduk::status()->filterWilayah()->count(),
            'jumlah_keluarga' => Keluarga::status()->filterWilayah()->count(),
            'jumlah_rtm' => Rtm::status()->filterWilayah()->count(),
            'jumlah_bantuan' => Bantuan::count(),
            'grafik_penduduk' => $this->grafikPenduduk(),
        ];
    }

    // cara ini kemungkinan besar akan menyebabkan lemot, solusi lain adalah membuat tabel baru  untuk menyimpan data statistik penduduk
    // dan melakukan update setiap bulan
    private function grafikPenduduk()
    {
        // interval bulan
        $akhir = now()->format('Y-m');
        $awal = now()->subYear()->format('Y-m');
        $periods = CarbonPeriod::create($awal , '1 month', $akhir);
        $data = [];
        $pendudukSql= [];
        foreach ($periods as $period) {
            $akhirBulan = $period->endOfMonth()->format('Y-m-d');
            $bulan = $period->format('m');
            $tahun = $period->format('Y');
            $pendudukSql[] = Penduduk::selectRaw('COUNT(CASE WHEN tweb_penduduk.sex = 1 THEN tweb_penduduk.id END) AS laki_laki')
                ->selectRaw('COUNT(CASE WHEN tweb_penduduk.sex = 2 THEN tweb_penduduk.id END) AS perempuan')
                ->selectRaw("'$bulan' AS bulan")
                ->selectRaw("'$tahun' AS tahun")
                ->hidupPada($akhirBulan)
                ->filterWilayah()
                ->toBoundSql();
        }
        $pendudukResult = DB::connection('openkab')->select(implode(' UNION ALL ', $pendudukSql));
        foreach ($pendudukResult as $result) {
            $bulan = $result->bulan;
            $tahun = $result->tahun;
            $laki_laki = (int) $result->laki_laki;
            $perempuan = (int) $result->perempuan;

            $data[] = [
                'kategori' => bulan($bulan).' '.$tahun,
                'tahun' => $tahun,
                'bulan' => $bulan,
                'laki_laki' => $laki_laki,
                'perempuan' => $perempuan,
            ];
        }
        $data = collect($data);

        return [
            'kategori' => $data->pluck('kategori'),
            'laki_laki' => $data->pluck('laki_laki'),
            'perempuan' => $data->pluck('perempuan'),
        ];
    }
}
