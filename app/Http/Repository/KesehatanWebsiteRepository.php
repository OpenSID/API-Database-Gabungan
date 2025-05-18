<?php

namespace App\Http\Repository;

use App\Models\Anak;
use App\Models\IbuHamil;
use App\Models\Posyandu;
use App\Models\SasaranPaud;
use App\Services\RekapService;
use App\Services\StuntingService;
use Illuminate\Support\Facades\Log;

class KesehatanWebsiteRepository
{
    public function index($filters = [])
    {
        $rekap = new RekapService;
        $kuartal = $filters['kuartal'] ?? null;
        $tahun = $filters['tahun'] ?? null;
        $idPosyandu = $filters['posyandu'] ?? null;

        if ($kuartal < 1 || $kuartal > 4) {
            $kuartal = null;
        }

        if ($kuartal == null) {
            $bulanSekarang = date('m');
            if ($bulanSekarang <= 3) {
                $kuartal = 1;
            } elseif ($bulanSekarang <= 6) {
                $kuartal = 2;
            } elseif ($bulanSekarang <= 9) {
                $kuartal = 3;
            } elseif ($bulanSekarang <= 12) {
                $kuartal = 4;
            }

            $filters['kuartal'] = $kuartal;
        }

        if ($tahun == null) {
            $tahun = date('Y');
        }

        if ($kuartal == 1) {
            $batasBulanBawah = 1;
            $batasBulanAtas = 3;
        } elseif ($kuartal == 2) {
            $batasBulanBawah = 4;
            $batasBulanAtas = 6;
        } elseif ($kuartal == 3) {
            $batasBulanBawah = 7;
            $batasBulanAtas = 9;
        } elseif ($kuartal == 4) {
            $batasBulanBawah = 10;
            $batasBulanAtas = 12;
        } else {
            exit('Terjadi Kesalahan di kuartal!');
        }

        $JTRT_IbuHamil = IbuHamil::filter($filters)
            ->distinct()
            ->join('kia', 'ibu_hamil.kia_id', '=', 'kia.id')
            ->whereMonth('ibu_hamil.created_at', '>=', $batasBulanBawah)
            ->whereMonth('ibu_hamil.created_at', '<=', $batasBulanAtas)
            ->whereYear('ibu_hamil.created_at', $tahun)
            ->selectRaw('ibu_hamil.kia_id as kia_id')
            ->get();

        $JTRT_BulananAnak = Anak::filter($filters)
            ->distinct()
            ->join('kia', 'bulanan_anak.kia_id', '=', 'kia.id')
            ->whereMonth('bulanan_anak.created_at', '>=', $batasBulanBawah)
            ->whereMonth('bulanan_anak.created_at', '<=', $batasBulanAtas)
            ->whereYear('bulanan_anak.created_at', $tahun)
            ->selectRaw('bulanan_anak.kia_id as kia_id')
            ->get();
        $dataNoKia = [];
        foreach ($JTRT_IbuHamil as $item_ibuHamil) {
            $dataNoKia[] = $item_ibuHamil;

            foreach ($JTRT_BulananAnak as $item_bulananAnak) {
                if (! in_array($item_bulananAnak, $dataNoKia)) {
                    $dataNoKia[] = $item_bulananAnak;
                }
            }
        }

        $ibuHamil = $rekap->getDataIbuHamil($filters);
        $bulananAnak = $rekap->getDataBulananAnak($filters);

        // HITUNG KEK ATAU RISTI
        $jumlahKekRisti = 0;

        foreach ($ibuHamil['dataFilter'] ?? [] as $item) {
            if (! in_array($item['user']['status_kehamilan'], [null, '1'])) {
                $jumlahKekRisti++;
            }
        }

        // HITUNG HASIL PENGUKURAN TIKAR PERTUMBUHAN
        $status_tikar = collect(Anak::STATUS_TIKAR_ANAK)->pluck('simbol', 'id');
        $tikar = ['TD' => 0, 'M' => 0, 'K' => 0, 'H' => 0];

        if ($bulananAnak['dataGrup'] != null) {
            foreach ($bulananAnak['dataGrup'] as $detail) {
                $totalItem = count($detail);
                $i = 0;

                foreach ($detail as $item) {
                    if (++$i === $totalItem) {
                        $tikar[$status_tikar[$item['status_tikar']]]++;
                    }
                }
            }

            $jumlahGiziBukanNormal = 0;

            foreach ($bulananAnak['dataFilter'] as $item) {
                // N = 1
                if ($item['umur_dan_gizi']['status_gizi'] != 'N') {
                    $jumlahGiziBukanNormal++;
                }
            }
        } else {
            $dataNoKia = [];
            $jumlahGiziBukanNormal = 0;
        }

        // START ANAK PAUD------------------------------------------------------------
        $totalAnak = [
            'januari' => ['total' => 0, 'v' => 0],
            'februari' => ['total' => 0, 'v' => 0],
            'maret' => ['total' => 0, 'v' => 0],
            'april' => ['total' => 0, 'v' => 0],
            'mei' => ['total' => 0, 'v' => 0],
            'juni' => ['total' => 0, 'v' => 0],
            'juli' => ['total' => 0, 'v' => 0],
            'agustus' => ['total' => 0, 'v' => 0],
            'september' => ['total' => 0, 'v' => 0],
            'oktober' => ['total' => 0, 'v' => 0],
            'november' => ['total' => 0, 'v' => 0],
            'desember' => ['total' => 0, 'v' => 0],
        ];

        $anak2sd6 = SasaranPaud::filter($filters);
        $anak2sd6->whereYear('sasaran_paud.created_at', $tahun)->get();

        foreach ($anak2sd6 as $datax) {
            if ($datax->januari != 'belum') {
                $totalAnak['januari']['total']++;
            }
            if ($datax->februari != 'belum') {
                $totalAnak['februari']['total']++;
            }
            if ($datax->maret != 'belum') {
                $totalAnak['maret']['total']++;
            }
            if ($datax->april != 'belum') {
                $totalAnak['april']['total']++;
            }
            if ($datax->mei != 'belum') {
                $totalAnak['mei']['total']++;
            }
            if ($datax->juni != 'belum') {
                $totalAnak['juni']['total']++;
            }
            if ($datax->juli != 'belum') {
                $totalAnak['juni']['total']++;
            }
            if ($datax->agustus != 'belum') {
                $totalAnak['agustus']['total']++;
            }
            if ($datax->september != 'belum') {
                $totalAnak['juni']['total']++;
            }
            if ($datax->oktober != 'belum') {
                $totalAnak['oktober']['total']++;
            }
            if ($datax->november != 'belum') {
                $totalAnak['november']['total']++;
            }
            if ($datax->desember != 'belum') {
                $totalAnak['desember']['total']++;
            }

            if ($datax->januari == 'v') {
                $totalAnak['januari']['v']++;
            }
            if ($datax->februari == 'v') {
                $totalAnak['februari']['v']++;
            }
            if ($datax->maret == 'v') {
                $totalAnak['maret']['v']++;
            }
            if ($datax->april == 'v') {
                $totalAnak['april']['v']++;
            }
            if ($datax->mei == 'v') {
                $totalAnak['mei']['v']++;
            }
            if ($datax->juni == 'v') {
                $totalAnak['juni']['v']++;
            }
            if ($datax->juli == 'v') {
                $totalAnak['juni']['v']++;
            }
            if ($datax->agustus == 'v') {
                $totalAnak['agustus']['v']++;
            }
            if ($datax->september == 'v') {
                $totalAnak['juni']['v']++;
            }
            if ($datax->oktober == 'v') {
                $totalAnak['oktober']['v']++;
            }
            if ($datax->november == 'v') {
                $totalAnak['november']['v']++;
            }
            if ($datax->desember == 'v') {
                $totalAnak['desember']['v']++;
            }
        }

        $dataAnak0sd2Tahun = ['jumlah' => 0, 'persen' => 0];
        if ($kuartal == 1) {
            $jmlAnk = $totalAnak['januari']['total'] + $totalAnak['februari']['total'] + $totalAnak['maret']['total'];
            $jmlV = $totalAnak['januari']['v'] + $totalAnak['februari']['v'] + $totalAnak['maret']['v'];
        } elseif ($kuartal == 2) {
            $jmlAnk = $totalAnak['april']['total'] + $totalAnak['mei']['total'] + $totalAnak['juni']['total'];
            $jmlV = $totalAnak['april']['v'] + $totalAnak['mei']['v'] + $totalAnak['juni']['v'];
        } elseif ($kuartal == 3) {
            $jmlAnk = $totalAnak['agustus']['total'];
            $jmlV = $totalAnak['agustus']['v'];
        } elseif ($kuartal == 4) {
            $jmlAnk = $totalAnak['oktober']['total'] + $totalAnak['november']['total'] + $totalAnak['desember']['total'];
            $jmlV = $totalAnak['oktober']['v'] + $totalAnak['november']['v'] + $totalAnak['desember']['v'];
        }
        $dataAnak0sd2Tahun['jumlah'] = $jmlV;
        $dataAnak0sd2Tahun['persen'] = $jmlAnk !== 0 ? number_format($jmlV / $jmlAnk * 100, 2) : 0;

        // END ANAK PAUD------------------------------------------------------------

        $data = $this->widgets($filters);
        $data['navigasi'] = 'scorcard-konvergensi';
        $data['dataAnak0sd2Tahun'] = $dataAnak0sd2Tahun;
        $data['idPosyandu'] = $idPosyandu;
        $data['posyandu'] = Posyandu::filter($filters)->get();
        $data['JTRT'] = count($dataNoKia ?? []);
        $data['jumlahKekRisti'] = $jumlahKekRisti;
        $data['jumlahGiziBukanNormal'] = $jumlahGiziBukanNormal;
        $data['tikar'] = $tikar;
        $data['ibu_hamil'] = $ibuHamil;
        $data['bulanan_anak'] = $bulananAnak;
        $data['dataTahun'] = !$ibuHamil['dataTahun']->isEmpty() ? $ibuHamil['dataTahun'] : $bulananAnak['dataTahun'];
        $data['kuartal'] = $kuartal;
        $data['_tahun'] = $tahun;
        $data['aktif'] = 'scorcard';
        $stunting = new StuntingService($filters);
        $data['chartStuntingUmurData'] = $stunting->chartStuntingUmurData();
        $data['chartStuntingPosyanduData'] = $stunting->chartPosyanduData();
        $data['id'] = 1;
        // check if data has current year
        if(!$data['dataTahun']->contains('tahun', date('Y'))) {
            $data['dataTahun']->push([
                'tahun' => date('Y'),
            ]);
        }
        $data['dataTahun'] = $data['dataTahun']->sortByDesc('tahun');
        return collect([$data]);
    }

