<?php

namespace App\Http\Repository;

use App\Models\Bantuan;
use App\Models\Enums\HubunganRTMEnum;
use App\Models\Enums\LabelStatistikEnum;
use App\Models\Keluarga;
use App\Models\Rtm;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class RtmRepository
{
    public function listStatistik($kategori): array|object
    {
        return collect(match ($kategori) {
            'bdt' => $this->caseBdt(),
            default => []
        })->toArray();
    }

    public function listTahun()
    {
        return Rtm::minMaxTahun('tgl_daftar')->first();
    }

    private function listFooter($dataHeader, $queryFooter): array|object
    {
        $jumlahLakiLaki = $dataHeader->sum('laki_laki');
        $jumlahJerempuan = $dataHeader->sum('perempuan');
        $jumlah = $jumlahLakiLaki + $jumlahJerempuan;

        $totalLakiLaki = $queryFooter->sum('laki_laki');
        $totalPerempuan = $queryFooter->sum('perempuan');
        $total = $totalLakiLaki + $totalPerempuan;

        return [
            [
                'nama' => 'Jumlah',
                'jumlah' => $jumlah,
                'laki_laki' => $jumlahLakiLaki,
                'perempuan' => $jumlahJerempuan,
            ],
            [
                'nama' => 'Belum Mengisi',
            ],
            [
                'nama' => 'Total',
                'jumlah' => $total,
                'laki_laki' => $totalLakiLaki,
                'perempuan' => $totalPerempuan,
            ],
        ];
    }

    private function caseBdt(): array|object
    {
        $bdt = Rtm::CountStatistik()->filters(request()->input('filter'), 'tgl_daftar');
        $queryFooter = $bdt->get();
        $dataHeader = $bdt->bdt(true)->get();

        return [
            'header' => [],
            'footer' => $this->listFooter($dataHeader, $queryFooter),
        ];
    }

    public function detailRtm($tipe = '0', $nomor = 0, $sex = null)
    {
        $judulStatistik = '';
        $kategori = '';
        $result = [];

        switch ($tipe) {
            case 'bdt':
                $kategori = 'KLASIFIKASI BDT :';
                $result = QueryBuilder::for(Rtm::class)
                    ->when($sex, static fn ($q) => $q->whereHas('kepalaKeluarga', static fn ($r) => $r
                        ->whereSex($sex)
                        ->where('rtm_level', HubunganRTMEnum::KEPALA_RUMAH_TANGGA)))
                    ->when(in_array($nomor, [LabelStatistikEnum::BelumMengisi, LabelStatistikEnum::Jumlah]),
                        static fn ($q) => $nomor == LabelStatistikEnum::BelumMengisi ? $q->whereNull('bdt') : $q->whereNotNull('bdt'))
                    ->with(['kepalaKeluarga' => static fn ($q) => $q->withOnly(['keluarga'])])
                    ->withCount('anggota')
                    ->allowedFields('*')
                    ->allowedSorts(['nama'])
                    ->jsonPaginate();
                break;

            case $tipe > 50:
                $program_id = preg_replace('/^50/', '', $tipe);
                session()->put('program_bantuan', $program_id);

                $nama = Bantuan::find($program_id)?->nama ?? 'Program Tidak Diketahui';

                if (! in_array($nomor, [LabelStatistikEnum::BelumMengisi, LabelStatistikEnum::Total])) {
                    session()->put('status_dasar', null);
                    $nomor = $program_id;
                }

                $kategori = $nama . ' : ';
                $tipe = 'penerima_bantuan';

                break;

            default:
                $kategori = '';
                break;
        }

        $judul = Rtm::judulStatistik($tipe, $nomor, $sex);
        $judulStatistik = ($judul['nama'] ?? false) ? $kategori . $judul['nama'] : '';

        return [
            'judul' => $judulStatistik,
            'data' => $result
        ];
    }

}
