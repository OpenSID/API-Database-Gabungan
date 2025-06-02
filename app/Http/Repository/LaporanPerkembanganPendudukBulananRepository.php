<?php

namespace App\Http\Repository;

use App\Enums\PindahEnum;
use App\Enums\WargaNegaraEnum;
use App\Models\Enums\JenisKelaminEnum;
use App\Models\Enums\SHDKEnum;
use App\Models\LogKeluarga;
use App\Models\LogPenduduk;
use App\Models\Penduduk;
use Carbon\Carbon;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class LaporanPerkembanganPendudukBulananRepository
{
    public function laporanPenduduk($tahun, $bulan)
    {
        
        $bulanDepan        = Carbon::create($tahun, $bulan)->addMonth();
        $pendudukAwalBulan = Penduduk::awalBulan($bulanDepan->format('Y'), $bulanDepan->format('m'))->get();
        $pendudukAwal      = [
            'WNI_L' => $pendudukAwalBulan->where('sex', JenisKelaminEnum::laki_laki)->where('warganegara_id', WargaNegaraEnum::WNI)->count(),
            'WNI_P' => $pendudukAwalBulan->where('sex', JenisKelaminEnum::perempuan)->where('warganegara_id', WargaNegaraEnum::WNI)->count(),
            'WNA_L' => $pendudukAwalBulan->where('sex', JenisKelaminEnum::laki_laki)->where('warganegara_id', '!=', WargaNegaraEnum::WNI)->count(),
            'WNA_P' => $pendudukAwalBulan->where('sex', JenisKelaminEnum::perempuan)->where('warganegara_id', '!=', WargaNegaraEnum::WNI)->count(),
            // keluarga
            'KK_L' => $pendudukAwalBulan->where('sex', JenisKelaminEnum::laki_laki)->where('kk_level', SHDKEnum::KEPALA_KELUARGA)->whereNotNull('id_kk')->count(),
            'KK_P' => $pendudukAwalBulan->where('sex', JenisKelaminEnum::perempuan)->where('kk_level', SHDKEnum::KEPALA_KELUARGA)->whereNotNull('id_kk')->count(),
        ];
        $mutasiPenduduk   = LogPenduduk::with(['penduduk' => static fn ($q) => $q->withOnly([])])->whereYear('tgl_lapor', $tahun)->whereMonth('tgl_lapor', $bulan)->get();
        $keluargaPenduduk = LogKeluarga::with(['keluarga.kepalaKeluarga' => static fn ($q) => $q->withOnly([])])->whereYear('tgl_peristiwa', $tahun)->whereMonth('tgl_peristiwa', $bulan)->get();
        $kelahiran = [
            'WNI_L' => $mutasiPenduduk->where('kode_peristiwa', LogPenduduk::BARU_LAHIR)->where('penduduk.sex', JenisKelaminEnum::laki_laki)->where('penduduk.warganegara_id', WargaNegaraEnum::WNI)->count(),
            'WNI_P' => $mutasiPenduduk->where('kode_peristiwa', LogPenduduk::BARU_LAHIR)->where('penduduk.sex', JenisKelaminEnum::perempuan)->where('penduduk.warganegara_id', WargaNegaraEnum::WNI)->count(),
            'WNA_L' => $mutasiPenduduk->where('kode_peristiwa', LogPenduduk::BARU_LAHIR)->where('penduduk.sex', JenisKelaminEnum::laki_laki)->where('penduduk.warganegara_id', '!=', WargaNegaraEnum::WNI)->count(),
            'WNA_P' => $mutasiPenduduk->where('kode_peristiwa', LogPenduduk::BARU_LAHIR)->where('penduduk.sex', JenisKelaminEnum::perempuan)->where('penduduk.warganegara_id', '!=', WargaNegaraEnum::WNI)->count(),
            // keluarga
            'KK_L' => $keluargaPenduduk->where('id_peristiwa', LogKeluarga::KELUARGA_BARU)->where('keluarga.kepalaKeluarga.sex', JenisKelaminEnum::laki_laki)->count(),
            'KK_P' => $keluargaPenduduk->where('id_peristiwa', LogKeluarga::KELUARGA_BARU)->where('keluarga.kepalaKeluarga.sex', JenisKelaminEnum::perempuan)->count(),
        ];
        $pendudukAwal['KK_L'] = $pendudukAwal['KK_L'] - $kelahiran['KK_L'];
        $pendudukAwal['KK_P'] = $pendudukAwal['KK_P'] - $kelahiran['KK_P'];
        $pendudukAwal['KK']   = $pendudukAwal['KK_L'] + $pendudukAwal['KK_P'];
        $kelahiran['KK'] = $kelahiran['KK_L'] + $kelahiran['KK_P'];
        $kematian        = [
            'WNI_L' => $mutasiPenduduk->where('kode_peristiwa', LogPenduduk::MATI)->where('penduduk.sex', JenisKelaminEnum::laki_laki)->where('penduduk.warganegara_id', WargaNegaraEnum::WNI)->count(),
            'WNI_P' => $mutasiPenduduk->where('kode_peristiwa', LogPenduduk::MATI)->where('penduduk.sex', JenisKelaminEnum::perempuan)->where('penduduk.warganegara_id', WargaNegaraEnum::WNI)->count(),
            'WNA_L' => $mutasiPenduduk->where('kode_peristiwa', LogPenduduk::MATI)->where('penduduk.sex', JenisKelaminEnum::laki_laki)->where('penduduk.warganegara_id', '!=', WargaNegaraEnum::WNI)->count(),
            'WNA_P' => $mutasiPenduduk->where('kode_peristiwa', LogPenduduk::MATI)->where('penduduk.sex', JenisKelaminEnum::perempuan)->where('penduduk.warganegara_id', '!=', WargaNegaraEnum::WNI)->count(),
            // keluarga
            'KK_L' => $keluargaPenduduk->where('id_peristiwa', LogKeluarga::KEPALA_KELUARGA_MATI)->where('keluarga.kepalaKeluarga.sex', JenisKelaminEnum::laki_laki)->count(),
            'KK_P' => $keluargaPenduduk->where('id_peristiwa', LogKeluarga::KEPALA_KELUARGA_MATI)->where('keluarga.kepalaKeluarga.sex', JenisKelaminEnum::perempuan)->count(),
        ];
        $kematian['KK'] = $kematian['KK_L'] + $kematian['KK_P'];
        $pendatang      = [
            'WNI_L' => $mutasiPenduduk->where('kode_peristiwa', LogPenduduk::BARU_PINDAH_MASUK)->where('penduduk.sex', JenisKelaminEnum::laki_laki)->where('penduduk.warganegara_id', WargaNegaraEnum::WNI)->count(),
            'WNI_P' => $mutasiPenduduk->where('kode_peristiwa', LogPenduduk::BARU_PINDAH_MASUK)->where('penduduk.sex', JenisKelaminEnum::perempuan)->where('penduduk.warganegara_id', WargaNegaraEnum::WNI)->count(),
            'WNA_L' => $mutasiPenduduk->where('kode_peristiwa', LogPenduduk::BARU_PINDAH_MASUK)->where('penduduk.sex', JenisKelaminEnum::laki_laki)->where('penduduk.warganegara_id', '!=', WargaNegaraEnum::WNI)->count(),
            'WNA_P' => $mutasiPenduduk->where('kode_peristiwa', LogPenduduk::BARU_PINDAH_MASUK)->where('penduduk.sex', JenisKelaminEnum::perempuan)->where('penduduk.warganegara_id', '!=', WargaNegaraEnum::WNI)->count(),
            // keluarga
            'KK_L' => $keluargaPenduduk->where('id_peristiwa', LogKeluarga::KEPALA_KELUARGA_PINDAH)->where('keluarga.kepalaKeluarga.sex', JenisKelaminEnum::laki_laki)->count(),
            'KK_P' => $keluargaPenduduk->where('id_peristiwa', LogKeluarga::KEPALA_KELUARGA_PINDAH)->where('keluarga.kepalaKeluarga.sex', JenisKelaminEnum::perempuan)->count(),
        ];
        $pendatang['KK'] = $pendatang['KK_L'] + $pendatang['KK_P'];
        $pindah          = [
            'WNI_L' => $mutasiPenduduk->where('kode_peristiwa', LogPenduduk::PINDAH_KELUAR)->where('penduduk.sex', JenisKelaminEnum::laki_laki)->where('penduduk.warganegara_id', WargaNegaraEnum::WNI)->count(),
            'WNI_P' => $mutasiPenduduk->where('kode_peristiwa', LogPenduduk::PINDAH_KELUAR)->where('penduduk.sex', JenisKelaminEnum::perempuan)->where('penduduk.warganegara_id', WargaNegaraEnum::WNI)->count(),
            'WNA_L' => $mutasiPenduduk->where('kode_peristiwa', LogPenduduk::PINDAH_KELUAR)->where('penduduk.sex', JenisKelaminEnum::laki_laki)->where('penduduk.warganegara_id', '!=', WargaNegaraEnum::WNI)->count(),
            'WNA_P' => $mutasiPenduduk->where('kode_peristiwa', LogPenduduk::PINDAH_KELUAR)->where('penduduk.sex', JenisKelaminEnum::perempuan)->where('penduduk.warganegara_id', '!=', WargaNegaraEnum::WNI)->count(),
            // keluarga
            'KK_L' => $keluargaPenduduk->where('id_peristiwa', LogKeluarga::KEPALA_KELUARGA_PINDAH)->where('keluarga.kepalaKeluarga.sex', JenisKelaminEnum::laki_laki)->count(),
            'KK_P' => $keluargaPenduduk->where('id_peristiwa', LogKeluarga::KEPALA_KELUARGA_PINDAH)->where('keluarga.kepalaKeluarga.sex', JenisKelaminEnum::perempuan)->count(),
        ];
        $pindah['KK'] = $pindah['KK_L'] + $pindah['KK_P'];
        $hilang       = [
            'WNI_L' => $mutasiPenduduk->where('kode_peristiwa', LogPenduduk::HILANG)->where('penduduk.sex', JenisKelaminEnum::laki_laki)->where('penduduk.warganegara_id', WargaNegaraEnum::WNI)->count(),
            'WNI_P' => $mutasiPenduduk->where('kode_peristiwa', LogPenduduk::HILANG)->where('penduduk.sex', JenisKelaminEnum::perempuan)->where('penduduk.warganegara_id', WargaNegaraEnum::WNI)->count(),
            'WNA_L' => $mutasiPenduduk->where('kode_peristiwa', LogPenduduk::HILANG)->where('penduduk.sex', JenisKelaminEnum::laki_laki)->where('penduduk.warganegara_id', '!=', WargaNegaraEnum::WNI)->count(),
            'WNA_P' => $mutasiPenduduk->where('kode_peristiwa', LogPenduduk::HILANG)->where('penduduk.sex', JenisKelaminEnum::perempuan)->where('penduduk.warganegara_id', '!=', WargaNegaraEnum::WNI)->count(),
            // keluarga
            'KK_L' => $keluargaPenduduk->where('id_peristiwa', LogKeluarga::KEPALA_KELUARGA_HILANG)->where('keluarga.kepalaKeluarga.sex', JenisKelaminEnum::laki_laki)->count(),
            'KK_P' => $keluargaPenduduk->where('id_peristiwa', LogKeluarga::KEPALA_KELUARGA_HILANG)->where('keluarga.kepalaKeluarga.sex', JenisKelaminEnum::perempuan)->count(),
        ];
        $hilang['KK']  = $hilang['KK_L'] + $hilang['KK_P'];
        $pendudukAkhir = [
            'WNI_L' => $pendudukAwal['WNI_L'] + $kelahiran['WNI_L'] + $pendatang['WNI_L'] - $pindah['WNI_L'] - $hilang['WNI_L'] - $kematian['WNI_L'],
            'WNI_P' => $pendudukAwal['WNI_P'] + $kelahiran['WNI_P'] + $pendatang['WNI_P'] - $pindah['WNI_P'] - $hilang['WNI_P'] - $kematian['WNI_P'],
            'WNA_L' => $pendudukAwal['WNA_L'] + $kelahiran['WNA_L'] + $pendatang['WNA_L'] - $pindah['WNA_L'] - $hilang['WNA_L'] - $kematian['WNA_L'],
            'WNA_P' => $pendudukAwal['WNA_P'] + $kelahiran['WNA_P'] + $pendatang['WNA_P'] - $pindah['WNA_P'] - $hilang['WNA_P'] - $kematian['WNA_P'],
            // keluarga
            'KK_L' => $pendudukAwal['KK_L'] + $kelahiran['KK_L'] + $pendatang['KK_L'] - $pindah['KK_L'] - $hilang['KK_L'] - $kematian['KK_L'],
            'KK_P' => $pendudukAwal['KK_P'] + $kelahiran['KK_P'] + $pendatang['KK_P'] - $pindah['KK_P'] - $hilang['KK_P'] - $kematian['KK_P'],
        ];
        $pendudukAkhir['KK'] = $pendudukAkhir['KK_L'] + $pendudukAkhir['KK_P'];

        return [
            'kelahiran'      => $kelahiran,
            'kematian'       => $kematian,
            'pendatang'      => $pendatang,
            'pindah'         => $pindah,
            'hilang'         => $hilang,
            'penduduk_awal'  => $pendudukAwal,
            'penduduk_akhir' => $pendudukAkhir,
            'rincian_pindah' => $this->rincian_pindah($mutasiPenduduk),
        ];
    }

    public function rincian_pindah($mutasiPenduduk)
    {
        $data              = [];
        $data['DESA_L']    = $mutasiPenduduk->where('ref_pindah', PindahEnum::DESA)->where('kode_peristiwa', LogPenduduk::PINDAH_KELUAR)->where('penduduk.sex', JenisKelaminEnum::laki_laki)->count();
        $data['DESA_P']    = $mutasiPenduduk->where('ref_pindah', PindahEnum::DESA)->where('kode_peristiwa', LogPenduduk::PINDAH_KELUAR)->where('penduduk.sex', JenisKelaminEnum::perempuan)->count();
        $data['DESA_KK_L'] = $mutasiPenduduk->where('ref_pindah', PindahEnum::DESA)->where('kode_peristiwa', LogPenduduk::PINDAH_KELUAR)->where('penduduk.sex', JenisKelaminEnum::laki_laki)->where('penduduk.kk_level', SHDKEnum::KEPALA_KELUARGA)->count();
        $data['DESA_KK_P'] = $mutasiPenduduk->where('ref_pindah', PindahEnum::DESA)->where('kode_peristiwa', LogPenduduk::PINDAH_KELUAR)->where('penduduk.sex', JenisKelaminEnum::perempuan)->where('penduduk.kk_level', SHDKEnum::KEPALA_KELUARGA)->count();

        $data['KEC_L']    = $mutasiPenduduk->where('ref_pindah', PindahEnum::KECAMATAN)->where('kode_peristiwa', LogPenduduk::PINDAH_KELUAR)->where('penduduk.sex', JenisKelaminEnum::laki_laki)->count();
        $data['KEC_P']    = $mutasiPenduduk->where('ref_pindah', PindahEnum::KECAMATAN)->where('kode_peristiwa', LogPenduduk::PINDAH_KELUAR)->where('penduduk.sex', JenisKelaminEnum::perempuan)->count();
        $data['KEC_KK_L'] = $mutasiPenduduk->where('ref_pindah', PindahEnum::KECAMATAN)->where('kode_peristiwa', LogPenduduk::PINDAH_KELUAR)->where('penduduk.sex', JenisKelaminEnum::laki_laki)->where('penduduk.kk_level', SHDKEnum::KEPALA_KELUARGA)->count();
        $data['KEC_KK_P'] = $mutasiPenduduk->where('ref_pindah', PindahEnum::KECAMATAN)->where('kode_peristiwa', LogPenduduk::PINDAH_KELUAR)->where('penduduk.sex', JenisKelaminEnum::perempuan)->where('penduduk.kk_level', SHDKEnum::KEPALA_KELUARGA)->count();

        $data['KAB_L']    = $mutasiPenduduk->where('ref_pindah', PindahEnum::KABUPATEN)->where('kode_peristiwa', LogPenduduk::PINDAH_KELUAR)->where('penduduk.sex', JenisKelaminEnum::laki_laki)->count();
        $data['KAB_P']    = $mutasiPenduduk->where('ref_pindah', PindahEnum::KABUPATEN)->where('kode_peristiwa', LogPenduduk::PINDAH_KELUAR)->where('penduduk.sex', JenisKelaminEnum::perempuan)->count();
        $data['KAB_KK_L'] = $mutasiPenduduk->where('ref_pindah', PindahEnum::KABUPATEN)->where('kode_peristiwa', LogPenduduk::PINDAH_KELUAR)->where('penduduk.sex', JenisKelaminEnum::laki_laki)->where('penduduk.kk_level', SHDKEnum::KEPALA_KELUARGA)->count();
        $data['KAB_KK_P'] = $mutasiPenduduk->where('ref_pindah', PindahEnum::KABUPATEN)->where('kode_peristiwa', LogPenduduk::PINDAH_KELUAR)->where('penduduk.sex', JenisKelaminEnum::perempuan)->where('penduduk.kk_level', SHDKEnum::KEPALA_KELUARGA)->count();

        $data['PROV_L']    = $mutasiPenduduk->where('ref_pindah', PindahEnum::PROVINSI)->where('kode_peristiwa', LogPenduduk::PINDAH_KELUAR)->where('penduduk.sex', JenisKelaminEnum::laki_laki)->count();
        $data['PROV_P']    = $mutasiPenduduk->where('ref_pindah', PindahEnum::PROVINSI)->where('kode_peristiwa', LogPenduduk::PINDAH_KELUAR)->where('penduduk.sex', JenisKelaminEnum::perempuan)->count();
        $data['PROV_KK_L'] = $mutasiPenduduk->where('ref_pindah', PindahEnum::PROVINSI)->where('kode_peristiwa', LogPenduduk::PINDAH_KELUAR)->where('penduduk.sex', JenisKelaminEnum::laki_laki)->where('penduduk.kk_level', SHDKEnum::KEPALA_KELUARGA)->count();
        $data['PROV_KK_P'] = $mutasiPenduduk->where('ref_pindah', PindahEnum::PROVINSI)->where('kode_peristiwa', LogPenduduk::PINDAH_KELUAR)->where('penduduk.sex', JenisKelaminEnum::perempuan)->where('penduduk.kk_level', SHDKEnum::KEPALA_KELUARGA)->count();

        $data['TOTAL_L']    = $data['DESA_L'] + $data['KEC_L'] + $data['KAB_L'] + $data['PROV_L'];
        $data['TOTAL_P']    = $data['DESA_P'] + $data['KEC_P'] + $data['KAB_P'] + $data['PROV_P'];
        $data['TOTAL_KK_L'] = $data['DESA_KK_L'] + $data['KEC_KK_L'] + $data['KAB_KK_L'] + $data['PROV_KK_L'];
        $data['TOTAL_KK_P'] = $data['DESA_KK_P'] + $data['KEC_KK_P'] + $data['KAB_KK_P'] + $data['PROV_KK_P'];

        return $data;
    }

    public function sumberData()
    {
        return QueryBuilder::for(Penduduk::awalBulan(request()->input('filter')['tahun'], request()->input('filter')['bulan'])->filterWilayah())
            ->allowedFilters([
                AllowedFilter::exact('*'),
                AllowedFilter::exact('config_id'),
                AllowedFilter::callback('rincian', function($query, $value){
                    // switch (strtolower($value)) {
                    //     case 'awal':
                            
                    //         break;
                    // }
                }),
                AllowedFilter::callback('tipe', function($query, $value){
                    switch($value){
                        case 'wni_l';
                            $query->whereIn('warganegara_id', [WargaNegaraEnum::WNI])
                            ->whereSex(JenisKelaminEnum::laki_laki);
                            break;
                        case 'wni_p';
                            $query->whereIn('warganegara_id', [WargaNegaraEnum::WNI])
                            ->whereSex(JenisKelaminEnum::perempuan);
                            break;
                        case 'wna_l';
                            $query->whereIn('warganegara_id',  [WargaNegaraEnum::WNA, WargaNegaraEnum::DUAKEWARGANEGARAAN])
                            ->whereSex(JenisKelaminEnum::laki_laki);
                            break;
                        case 'wna_p';
                            $query->whereIn('warganegara_id',  [WargaNegaraEnum::WNA, WargaNegaraEnum::DUAKEWARGANEGARAAN])
                            ->whereSex(JenisKelaminEnum::perempuan);
                            break;
                        case 'jml_l';
                            $query->whereSex(JenisKelaminEnum::laki_laki);
                            break;
                        case 'jml_p';
                            $query->whereSex(JenisKelaminEnum::perempuan);
                            break;
                        case 'jml';
                            $query->where('kk_level', SHDKEnum::KEPALA_KELUARGA)
                            ->whereNotNull('id_kk')
                            ->whereIn('warganegara_id',  [WargaNegaraEnum::WNA, WargaNegaraEnum::DUAKEWARGANEGARAAN])
                            ->whereSex(JenisKelaminEnum::perempuan);
                            break;
                        case 'kk';
                            $query->where('kk_level', SHDKEnum::KEPALA_KELUARGA)
                            ->whereNotNull('id_kk');
                            break;
                        case 'kk_l';
                            $query->where('kk_level', SHDKEnum::KEPALA_KELUARGA)
                            ->whereNotNull('id_kk')
                            ->whereSex(JenisKelaminEnum::laki_laki);
                            break;
                        case 'kk_p';
                            $query->where('kk_level', SHDKEnum::KEPALA_KELUARGA)
                            ->whereNotNull('id_kk')
                            ->whereSex(JenisKelaminEnum::perempuan);
                            break;
                        
                    }
                }),
                AllowedFilter::callback('tahun', function($query, $value){
                    
                }),
                AllowedFilter::callback('bulan', function($query, $value){
                    
                }),
                AllowedFilter::callback('kode_kecamatan', function($query, $value){
                    
                })
                // AllowedFilter::callback('search', function ($query, $value) {
                //     $query->where(function ($query) use ($value) {
                //         $query->where('template_uuid', 'like', "%{$value}%");
                //         $query->orWhere('keuangan_template.uraian', 'like', "%{$value}%");
                //         $query->orWhere('tahun', 'like', "{$value}%");
                //         $query->orWhere('config.nama_desa', 'like', "%{$value}%");
                //     });
                // }),
            ])
            // ->allowedSorts([
            //     'config_id',
            //     'tahun',
            //     'anggaran',
            //     'realisasi',
            //     'template_uuid',
            //     'keuangan_template.uraian',
            //     'config.nama_desa',
            // ])
            ->jsonPaginate();
    }
}