    private function widgets($filters): array
    {
        $anaknormal = Anak::filter($filters)->normal();
        $anakresiko = Anak::filter($filters)->normal();
        $anakperiksa = Anak::filter($filters)->normal();
        $anakstunting = Anak::filter($filters)->normal();
        $ibuhamil = IbuHamil::filter($filters);
        $hamilperiksa = IbuHamil::filter($filters);

        // Hitung jumlah anak normal dengan filter yang diterapkan
        $anakperiksa = $anakperiksa->whereMonth('bulanan_anak.created_at', date('m'))->count();
        $anakresiko = $anakresiko->resikoStunting()->count();
        $anakstunting = $anakstunting->stunting()->count();
        $anaknormal = $anaknormal->count();
        $hamilperiksa = $hamilperiksa->whereMonth('created_at', date('m'))->count();
        $ibuhamil = $ibuhamil->count();

        return [
            'bulanIniIbuHamil' => IbuHamil::filter($filters)->whereMonth('created_at', date('m'))->count(),
            'bulanIniAnak' => Anak::filter($filters)->whereMonth('created_at', date('m'))->count(),
            'totalIbuHamil' => IbuHamil::filter($filters)->count(),
            'totalAnak' => Anak::filter($filters)->count(),
            'totalAnakNormal' => Anak::filter($filters)->where('status_gizi', 1)->count(),
            'totalAnakResiko' => Anak::filter($filters)->whereIn('status_gizi', [2, 3])->count(),
            'totalAnakStunting' => Anak::filter($filters)->where('status_gizi', 4)->count(),
            'widgets' => [
                [
                    'title' => 'Ibu Hamil Periksa Bulan ini',
                    'icon' => 'ion-woman',
                    'bg-color' => 'bg-blue',
                    'bg-icon' => 'ion-stats-bars',
                    'total' => $hamilperiksa,
                ],
                [
                    'title' => 'Anak Periksa Bulan ini',
                    'icon' => 'ion-woman',
                    'bg-color' => 'bg-gray',
                    'bg-icon' => 'ion-stats-bars',
                    'total' => $anakperiksa,
                ],
                [
                    'title' => 'Ibu Hamil & Anak 0-23 Bulan',
                    'icon' => 'ion-woman',
                    'bg-color' => 'bg-green',
                    'bg-icon' => 'ion-stats-bars',
                    'total' => $ibuhamil + $anaknormal,
                ],
                [
                    'title' => 'Anak 0-23 Bulan Normal',
                    'icon' => 'ion-woman',
                    'bg-color' => 'bg-green',
                    'bg-icon' => 'ion-stats-bars',
                    'total' => $anaknormal,
                ],
                [
                    'title' => 'Anak 0-23 Bulan Resiko Stunting',
                    'icon' => 'ion-woman',
                    'bg-color' => 'bg-yellow',
                    'bg-icon' => 'ion-stats-bars',
                    'total' => $anakresiko,
                ],
                [
                    'title' => 'Anak 0-23 Bulan Stunting',
                    'icon' => 'ion-woman',
                    'bg-color' => 'bg-red',
                    'bg-icon' => 'ion-stats-bars',
                    'total' => $anakstunting,
                ],
            ],
        ];
    }
}
