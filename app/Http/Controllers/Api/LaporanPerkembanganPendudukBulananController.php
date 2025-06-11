<?php

namespace App\Http\Controllers\Api;

use App\Enums\PindahEnum;
use App\Enums\WargaNegaraEnum;
use App\Http\Repository\LaporanPerkembanganPendudukBulananRepository;
use App\Models\Config;
use App\Models\Enums\JenisKelaminEnum;
use App\Models\Enums\SHDKEnum;
use App\Models\LogKeluarga;
use App\Models\LogPenduduk;
use App\Models\Penduduk;
use App\Models\Traits\QueryBuilderTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;

class LaporanPerkembanganPendudukBulananController extends Controller
{
    use QueryBuilderTrait;

    protected $rincian;

    protected $tipe;

    protected $tahun;

    protected $bulan;

    protected $kode_kabupaten;

    protected $kode_kecamatan;

    protected $kode_desa;

    public function __construct(
        protected LaporanPerkembanganPendudukBulananRepository $penduduk
    ) {

        $this->rincian = request()->input('filter')['rincian'] ?? null;
        $this->tipe = request()->input('filter')['tipe'] ?? null;
        $this->tahun = request()->input('filter')['tahun'] ?? null;
        $this->bulan = request()->input('filter')['bulan'] ?? null;
        $this->kode_kecamatan = request()->input('filter')['kode_kecamatan'] ?? null;
        $this->kode_kabupaten = request()->input('filter')['kode_kabupaten'] ?? null;
        $this->kode_desa = request()->input('filter')['config_desa'] ?? null;

    }

