<?php

namespace App\Http\Repository;

use App\Models\KelasSosial;
use App\Models\Keluarga;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class KeluargaRepository
{
    private $kategoriStatistik;
    public function listKeluarga()
    {
        return QueryBuilder::for(Keluarga::with(['anggota'])->filterWilayah())
            ->allowedFields('*')
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('no_kk'),
                AllowedFilter::exact('nik_kepala'),
                AllowedFilter::exact('kelas_sosial'),
                AllowedFilter::callback('jumlah', function ($query, $value) {
                    switch ($value) {
                        case 'kelas-sosial':
                            $query->whereNotNull('kelas_sosial');
                            break;
                        default:
                            break;
                    }
                }),
                AllowedFilter::callback('belum_mengisi', function ($query, $value) {
                    switch ($value) {
                        case 'kelas-sosial':
                            $query->whereNull('kelas_sosial');
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
                AllowedFilter::callback('kode_kecamatan', function ($query, $value) {
                    $query->whereHas('config', static fn ($query) => $query->where('kode_kecamatan', $value));
                }),
                AllowedFilter::callback('kode_desa', function ($query, $value) {
                    $query->whereHas('config', function ($query) use ($value) {
                        $query->where('kode_desa', $value);
                    });
                }),
                AllowedFilter::callback('kode_kabupaten', function ($query, $value) {
                    $query->whereHas('config', static fn ($query) => $query->where('kode_kabupaten', $value));
                }),
                AllowedFilter::callback('sex', function ($query, $value) {
                    $query->whereHas('kepalaKeluarga', static fn ($query) => $query->where('sex', $value));
                }),
                AllowedFilter::callback('status', function ($query, $value) {
                    $query->whereHas('kepalaKeluarga', static fn ($query) => $query->where('status_dasar', $value));
                }),
                AllowedFilter::callback('search', function ($query, $value) {
                    $query->where(function ($query) use ($value) {
                        $query->where('no_kk', 'like', "%{$value}%")
                        ->orWhereHas('kepalaKeluarga', function ($query) use ($value) {
                            $query->where('nama', 'like', "%{$value}%");
                        })
                        ->orWhereHas('config', function ($query) use ($value) {
                            $query->where('nama_desa', 'like', "%{$value}%");
                        });
                    });
                }),
            ])
            ->allowedSorts([
                'no_kk',
                'nik_kepala',
                'kelas_sosial',
                'created_at',
            ])
            ->jsonPaginate();
    }

    public function rincianKeluarga()
    {
        return QueryBuilder::for(Keluarga::class)
            ->allowedFilters([
                AllowedFilter::exact('no_kk'),
                'no_kk',
                'nik_kepala',
                'kelas_sosial',
            ])
            ->jsonPaginate();
    }

    public function listStatistik($kategori): array|object
    {
        $this->setKategoriStatistik($kategori);
        return collect(match ($kategori) {
            'kelas-sosial' => $this->caseKelasSosial(),
            default => []
        })->toArray();
    }

    public function listTahun()
    {
        return Keluarga::minMaxTahun('tgl_daftar')->first();
    }

    private function listFooter($dataHeader, $query_footer): array|object
    {
        $jumlahLakiLaki = $dataHeader->sum('laki_laki');
        $jumlahJerempuan = $dataHeader->sum('perempuan');
        $jumlah = $jumlahLakiLaki + $jumlahJerempuan;

        $totalLakiLaki = $query_footer->sum('laki_laki');
        $totalPerempuan = $query_footer->sum('perempuan');
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
                'kriteria' => json_encode(['belum_mengisi' => $this->getKategoriStatistik()]),
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

    private function caseKelasSosial(): array|object
    {
        $configId = request('config_desa');
        $kelas = KelasSosial::countStatistik($configId)->get();
        $query = Keluarga::configId()->filters(request()->input('filter'), 'tgl_daftar')->countStatistik($configId)->get();

        return [
            'header' => $kelas,
            'footer' => $this->listFooter($kelas, $query),
        ];
    }

    public function summary()
    {
        return QueryBuilder::for(Keluarga::status())
            ->allowedFilters([
                AllowedFilter::callback('kode_kabupaten', function ($query, $value) {
                    $query->whereHas('config', function ($query) use ($value) {
                        $query->where('kode_kabupaten', $value);
                    });
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
            ])
            ->count();
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
