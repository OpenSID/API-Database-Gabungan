<?php

namespace App\Services;

use App\Models\Anak;
use App\Models\Posyandu;

class StuntingService
{
    private $kuartal;

    private $tahun;

    private $tahunawal;

    private $idPosyandu;

    private $batasBulanAtas;

    private $batasBulanBawah;

    private $filters;

    public function __construct(?array $default)
    {
        $this->kuartal = $default['kuartal'] ?? null;
        $this->tahun = $default['tahun'] ?? null;
        $this->idPosyandu = $default['posyandu'] ?? null;
        $this->filters = $default['filters'] ?? [];

        if ($this->kuartal < 1 || $this->kuartal > 4) {
            $this->kuartal = null;
        }

        if ($this->kuartal == null) {
            $bulanSekarang = date('m');
            if ($bulanSekarang <= 3) {
                $_kuartal = 1;
            } elseif ($bulanSekarang <= 6) {
                $_kuartal = 2;
            } elseif ($bulanSekarang <= 9) {
                $_kuartal = 3;
            } elseif ($bulanSekarang <= 12) {
                $_kuartal = 4;
            }
            $this->kuartal = $_kuartal;
        }
        $this->tahunawal = $this->tahun;

        if ($this->tahun == null) {
            $this->tahun = date('Y');
        }

        if ($this->kuartal == 1) {
            $this->batasBulanBawah = 1;
            $this->batasBulanAtas = 3;
        } elseif ($this->kuartal == 2) {
            $this->batasBulanBawah = 4;
            $this->batasBulanAtas = 6;
        } elseif ($this->kuartal == 3) {
            $this->batasBulanBawah = 7;
            $this->batasBulanAtas = 9;
        } elseif ($this->kuartal == 4) {
            $this->batasBulanBawah = 10;
            $this->batasBulanAtas = 12;
        }
    }

    public function chartStuntingUmurData()
    {
        $filters = $this->filters;
        $summary = collect([
            [
                'range_1' => [Anak::TB_PENDEK => 0, Anak::TB_SANGAT_PENDEK => 0],
                'range_2' => [Anak::TB_PENDEK => 0, Anak::TB_SANGAT_PENDEK => 0],
                'range_3' => [Anak::TB_PENDEK => 0, Anak::TB_SANGAT_PENDEK => 0],
            ],
        ]);
        $stuntingObj = Anak::filter($filters)->selectRaw('status_tikar')
            ->selectRaw('sum(case when umur_bulan between 0 and 5 then 1 else 0 end) as range_1')
            ->selectRaw('sum(case when umur_bulan between 6 and 11 then 1 else 0 end) as range_2')
            ->selectRaw('sum(case when umur_bulan between 12 and 23 then 1 else 0 end) as range_3')
            ->stuntingPendek()
            ->join('config', 'config.id', '=', 'bulanan_anak.config_id', 'left')
            ->groupBy(['status_tikar']);

        $stunting = $stuntingObj->get();

        if (! $stunting->isEmpty()) {
            $obj = $stunting->keyBy('status_tikar');

            $totalRange1 = $obj[Anak::TB_SANGAT_PENDEK]->range_1 + ($obj[Anak::TB_PENDEK]->range_1 ?? 0);
            $totalRange2 = $obj[Anak::TB_SANGAT_PENDEK]->range_2 + ($obj[Anak::TB_PENDEK]->range_2 ?? 0);
            $totalRange3 = $obj[Anak::TB_SANGAT_PENDEK]->range_3 + ($obj[Anak::TB_PENDEK]->range_3 ?? 0);
            $summary = collect([
                'range_1' => [Anak::TB_PENDEK => $this->conversiPercent(($obj[Anak::TB_PENDEK]->range_1 ?? 0), $totalRange1), Anak::TB_SANGAT_PENDEK => $this->conversiPercent($obj[Anak::TB_SANGAT_PENDEK]->range_1, $totalRange1)],
                'range_2' => [Anak::TB_PENDEK => $this->conversiPercent(($obj[Anak::TB_PENDEK]->range_2 ?? 0), $totalRange2), Anak::TB_SANGAT_PENDEK => $this->conversiPercent($obj[Anak::TB_SANGAT_PENDEK]->range_2, $totalRange2)],
                'range_3' => [Anak::TB_PENDEK => $this->conversiPercent(($obj[Anak::TB_PENDEK]->range_3 ?? 0), $totalRange3), Anak::TB_SANGAT_PENDEK => $this->conversiPercent($obj[Anak::TB_SANGAT_PENDEK]->range_3, $totalRange3)],
            ]);
        }
        if (! empty($summary[0])) {
            return [
                ['id' => 'chart_0_5', 'title' => 'Jumlah Per Gol Umur 0-5 Bulan', 'data' => [['name' => 'Pendek (Stunting)', 'y' => $summary[0]['range_1'][Anak::TB_PENDEK]], ['name' => 'Sangat Pendek (Severity Stunting)', 'y' => $summary[0]['range_1'][Anak::TB_SANGAT_PENDEK]]]],
                ['id' => 'chart_6_11', 'title' => 'Jumlah Per Gol Umur 6-11 Bulan', 'data' => [['name' => 'Pendek (Stunting)', 'y' => $summary[0]['range_2'][Anak::TB_PENDEK]], ['name' => 'Sangat Pendek (Severity Stunting)', 'y' => $summary[0]['range_2'][Anak::TB_SANGAT_PENDEK]]]],
                ['id' => 'chart_12_23', 'title' => 'Jumlah Per Gol Umur 12-23 Bulan', 'data' => [['name' => 'Pendek (Stunting)', 'y' => $summary[0]['range_3'][Anak::TB_PENDEK]], ['name' => 'Sangat Pendek (Severity Stunting)', 'y' => $summary[0]['range_3'][Anak::TB_SANGAT_PENDEK]]]],
            ];
        } else {
            return [
                ['id' => 'chart_0_5', 'title' => 'Jumlah Per Gol Umur 0-5 Bulan', 'data' => [['name' => 'Pendek (Stunting)', 'y' => $summary['range_1'][Anak::TB_PENDEK]], ['name' => 'Sangat Pendek (Severity Stunting)', 'y' => $summary['range_1'][Anak::TB_SANGAT_PENDEK]]]],
                ['id' => 'chart_6_11', 'title' => 'Jumlah Per Gol Umur 6-11 Bulan', 'data' => [['name' => 'Pendek (Stunting)', 'y' => $summary['range_2'][Anak::TB_PENDEK]], ['name' => 'Sangat Pendek (Severity Stunting)', 'y' => $summary['range_2'][Anak::TB_SANGAT_PENDEK]]]],
                ['id' => 'chart_12_23', 'title' => 'Jumlah Per Gol Umur 12-23 Bulan', 'data' => [['name' => 'Pendek (Stunting)', 'y' => $summary['range_3'][Anak::TB_PENDEK]], ['name' => 'Sangat Pendek (Severity Stunting)', 'y' => $summary['range_3'][Anak::TB_SANGAT_PENDEK]]]],
            ];
        }
    }

