<?php

namespace App\Http\Repository;

use App\Models\Bantuan;
use App\Models\Enums\HubunganRTMEnum;
use App\Models\Enums\LabelStatistikEnum;
use App\Models\Keluarga;
use App\Models\Rtm;
use Illuminate\Support\Facades\Log;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class RtmRepository
{
    private $kategoriStatistik;

    public function listRtm()
    {
        return QueryBuilder::for(Rtm::filterWilayah())
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('nik_kepala'),
                AllowedFilter::exact('no_kk'),
                AllowedFilter::exact('config_id'),
                AllowedFilter::callback('jumlah', function ($query, $value) {
                    switch ($value) {
                        case 'bdt':
                            $query->whereNotNull('bdt');
                            break;
                        default:
                            break;
                    }
                }),
                AllowedFilter::callback('belum_mengisi', function ($query, $value) {
                    switch ($value) {
                        case 'bdt':
                            $query->whereNull('bdt');
                            break;
                        default:
                            break;
                    }
                }),
                AllowedFilter::callback('total', function ($query, $value) {
                    switch ($value) {
                        default:
                            break;
                    }
                }),
                AllowedFilter::callback('sex', function ($query, $value) {
                    $query->whereHas('kepalaKeluarga', function ($r) use ($value) {
                        $r->whereSex($value)
                        ->where('rtm_level', HubunganRTMEnum::KEPALA_RUMAH_TANGGA);
                    });
                    Log::debug('rtm: '. $query->toSql());
                    Log::debug('rtm value: '. $value);
                }),
                AllowedFilter::callback('kode_kabupaten', function ($query, $value) {
                    $query->whereHas('config', static fn ($query) => $query->where('kode_kabupaten', $value));
                }),
                AllowedFilter::callback('kode_kecamatan', function ($query, $value) {
                    $query->whereHas('config', function ($query) use ($value) {
                        $query->where('kode_kecamatan', $value);
                    });
                }),
                AllowedFilter::callback('kode_desa', function ($query, $value) {
                    $query->whereHas('config', function ($query) use ($value) {
                        $query->where('kode_desa', $value);
                    });
                }),
                AllowedFilter::callback('search', function ($query, $value) {
                    $query->where(function ($query) use ($value) {
                        $query->whereRelation('anggota', 'nik','like', "%{$value}%")->orWhereRelation('anggota', 'nama','like', "%{$value}%");
                    });
                }),
            ])
            ->allowedSorts(['id', 'nik_kepala', 'no_kk'])
            ->jsonPaginate();
    }

    public function listStatistik($kategori): array|object
    {
        $this->setKategoriStatistik($kategori);
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
                'kriteria' => json_encode(['jumlah' => $this->getKategoriStatistik()]),
            ],
            [
                'nama' => 'Belum Mengisi',
                'kriteria' => json_encode(['belum mengisi' => $this->getKategoriStatistik()]),
            ],
            [
                'nama' => 'Total',
                'jumlah' => $total,
                'laki_laki' => $totalLakiLaki,
                'perempuan' => $totalPerempuan,
                'kriteria' => json_encode(['total' => $this->getKategoriStatistik()]),
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

    public function detailRtm()
    {
        $judulStatistik = '';
        $kategori = '';
        $result = [];

        $tipe = request()->input('filter')['id'] ?? '0';
        $nomor = request()->input('filter')['nomor'] ?? 0;
        $sex = request()->input('filter')['sex'] ?? null;

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

    /**
     * Get the value of kategoriStatistik
     */
    public function getKategoriStatistik()
    {
        return $this->kategoriStatistik;
    }

    /**
     * Set the value of kategoriStatistik
     *
     * @return  self
     */
    public function setKategoriStatistik($kategoriStatistik)
    {
        $this->kategoriStatistik = $kategoriStatistik;

        return $this;
    }

}