    public function index()
    {
        // Session::put('config_id', Config::where('kode_kabupaten', $this->kode_kabupaten)->first()->id);
        $bulanDepan        = Carbon::create($this->tahun, $this->bulan)->addMonth();

        $bulanFix          = str_pad($this->bulan, 2, '0', STR_PAD_LEFT);
        $pendudukAwalBulan = Penduduk::awalBulan($bulanDepan->format('Y'), $bulanFix)->filterWilayah()->get();

        $pendudukAwal      = [
            'WNI_L' => $pendudukAwalBulan->where('sex', JenisKelaminEnum::laki_laki)->where('warganegara_id', WargaNegaraEnum::WNI)->count(),
            'WNI_P' => $pendudukAwalBulan->where('sex', JenisKelaminEnum::perempuan)->where('warganegara_id', WargaNegaraEnum::WNI)->count(),
            'WNA_L' => $pendudukAwalBulan->where('sex', JenisKelaminEnum::laki_laki)->where('warganegara_id', '!=', WargaNegaraEnum::WNI)->count(),
            'WNA_P' => $pendudukAwalBulan->where('sex', JenisKelaminEnum::perempuan)->where('warganegara_id', '!=', WargaNegaraEnum::WNI)->count(),
            // keluarga
            'KK_L' => $pendudukAwalBulan->where('sex', JenisKelaminEnum::laki_laki)->where('kk_level', SHDKEnum::KEPALA_KELUARGA)->whereNotNull('id_kk')->count(),
            'KK_P' => $pendudukAwalBulan->where('sex', JenisKelaminEnum::perempuan)->where('kk_level', SHDKEnum::KEPALA_KELUARGA)->whereNotNull('id_kk')->count(),
        ];

        $mutasiPenduduk = LogPenduduk::filterWilayah()->with(['penduduk' => static fn ($q) => $q->withOnly([])])->whereYear('tgl_lapor', $this->tahun)->whereMonth('tgl_lapor', $this->bulan)->get();
        // KELUARGA_BARU_DATANG
        $keluargaPenduduk = LogKeluarga::filterWilayah()->with(['keluarga.kepalaKeluarga' => static fn ($q) => $q->withOnly([])])->whereYear('tgl_peristiwa', $this->tahun)->whereMonth('tgl_peristiwa', $this->bulan)->get();

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

        return response()->json([
            'data' => [
                'kelahiran'      => $kelahiran,
                'kematian'       => $kematian,
                'pendatang'      => $pendatang,
                'pindah'         => $pindah,
                'hilang'         => $hilang,
                'penduduk_awal'  => $pendudukAwal,
                'penduduk_akhir' => $pendudukAkhir,
                'rincian_pindah' => $this->rincian_pindah($mutasiPenduduk),
            ]
        ]);
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

    // public function sumberData()
    // {
    //     return $this->fractal($this->penduduk->sumberData(), function($item){
    //         dd($item);
    //     }, 'laporan bulanan')->respond();
    // }

    public function sumberData()
    {
        $rincian = $this->rincian;
        $tipe = $this->tipe;
        $bulan = $this->bulan;
        $tahun = $this->tahun;
        $kode_kabupaten = $this->kode_kabupaten;
        $kode_kecamatan = $this->kode_kecamatan;
        $kode_desa = $this->kode_desa;

        $data         = [];
        $keluarga     = ['kk', 'kk_l', 'kk_p'];
        $titlePeriode = strtoupper(bulan($this->bulan)) . ' ' . $this->tahun;
        $filter       = [];

        $filter['warganegara_id'] = null;
        $filter['kk_level'] = null;
        $filter['sex']      = null;

        switch($this->tipe) {
            case 'wni_l':
                $filter['sex']            = JenisKelaminEnum::laki_laki;
                $filter['warganegara_id'] = [WargaNegaraEnum::WNI];
                break;

            case 'wni_p':
                $filter['sex']            = JenisKelaminEnum::perempuan;
                $filter['warganegara_id'] = [WargaNegaraEnum::WNI];
                break;

            case 'wna_l':
                $filter['sex']            = JenisKelaminEnum::laki_laki;
                $filter['warganegara_id'] = [WargaNegaraEnum::WNA, WargaNegaraEnum::DUAKEWARGANEGARAAN];
                break;

            case 'wna_p':
                $filter['sex']            = JenisKelaminEnum::perempuan;
                $filter['warganegara_id'] = [WargaNegaraEnum::WNA, WargaNegaraEnum::DUAKEWARGANEGARAAN];
                break;

            case 'jml_l':
                $filter['sex'] = JenisKelaminEnum::laki_laki;
                break;

            case 'jml_p':
                $filter['sex'] = JenisKelaminEnum::perempuan;
                break;

            case 'jml':
                $filter['kk_level'] = SHDKEnum::KEPALA_KELUARGA;
                $filter['sex'] = JenisKelaminEnum::perempuan;
                $filter['warganegara_id'] = [WargaNegaraEnum::WNA, WargaNegaraEnum::DUAKEWARGANEGARAAN];
                break;

            case 'kk':
                $filter['kk_level'] = SHDKEnum::KEPALA_KELUARGA;
                break;

            case 'kk_l':
                $filter['kk_level'] = SHDKEnum::KEPALA_KELUARGA;
                $filter['sex']      = JenisKelaminEnum::laki_laki;
                break;

            case 'kk_p':
                $filter['kk_level'] = SHDKEnum::KEPALA_KELUARGA;
                $filter['sex']      = JenisKelaminEnum::perempuan;
                break;
        }

        switch (strtolower($rincian)) {
            case 'awal':
                $data = [
                    'title' => 'PENDUDUK/KELUARGA AWAL BULAN ' . $titlePeriode,
                    'main'  => Penduduk::awalBulan($tahun, $bulan)->filterWilayah()->when($filter['kk_level'], static fn ($q) => $q->where('kk_level', $filter['kk_level'])->whereNotNull('id_kk'))->when($filter['warganegara_id'], static fn ($q) => $q->whereIn('warganegara_id', $filter['warganegara_id']))->when($filter['sex'], static fn ($q) => $q->whereSex($filter['sex']))->get(),
                ];

                break;

            case 'lahir':
                $data = [
                    'title' => (in_array($tipe, $keluarga) ? 'KELUARGA BARU BULAN ' : 'KELAHIRAN BULAN ') . $titlePeriode,
                    'main'  => Penduduk::filterWilayah()->withOnly([])
                        ->when(
                            $filter['kk_level'],
                            static fn ($q) => $q->where('kk_level', $filter['kk_level'])->whereNotNull('id_kk')
                                ->whereHas(
                                    'keluarga.logKeluarga',
                                    static fn ($q) => $q->where('id_peristiwa', LogKeluarga::KELUARGA_BARU)->whereYear('tgl_peristiwa', $tahun)->whereMonth('tgl_peristiwa', $bulan)
                                ),
                            static function ($q) use ($tahun, $bulan) {
                                $q->whereHas(
                                    'log',
                                    static fn ($q) => $q->whereKodePeristiwa(LogPenduduk::BARU_LAHIR)->whereYear('tgl_lapor', $tahun)->whereMonth('tgl_lapor', $bulan)
                                );
                            }
                        )
                        ->when($filter['warganegara_id'], static fn ($q) => $q->whereIn('warganegara_id', $filter['warganegara_id']))
                        ->when($filter['sex'], static fn ($q) => $q->whereSex($filter['sex']))->get(),
                ];
                break;

            case 'mati':
                $data = [
                    'title' => 'KEMATIAN BULAN ' . $titlePeriode,
                    'main'  => Penduduk::filterWilayah()->withOnly([])
                        ->when(
                            $filter['kk_level'],
                            static fn ($q) => $q->where('kk_level', $filter['kk_level'])->whereNotNull('id_kk')
                                ->whereHas(
                                    'keluarga.logKeluarga',
                                    static fn ($q) => $q->where('id_peristiwa', LogKeluarga::KEPALA_KELUARGA_MATI)->whereYear('tgl_peristiwa', $tahun)->whereMonth('tgl_peristiwa', $bulan)
                                ),
                            static function ($q) use ($tahun, $bulan) {
                                $q->whereHas(
                                    'log',
                                    static fn ($q) => $q->whereKodePeristiwa(LogPenduduk::MATI)->whereYear('tgl_lapor', $tahun)->whereMonth('tgl_lapor', $bulan)
                                );
                            }
                        )
                        ->when($filter['warganegara_id'], static fn ($q) => $q->whereIn('warganegara_id', $filter['warganegara_id']))->when($filter['sex'], static fn ($q) => $q->whereSex($filter['sex']))->get(),
                ];
                break;

            case 'datang':
                $data = [
                    'title' => 'PENDATANG BULAN ' . $titlePeriode,
                    'main'  => Penduduk::filterWilayah()->withOnly([])
                        ->when(
                            $filter['kk_level'],
                            static fn ($q) => $q->where('kk_level', $filter['kk_level'])->whereNotNull('id_kk')
                                ->whereHas(
                                    'keluarga.logKeluarga',
                                    static fn ($q) => $q->where('id_peristiwa', LogKeluarga::KELUARGA_BARU_DATANG)->whereYear('tgl_peristiwa', $tahun)->whereMonth('tgl_peristiwa', $bulan)
                                ),
                            static function ($q) use ($tahun, $bulan) {
                                $q->whereHas(
                                    'log',
                                    static fn ($q) => $q->whereKodePeristiwa(LogPenduduk::BARU_PINDAH_MASUK)->whereYear('tgl_lapor', $tahun)->whereMonth('tgl_lapor', $bulan)
                                );
                            }
                        )
                        ->when($filter['warganegara_id'], static fn ($q) => $q->whereIn('warganegara_id', $filter['warganegara_id']))->when($filter['sex'], static fn ($q) => $q->whereSex($filter['sex']))->get(),
                ];
                break;

            case 'pindah':
                $data = [
                    'title' => 'PINDAH/KELUAR PERGI BULAN ' . $titlePeriode,
                    'main'  => Penduduk::filterWilayah()->withOnly([])
                        ->when(
                            $filter['kk_level'],
                            static fn ($q) => $q->where('kk_level', $filter['kk_level'])->whereNotNull('id_kk')
                                ->whereHas(
                                    'keluarga.logKeluarga',
                                    static fn ($q) => $q->where('id_peristiwa', LogKeluarga::KEPALA_KELUARGA_PINDAH)->whereYear('tgl_peristiwa', $tahun)->whereMonth('tgl_peristiwa', $bulan)
                                ),
                            static function ($q) use ($tahun, $bulan) {
                                $q->whereHas(
                                    'log',
                                    static fn ($q) => $q->whereKodePeristiwa(LogPenduduk::PINDAH_KELUAR)->whereYear('tgl_lapor', $tahun)->whereMonth('tgl_lapor', $bulan)
                                );
                            }
                        )
                        ->when($filter['warganegara_id'], static fn ($q) => $q->whereIn('warganegara_id', $filter['warganegara_id']))->when($filter['sex'], static fn ($q) => $q->whereSex($filter['sex']))->get(),
                ];
                break;

            case 'hilang':
                $data = [
                    'title' => 'PENDUDUK HILANG BULAN ' . $titlePeriode,
                    'main'  => Penduduk::filterWilayah()->withOnly([])
                        ->when(
                            $filter['kk_level'],
                            static fn ($q) => $q->where('kk_level', $filter['kk_level'])->whereNotNull('id_kk')
                                ->whereHas(
                                    'keluarga.logKeluarga',
                                    static fn ($q) => $q->where('id_peristiwa', LogKeluarga::KEPALA_KELUARGA_HILANG)->whereYear('tgl_peristiwa', $tahun)->whereMonth('tgl_peristiwa', $bulan)
                                ),
                            static function ($q) use ($tahun, $bulan) {
                                $q->whereHas(
                                    'log',
                                    static fn ($q) => $q->whereKodePeristiwa(LogPenduduk::HILANG)->whereYear('tgl_lapor', $tahun)->whereMonth('tgl_lapor', $bulan)
                                );
                            }
                        )
                        ->when($filter['warganegara_id'], static fn ($q) => $q->whereIn('warganegara_id', $filter['warganegara_id']))->when($filter['sex'], static fn ($q) => $q->whereSex($filter['sex']))->get(),
                ];
                break;

            case 'akhir':
                $bulanDepan = Carbon::createFromDate($tahun, $bulan)->addMonth();
                $data       = [
                    'title' => 'PENDUDUK/KELUARGA AKHIR BULAN ' . $titlePeriode,
                    'main'  => Penduduk::awalBulan($bulanDepan->format('Y'), $bulanDepan->format('m'))->filterWilayah()->when($filter['kk_level'], static fn ($q) => $q->where('kk_level', $filter['kk_level'])->whereNotNull('id_kk'))->when($filter['warganegara_id'], static fn ($q) => $q->whereIn('warganegara_id', $filter['warganegara_id']))->when($filter['sex'], static fn ($q) => $q->whereSex($filter['sex']))->get(),
                ];
                break;
        }
        // switch($tipe) {
        //     case 'wni_l':
        //         $filter['sex']            = JenisKelaminEnum::laki_laki;
        //         $filter['warganegara_id'] = [WargaNegaraEnum::WNI];
        //         break;

        //     case 'wni_p':
        //         $filter['sex']            = JenisKelaminEnum::perempuan;
        //         $filter['warganegara_id'] = [WargaNegaraEnum::WNI];
        //         break;

        //     case 'wna_l':
        //         $filter['sex']            = JenisKelaminEnum::laki_laki;
        //         $filter['warganegara_id'] = [WargaNegaraEnum::WNA, WargaNegaraEnum::DUAKEWARGANEGARAAN];
        //         break;

        //     case 'wna_p':
        //         $filter['sex']            = JenisKelaminEnum::perempuan;
        //         $filter['warganegara_id'] = [WargaNegaraEnum::WNA, WargaNegaraEnum::DUAKEWARGANEGARAAN];
        //         break;

        //     case 'jml_l':
        //         $filter['sex'] = JenisKelaminEnum::laki_laki;
        //         break;

        //     case 'jml_p':
        //         $filter['sex'] = JenisKelaminEnum::perempuan;
        //         break;

        //     case 'kk':
        //         $filter['kk_level'] = SHDKEnum::KEPALA_KELUARGA;
        //         break;

        //     case 'kk_l':
        //         $filter['kk_level'] = SHDKEnum::KEPALA_KELUARGA;
        //         $filter['sex']      = JenisKelaminEnum::laki_laki;
        //         break;

        //     case 'kk_p':
        //         $filter['kk_level'] = SHDKEnum::KEPALA_KELUARGA;
        //         $filter['sex']      = JenisKelaminEnum::perempuan;
        //         break;
        // }

        // switch (strtolower($rincian)) {
        //     case 'awal':
        //         $data = [
        //             'title' => 'PENDUDUK/KELUARGA AWAL BULAN ' . $titlePeriode,
        //             'main'  => Penduduk::awalBulan($tahun, $bulan)->when($filter['kk_level'], static fn ($q) => $q->where('kk_level', $filter['kk_level'])->whereNotNull('id_kk'))->when($filter['warganegara_id'], static fn ($q) => $q->whereIn('warganegara_id', $filter['warganegara_id']))->when($filter['sex'], static fn ($q) => $q->whereSex($filter['sex']))->get(),
        //         ];
        //         break;

        //     case 'lahir':
        //         $data = [
        //             'title' => (in_array($tipe, $keluarga) ? 'KELUARGA BARU BULAN ' : 'KELAHIRAN BULAN ') . $titlePeriode,
        //             'main'  => Penduduk::withOnly([])
        //                 ->when(
        //                     $filter['kk_level'],
        //                     static fn ($q) => $q->where('kk_level', $filter['kk_level'])->whereNotNull('id_kk')
        //                         ->whereHas(
        //                             'keluarga.logKeluarga',
        //                             static fn ($q) => $q->where('id_peristiwa', LogKeluarga::KELUARGA_BARU)->whereYear('tgl_peristiwa', $tahun)->whereMonth('tgl_peristiwa', $bulan)
        //                         ),
        //                     static function ($q) use ($tahun, $bulan) {
        //                         $q->whereHas(
        //                             'log',
        //                             static fn ($q) => $q->whereKodePeristiwa(LogPenduduk::BARU_LAHIR)->whereYear('tgl_lapor', $tahun)->whereMonth('tgl_lapor', $bulan)
        //                         );
        //                     }
        //                 )
        //                 ->when($filter['warganegara_id'], static fn ($q) => $q->whereIn('warganegara_id', $filter['warganegara_id']))
        //                 ->when($filter['sex'], static fn ($q) => $q->whereSex($filter['sex']))->get(),
        //         ];
        //         break;

        //     case 'mati':
        //         $data = [
        //             'title' => 'KEMATIAN BULAN ' . $titlePeriode,
        //             'main'  => Penduduk::withOnly([])
        //                 ->when(
        //                     $filter['kk_level'],
        //                     static fn ($q) => $q->where('kk_level', $filter['kk_level'])->whereNotNull('id_kk')
        //                         ->whereHas(
        //                             'keluarga.logKeluarga',
        //                             static fn ($q) => $q->where('id_peristiwa', LogKeluarga::KEPALA_KELUARGA_MATI)->whereYear('tgl_peristiwa', $tahun)->whereMonth('tgl_peristiwa', $bulan)
        //                         ),
        //                     static function ($q) use ($tahun, $bulan) {
        //                         $q->whereHas(
        //                             'log',
        //                             static fn ($q) => $q->whereKodePeristiwa(LogPenduduk::MATI)->whereYear('tgl_lapor', $tahun)->whereMonth('tgl_lapor', $bulan)
        //                         );
        //                     }
        //                 )
        //                 ->when($filter['warganegara_id'], static fn ($q) => $q->whereIn('warganegara_id', $filter['warganegara_id']))->when($filter['sex'], static fn ($q) => $q->whereSex($filter['sex']))->get(),
        //         ];
        //         break;

        //     case 'datang':
        //         $data = [
        //             'title' => 'PENDATANG BULAN ' . $titlePeriode,
        //             'main'  => Penduduk::withOnly([])
        //                 ->when(
        //                     $filter['kk_level'],
        //                     static fn ($q) => $q->where('kk_level', $filter['kk_level'])->whereNotNull('id_kk')
        //                         ->whereHas(
        //                             'keluarga.logKeluarga',
        //                             static fn ($q) => $q->where('id_peristiwa', LogKeluarga::KELUARGA_BARU_DATANG)->whereYear('tgl_peristiwa', $tahun)->whereMonth('tgl_peristiwa', $bulan)
        //                         ),
        //                     static function ($q) use ($tahun, $bulan) {
        //                         $q->whereHas(
        //                             'log',
        //                             static fn ($q) => $q->whereKodePeristiwa(LogPenduduk::BARU_PINDAH_MASUK)->whereYear('tgl_lapor', $tahun)->whereMonth('tgl_lapor', $bulan)
        //                         );
        //                     }
        //                 )
        //                 ->when($filter['warganegara_id'], static fn ($q) => $q->whereIn('warganegara_id', $filter['warganegara_id']))->when($filter['sex'], static fn ($q) => $q->whereSex($filter['sex']))->get(),
        //         ];
        //         break;

        //     case 'pindah':
        //         $data = [
        //             'title' => 'PINDAH/KELUAR PERGI BULAN ' . $titlePeriode,
        //             'main'  => Penduduk::withOnly([])
        //                 ->when(
        //                     $filter['kk_level'],
        //                     static fn ($q) => $q->where('kk_level', $filter['kk_level'])->whereNotNull('id_kk')
        //                         ->whereHas(
        //                             'keluarga.logKeluarga',
        //                             static fn ($q) => $q->where('id_peristiwa', LogKeluarga::KEPALA_KELUARGA_PINDAH)->whereYear('tgl_peristiwa', $tahun)->whereMonth('tgl_peristiwa', $bulan)
        //                         ),
        //                     static function ($q) use ($tahun, $bulan) {
        //                         $q->whereHas(
        //                             'log',
        //                             static fn ($q) => $q->whereKodePeristiwa(LogPenduduk::PINDAH_KELUAR)->whereYear('tgl_lapor', $tahun)->whereMonth('tgl_lapor', $bulan)
        //                         );
        //                     }
        //                 )
        //                 ->when($filter['warganegara_id'], static fn ($q) => $q->whereIn('warganegara_id', $filter['warganegara_id']))->when($filter['sex'], static fn ($q) => $q->whereSex($filter['sex']))->get(),
        //         ];
        //         break;

        //     case 'hilang':
        //         $data = [
        //             'title' => 'PENDUDUK HILANG BULAN ' . $titlePeriode,
        //             'main'  => Penduduk::withOnly([])
        //                 ->when(
        //                     $filter['kk_level'],
        //                     static fn ($q) => $q->where('kk_level', $filter['kk_level'])->whereNotNull('id_kk')
        //                         ->whereHas(
        //                             'keluarga.logKeluarga',
        //                             static fn ($q) => $q->where('id_peristiwa', LogKeluarga::KEPALA_KELUARGA_HILANG)->whereYear('tgl_peristiwa', $tahun)->whereMonth('tgl_peristiwa', $bulan)
        //                         ),
        //                     static function ($q) use ($tahun, $bulan) {
        //                         $q->whereHas(
        //                             'log',
        //                             static fn ($q) => $q->whereKodePeristiwa(LogPenduduk::HILANG)->whereYear('tgl_lapor', $tahun)->whereMonth('tgl_lapor', $bulan)
        //                         );
        //                     }
        //                 )
        //                 ->when($filter['warganegara_id'], static fn ($q) => $q->whereIn('warganegara_id', $filter['warganegara_id']))->when($filter['sex'], static fn ($q) => $q->whereSex($filter['sex']))->get(),
        //         ];
        //         break;

        //     case 'akhir':
        //         $bulanDepan = Carbon::createFromDate($tahun, $bulan)->addMonth();
        //         $data       = [
        //             'title' => 'PENDUDUK/KELUARGA AKHIR BULAN ' . $titlePeriode,
        //             'main'  => Penduduk::awalBulan($bulanDepan->format('Y'), $bulanDepan->format('m'))->when($filter['kk_level'], static fn ($q) => $q->where('kk_level', $filter['kk_level'])->whereNotNull('id_kk'))->when($filter['warganegara_id'], static fn ($q) => $q->whereIn('warganegara_id', $filter['warganegara_id']))->when($filter['sex'], static fn ($q) => $q->whereSex($filter['sex']))->get(),
        //         ];
        //         break;
        // }

        return response()->json([
            'data' => $data
        ]);
    }

    public function LogPenduduk()
    {
        $result = LogPenduduk::min('tgl_lapor');
        return response()->json([
            'data' => $result
        ]);
    }

    public function penduduk(Request $request)
    {
        $this->validate($request, [
            'bulan' => 'required|int',
            'tahun' => 'required|int',
        ]);
        

        $data['kelahiran']         = $this->mutasi_peristiwa(1);
        $data['kematian']          = $this->mutasi_peristiwa(2);
        $data['pendatang']         = $this->mutasi_peristiwa(5);
        $data['pindah']            = $this->mutasi_peristiwa(3);
        $data['hilang']            = $this->mutasi_peristiwa(4);
        $data['penduduk_awal']     = $this->penduduk_awal($request);

        $data['penduduk_akhir']     = [];
        $kategori = ['WNI_L', 'WNI_P', 'WNA_L', 'WNA_P', 'KK', 'KK_L', 'KK_P'];

        foreach ($kategori as $k) {
            $data['penduduk_akhir'][$k] = $data['penduduk_awal'][$k] +  $data['kelahiran'][$k] + $data['pendatang'][$k] -  $data['kematian'][$k] -  $data['pindah'][$k] -  $data['hilang'][$k];
        }

        return $data;
    }

    private function penduduk_awal($request)
    {
        $bln      = $request->bulan;
        $thn      = $request->tahun;
        $pad_bln  = str_pad($bln, 2, '0', STR_PAD_LEFT);
        $lastDate = Carbon::createFromDate($thn, $bln, 1)->endOfMonth();
        $startDate = Carbon::createFromDate($thn, $bln, 1)->startOfMonth();
        $id_config = identitas('id');

        // penduduk awal

        $penduduk_mutasi_sql =  DB::connection('openkab')->table('tweb_penduduk AS p')
            ->join(DB::raw('(SELECT MAX(id) as max_id, id_pend FROM log_penduduk WHERE tgl_lapor < "' .  $lastDate->toDateString() . '" GROUP BY id_pend) log_max'), 'log_max.id_pend', '=', 'p.id')
            ->join('log_penduduk as l', function ($join) {
                $join->on('log_max.max_id', '=', 'l.id')
                    ->whereNotIn('l.kode_peristiwa', [2, 3, 4])
                    ->where('l.config_id', '=', identitas('id'));
            })
            ->select('p.*', 'l.kode_peristiwa');

        $penduduk_mutasi = collect(DB::connection('openkab')->table(DB::raw('(' . $this->getCompiledQueryWithBindings($penduduk_mutasi_sql) . ') as m'))
            ->selectRaw('sum(case when sex = 1 and warganegara_id <> 2 and kode_peristiwa in (1,5) then 1 else 0 end) AS WNI_L_PLUS')
            ->selectRaw('sum(case when sex = 2 and warganegara_id <> 2 and kode_peristiwa in (1,5) then 1 else 0 end) AS WNI_P_PLUS')
            ->selectRaw('sum(case when sex = 1 and warganegara_id = 2 and kode_peristiwa in (1,5) then 1 else 0 end) AS WNA_L_PLUS')
            ->selectRaw('sum(case when sex = 2 and warganegara_id = 2 and kode_peristiwa in (1,5) then 1 else 0 end) AS WNA_P_PLUS')
            ->selectRaw('sum(case when sex = 1 and warganegara_id <> 2 and kode_peristiwa in (2, 3, 4) then 1 else 0 end) AS WNI_L_MINUS')
            ->selectRaw('sum(case when sex = 2 and warganegara_id <> 2 and kode_peristiwa in (2, 3, 4) then 1 else 0 end) AS WNI_P_MINUS')
            ->selectRaw('sum(case when sex = 1 and warganegara_id = 2 and kode_peristiwa in (2, 3, 4) then 1 else 0 end) AS WNA_L_MINUS')
            ->selectRaw('sum(case when sex = 2 and warganegara_id = 2 and kode_peristiwa in (2, 3, 4) then 1 else 0 end) AS WNA_P_MINUS')
            ->first())->toArray();

        $keluarga_mutasi_sql = DB::connection('openkab')->table('log_keluarga as l')
        ->join(DB::raw('(SELECT MAX(id) as id FROM log_keluarga WHERE id_kk IS NOT NULL AND config_id = 1 AND tgl_peristiwa < "'.$startDate->toDateString().'" GROUP BY id_kk) log_max_keluarga'), 'log_max_keluarga.id', '=', 'l.id')
        ->join('tweb_keluarga as k', 'k.id', '=', 'l.id_kk')
        ->join(DB::raw('(SELECT MAX(id) as max_id, id_pend FROM log_penduduk WHERE tgl_lapor < "' .  $lastDate->toDateString() . '" AND config_id = 1 GROUP BY id_pend) log_max'), 'log_max.id_pend', '=', 'k.nik_kepala')
        ->join('log_penduduk as lp', function ($join) {
            $join->on('log_max.max_id', '=', 'lp.id')
                ->whereNotIn('lp.kode_peristiwa', [2, 3, 4]);
        })
        ->join('tweb_penduduk as p', function ($join) {
            $join->on('lp.id_pend', '=', 'p.id')
                ->where('p.kk_level', '=', 1);
        })
        ->select('p.*', 'l.id_peristiwa')
        ->where('l.config_id', '=', $id_config)
        ->whereRaw('l.tgl_peristiwa < "'.$startDate->toDateString().'"')
        ->whereNotIn('l.id_peristiwa', [2, 3, 4]);

        $keluarga_mutasi = collect(DB::connection('openkab')->table(DB::raw('(' . $this->getCompiledQueryWithBindings($keluarga_mutasi_sql) . ') as m'))
            ->selectRaw('sum(case when id_peristiwa in (1, 12) then 1 else 0 end) AS KK_PLUS')
            ->selectRaw('sum(case when sex = 1 and id_peristiwa in (1, 12) then 1 else 0 end) AS KK_L_PLUS')
            ->selectRaw('sum(case when sex = 2 and id_peristiwa in (1, 12) then 1 else 0 end) AS KK_P_PLUS')
            ->selectRaw('sum(case when id_peristiwa in (2, 3, 4) then 1 else 0 end) AS KK_MINUS')
            ->selectRaw('sum(case when sex = 1 and id_peristiwa in (2, 3, 4) then 1 else 0 end) AS KK_L_MINUS')
            ->selectRaw('sum(case when sex = 2 and id_peristiwa in (2, 3, 4) then 1 else 0 end) AS KK_P_MINUS')
            ->first())->toArray();

        $penduduk_mutasi = array_merge($penduduk_mutasi, $keluarga_mutasi);

        $data     = [];
        $kategori = ['WNI_L', 'WNI_P', 'WNA_L', 'WNA_P', 'KK', 'KK_L', 'KK_P'];

        foreach ($kategori as $k) {
            $data[$k] = $penduduk_mutasi[$k . '_PLUS'] - $penduduk_mutasi[$k . '_MINUS'];
        }

        return $data;
    }

    private function kelahiran($request)
    {
        $lahir = $this->mutasi_peristiwa(1);
    }


    private function mutasi_peristiwa(int $peristiwa, $rincian = 0, $tipe = 0)
    {
        // Jika rincian dan tipe di definisikan, maka akan masuk kedetil laporan
        if ($rincian && $tipe) {
            return $this->rincian_peristiwa($peristiwa, $tipe);
        }

        $bln      = request()->bulan;
        $thn      = request()->tahun;
        $id_config = identitas('id');

        // Mutasi penduduk
        $mutasi_pada_bln_thn_sql = DB::connection('openkab')->table('log_penduduk as l')
            ->join('tweb_penduduk as p', 'l.id_pend', '=', 'p.id')
            ->select('p.*', 'l.ref_pindah', 'l.kode_peristiwa')
            ->where('l.config_id', '=', $id_config)
            ->whereYear('l.tgl_lapor', '=', $thn)
            ->whereMonth('l.tgl_lapor', '=', $bln)
            ->where('l.kode_peristiwa', '=', $peristiwa);
        $mutasi_pada_bln_thn = $this->getCompiledQueryWithBindings($mutasi_pada_bln_thn_sql);

        $data = collect(DB::connection('openkab')->table(DB::raw('(' . $mutasi_pada_bln_thn . ') as m'))
            ->selectRaw('sum(case when sex = 1 and warganegara_id <> 2 then 1 else 0 end) AS WNI_L')
            ->selectRaw('sum(case when sex = 2 and warganegara_id <> 2 then 1 else 0 end) AS WNI_P')
            ->selectRaw('sum(case when sex = 1 and warganegara_id = 2 then 1 else 0 end) AS WNA_L')
            ->selectRaw('sum(case when sex = 2 and warganegara_id = 2 then 1 else 0 end) AS WNA_P')
            ->first())
            ->toArray();

        // Mutasi keluarga
        $mutasi_keluarga_bln_thn_sql = DB::connection('openkab')->table('log_keluarga as l')
            ->join('tweb_keluarga as k', 'k.id', '=', 'l.id_kk')
            ->join('tweb_penduduk as p', 'p.id', '=', 'k.nik_kepala')
            ->leftJoin('log_penduduk as lp', 'lp.id', '=', 'l.id_log_penduduk')
            ->select('p.*', 'l.id_peristiwa')
            ->where('l.config_id', '=', $id_config)
            ->where(function ($query) use ($thn, $bln) {
                $query->where(function ($subquery) use ($thn) {
                    $subquery->whereNotNull('lp.tgl_lapor')->whereYear('lp.tgl_lapor', '=', $thn);
                })->orWhere(function ($subquery) use ($thn) {
                    $subquery->whereNull('lp.tgl_lapor')->whereYear('l.tgl_peristiwa', '=', $thn);
                });
            })
            ->where(function ($query) use ($bln) {
                $query->where(function ($subquery) use ($bln) {
                    $subquery->whereNotNull('lp.tgl_lapor')->whereMonth('lp.tgl_lapor', '=', $bln);
                })->orWhere(function ($subquery) use ($bln) {
                    $subquery->whereNull('lp.tgl_lapor')->whereMonth('l.tgl_peristiwa', '=', $bln);
                });
            })
            ->where('l.id_peristiwa', '=', $peristiwa);
        $mutasi_keluarga_bln_thn = $this->getCompiledQueryWithBindings($mutasi_keluarga_bln_thn_sql);
        $kel = collect(DB::connection('openkab')->table(DB::raw('(' . $mutasi_keluarga_bln_thn . ') as m'))
            ->selectRaw('sum(case when kk_level = 1 then 1 else 0 end) AS KK')
            ->selectRaw('sum(case when kk_level = 1 and sex = 1 then 1 else 0 end) AS KK_L')
            ->selectRaw('sum(case when kk_level = 1 and sex = 2 then 1 else 0 end) AS KK_P')
            ->first())
            ->toArray();

        return array_merge($data, $kel);
    }
}
