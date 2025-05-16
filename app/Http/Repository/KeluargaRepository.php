<?php

namespace App\Http\Repository;

use App\Models\KelasSosial;
use App\Models\Keluarga;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class KeluargaRepository
{
    public function listKeluarga()
    {
        return QueryBuilder::for(Keluarga::with(['anggota'])->filterWilayah())
            ->allowedFields('*')
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('no_kk'),
                AllowedFilter::exact('nik_kepala'),
                AllowedFilter::exact('kelas_sosial'),
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
}