    public function chartPosyanduData()
    {
        $filters = $this->filters;
        $giziAnakObj = Anak::filter($filters)->selectRaw('status_gizi, posyandu_id, count(*) as total');

        if ($this->tahunawal == null) {
            $giziAnakObj->whereMonth('created_at', '>=', $this->batasBulanBawah)
                ->whereMonth('created_at', '<=', $this->batasBulanAtas)
                ->whereYear('created_at', $this->tahun);
        }

        $giziAnakObj->groupBy(['posyandu_id', 'status_gizi']);
        $posyanduObj = Posyandu::filter($filters);
        if ($this->idPosyandu) {
            $giziAnakObj->wherePosyanduId($this->idPosyandu);
            $posyanduObj->whereId($this->idPosyandu);
        }
        $posyandu = $posyanduObj->get();

        $giziAnak = $giziAnakObj->get();
        $summary = collect([
            [
                'normal' => [],
                'resiko_stunting' => [],
                'stunting' => [],
            ],
        ]);
        if (! $giziAnak->isEmpty()) {
            $summary = $giziAnak->groupBy('posyandu_id')->map(function ($item) {
                return [
                    'normal' => $item->sum(function ($q) {
                        return $q->isNormal() ? $q->total : 0;
                    }),
                    'resiko_stunting' => $item->sum(function ($q) {
                        return $q->isResikoStunting() ? $q->total : 0;
                    }),
                    'stunting' => $item->sum(function ($q) {
                        return $q->isStunting() ? $q->total : 0;
                    }),
                ];
            });
        }

        return [
            'categories' => $posyandu->pluck('nama')->toArray(),
            'data' => [
                ['name' => 'Normal', 'data' => $summary->pluck('normal')->toArray()],
                ['name' => 'Resiko Stunting', 'data' => $summary->pluck('resiko_stunting')->toArray()],
                ['name' => 'Terindikasi Stunting', 'data' => $summary->pluck('stunting')->toArray()],
            ],
        ];
    }

    private function conversiPercent($number, $total)
    {
        return intval(str_replace('%', '', persen3($number, $total)));
    }
}
