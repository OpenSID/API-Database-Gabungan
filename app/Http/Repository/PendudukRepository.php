<?php

namespace App\Http\Repository;

use App\Models\Config;
use App\Models\Covid;
use App\Models\Enums\StatusDasarEnum;
use App\Models\Ktp;
use App\Models\LogPenduduk;
use App\Models\Penduduk;
use App\Models\Umur;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PendudukRepository
{
    private $kodeDesa;
    private $kategoriStatistik;
    public function pendudukReferensi(string $class)
    {
        return QueryBuilder::for($class)
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::callback('search', function ($query, $value) {
                    $query->where(function ($query) use ($value) {
                        $query->where('nama', 'like', "%{$value}%");
                    });
                }),
            ])
            ->allowedSorts(['id', 'nama'])
            ->jsonPaginate();
    }

    public function listPendudukDemoSeeder()
    {
        return QueryBuilder::for(Penduduk::select([
            'id',
            'config_id',
            'id_kk',
            'id_rtm',
            'rtm_level',
            'kk_level',
            'created_at',
        ]))
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('id_kk'),
                AllowedFilter::exact('config_id'),
                AllowedFilter::exact('kk_level'),
            ])
            ->get();
    }

    public function listPenduduk()
    {
        $defaultConfigId = 1;
        return QueryBuilder::for(Penduduk::withRef()->filterWilayah())
            ->allowedFields('*')
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('sex'),
                AllowedFilter::exact('status'),
                AllowedFilter::exact('pendidikan_kk_id'),
                AllowedFilter::exact('pendidikan_sedang_id'),
                AllowedFilter::exact('pekerjaan_id'),
                AllowedFilter::exact('status_kawin'),
                AllowedFilter::exact('agama_id'),
                AllowedFilter::exact('cara_kb_id'),
                AllowedFilter::exact('id_asuransi'),
                AllowedFilter::exact('hamil'),
                AllowedFilter::exact('suku'),
                AllowedFilter::exact('golongan_darah_id'),
                AllowedFilter::exact('cacat_id'),
                AllowedFilter::exact('sakit_menahun_id'),
                AllowedFilter::exact('kk_level'),
                AllowedFilter::exact('warganegara_id'),
                AllowedFilter::exact('config_id'),
                AllowedFilter::exact('keluarga.no_kk'),
                AllowedFilter::exact('clusterDesa.dusun'),
                AllowedFilter::exact('clusterDesa.rw'),
                AllowedFilter::exact('clusterDesa.rt'),
                AllowedFilter::callback('jumlah', function ($query, $value) use($defaultConfigId) {
                    $referensi = $this->tabelReferensi($value);
                    switch ($value) {
                        case 'rentang-umur':
                            $batasUmur = DB::connection('openkab')->table('tweb_penduduk_umur')
                                ->selectRaw('min(dari) as dari, max(sampai) as sampai')
                                ->where('config_id', $defaultConfigId)
                                ->where('status', Umur::RENTANG)
                                ->first();
                            $umurObj = [
                                'min' => $batasUmur->dari,
                                'max' => $batasUmur->sampai,
                                'satuan' => 'tahun',
                            ];
                            $query->batasiUmur(date('d-m-Y'), $umurObj);
                            break;
                        case 'kategori-umur':
                            $batasUmur = DB::connection('openkab')->table('tweb_penduduk_umur')
                                ->selectRaw('min(dari) as dari, max(sampai) as sampai')
                                ->where('config_id', $defaultConfigId)
                                ->where('status', Umur::KATEGORI)
                                ->first();
                            $umurObj = [
                                'min' => $batasUmur->dari,
                                'max' => $batasUmur->sampai,
                                'satuan' => 'tahun',
                            ];
                            $query->batasiUmur(date('d-m-Y'), $umurObj);
                            break;
                        case 'bpjs-ketenagakerjaan':
                            $query->whereNotNull('bpjs_ketenagakerjaan')
                                ->where('bpjs_ketenagakerjaan', '!=', '');
                            break;
                        case 'akta-kelahiran':
                            $query->whereNotNull('akta_lahir')
                                ->whereIn('akta_lahir', ['', '-']);
                            break;
                        case 'akta-nikah':
                            $query->whereNotNull('akta_perkawinan')
                                ->whereIn('akta_perkawinan', ['', '-']);
                            break;
                        case 'status-covid':
                            $query->join('covid19_pemudik', 'covid19_pemudik.id_terdata' ,'=', 'tweb_penduduk.id')
                                ->where('covid19_pemudik.status_covid', '!=', '');
                            break;
                        case 'suku':
                            $query->whereNotNull('suku')
                                ->where('suku', '!=', '');
                            break;
                        case 'ktp':
                            $query->ktp()->whereNotNull('status_rekam')
                                ->where('status_rekam', '!=', '');
                            break;
                        default:
                            $query->whereNotNull($referensi['idReferensi']);
                            break;
                    }
                }),
                AllowedFilter::callback('belum_mengisi', function ($query, $value)use($defaultConfigId) {
                    $referensi = $this->tabelReferensi($value);
                    switch ($value) {
                        case 'rentang-umur':
                            $batasUmur = DB::connection('openkab')->table('tweb_penduduk_umur')
                                ->selectRaw('min(dari) as dari, max(sampai) as sampai')
                                ->where('config_id', $defaultConfigId)
                                ->where('status', Umur::RENTANG)
                                ->first();

                            $umurMin = $batasUmur->dari;
                            $umurMax = $batasUmur->sampai;

                            $query->whereRaw(DB::raw("TIMESTAMPDIFF(YEAR, tanggallahir, STR_TO_DATE(date('d-m-Y'),'%d-%m-%Y')) not between {$umurMin} and {$umurMax}"));
                            break;
                        case 'kategori-umur':
                            $batasUmur = DB::connection('openkab')->table('tweb_penduduk_umur')
                                ->selectRaw('min(dari) as dari, max(sampai) as sampai')
                                ->where('config_id', $defaultConfigId)
                                ->where('status', Umur::KATEGORI)
                                ->first();
                            $umurMin = $batasUmur->dari;
                            $umurMax = $batasUmur->sampai;

                            $query->whereRaw(DB::raw("TIMESTAMPDIFF(YEAR, tanggallahir, STR_TO_DATE(date('d-m-Y'),'%d-%m-%Y')) not between {$umurMin} and {$umurMax}"));
                            break;
                        case 'bpjs-ketenagakerjaan':
                            $query->whereNull('bpjs_ketenagakerjaan')
                                ->orWhere('bpjs_ketenagakerjaan', '=', '');
                            break;
                        case 'akta-kelahiran':
                            $query->whereNull('akta_lahir')
                                ->orWhereIn('akta_lahir', ['', '-']);
                            break;
                        case 'akta-nikah':
                            $query->whereNull('akta_perkawinan')
                                ->orWhereIn('akta_perkawinan', ['', '-']);
                            break;
                        case 'status-covid':
                            $query->whereNotIn(
                                'tweb_penduduk.id',
                                function ($query) {
                                    $query->select('covid19_pemudik.id_terdata')
                                        ->from('covid19_pemudik');
                                }
                            );
                            break;
                        case 'suku':
                            $query->whereNull('suku')
                                ->orWhere('suku', '');
                            break;
                        case 'ktp':
                            $query->ktp()->whereNull('status_rekam')
                                ->orWhere('status_rekam', '');
                            break;
                        default:
                            $query->whereNull($referensi['idReferensi']);
                            break;
                    }
                }),
                AllowedFilter::callback('total', function ($query, $value) {
                    switch ($value) {
                        case 'ktp':
                            $query->ktp();
                            break;
                        default:
                            break;
                    }
                }),
                AllowedFilter::callback('ktp', function ($query, $value) {
                    if($value){
                        $query->ktp();
                    }
                }),
                AllowedFilter::callback('status_dasar', function ($query, $value) {
                    $tanggalPeristiwa = null;
                    $configDesa = null;
                    if($value != StatusDasarEnum::MATI) {
                        $logPenduduk = LogPenduduk::select(['log_penduduk.id_pend'])->peristiwaTerakhir($tanggalPeristiwa, $configDesa)->tidakMati()->toBoundSql();
                        $query->where('status_dasar', $value)->join(DB::raw("($logPenduduk) as log"), 'log.id_pend', '=', 'tweb_penduduk.id');
                    } else {
                        $query->where('status_dasar', $value);
                    }
                }),
                AllowedFilter::callback('status_rekam', function ($query, $value) {
                    $where = "((DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(tanggallahir)), '%Y')+0)>=17 OR (status_kawin IS NOT NULL AND status_kawin <> 1))";
                    $query->where('status_rekam', $value)->whereRaw($where);

                }),
                AllowedFilter::callback('tag_id_card', function ($query, $value) {
                    $query->when($value == 0, static fn($q) => $q->whereNull('tag_id_card'))
                        ->when($value == 1, static fn($q) => $q->whereNotNull('tag_id_card'));
                }),
                AllowedFilter::callback('id_kk', function ($query, $value) {
                    $query->when($value == 0, static fn($q) => $q->whereNull('id_kk'))
                        ->when($value == 1, static fn($q) => $q->whereNotNull('id_kk'));
                }),
                AllowedFilter::callback('status_covid', function ($query, $value) {
                    $query->join('covid19_pemudik', 'covid19_pemudik.id_terdata' ,'=', 'tweb_penduduk.id')->where('covid19_pemudik.status_covid', $value);
                }),
                AllowedFilter::callback('umur', function ($query, $value) {
                    $tglPemilihan = Carbon::now()->format('d-m-Y');
                    $query->batasiUmur($tglPemilihan, $value);
                }),
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
                AllowedFilter::callback('search', function ($query, $value) {
                    $query->where(function ($query) use ($value) {
                        $query->where('nama', 'like', "%{$value}%")
                            ->orWhere('nik', 'like', "%{$value}%")
                            ->orWhere('tag_id_card', 'like', "%{$value}%");
                    });
                }),
            ])
            ->allowedSorts([
                'nik',
                'nama',
                'umur',
                'created_at',
                'tag_id_card'
            ])
            ->jsonPaginate();
    }

    public function listPendudukKesehatan()
    {
        return QueryBuilder::for(Penduduk::filterWilayah()->select([
            'id',
            'nik',
            'nama',
            'golongan_darah_id',
            'cacat_id',
            'sakit_menahun_id',
            'cara_kb_id',
            'hamil',
            'id_asuransi',
            'no_asuransi',
        ]))
        ->allowedFields('*')  // Tentukan field yang diizinkan untuk dipilih
        ->allowedFilters([  // Tentukan filter yang diizinkan
            AllowedFilter::exact('id'),
            AllowedFilter::exact('sex'),
            AllowedFilter::exact('status'),
            AllowedFilter::exact('status_dasar'),
            AllowedFilter::exact('keluarga.no_kk'),
            AllowedFilter::exact('clusterDesa.dusun'),
            AllowedFilter::exact('clusterDesa.rw'),
            AllowedFilter::exact('clusterDesa.rt'),
            AllowedFilter::callback('search', function ($query, $value) {
                $query->where(function ($query) use ($value) {
                    $query->where('nama', 'like', "%{$value}%")
                        ->orWhere('nik', 'like', "%{$value}%")
                        ->orWhere('tag_id_card', 'like', "%{$value}%");
                });
            }),
        ])
        ->allowedSorts([  // Tentukan kolom yang dapat digunakan untuk sorting
            'nik',
            'nama',
            'umur',
            'created_at',
        ])
        ->jsonPaginate();
    }

    public function listPendudukJaminanSosial()
    {
        return QueryBuilder::for(Penduduk::withRef()->filterWilayah()->select([
            'id',
            'nik',
            'nama',
            'id_asuransi',
            'no_asuransi',
            'bpjs_ketenagakerjaan',
            'cacat_id',
        ]))
        ->allowedFields('*')  // Tentukan field yang diizinkan untuk dipilih
        ->allowedFilters([  // Tentukan filter yang diizinkan
            AllowedFilter::exact('id'),
            AllowedFilter::callback('search', function ($query, $value) {
                $query->where(function ($query) use ($value) {
                    $query->where('nama', 'like', "%{$value}%")
                        ->orWhere('nik', 'like', "%{$value}%");
                });
            }),
        ])
        ->allowedSorts([  // Tentukan kolom yang dapat digunakan untuk sorting
            'nik',
            'nama',
        ])
        ->jsonPaginate();
    }

    public function listPendudukProdeskel()
    {
        return QueryBuilder::for(Penduduk::with('prodeskelLembagaAdat')->withRef()->filterWilayah()->select([
            'id',
            'nik',
            'nama',
            'agama_id',
            'suku',
            'config_id', // Pastikan config_id termasuk dalam query
        ]))
            ->allowedFields([
                'id', 'nik', 'nama', 'agama_id', 'suku', 'prodeskelLembagaAdat.id', 'prodeskelLembagaAdat.kategori', 'prodeskelLembagaAdat.data',
            ])
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::callback('search', function ($query, $value) {
                    $query->where(function ($query) use ($value) {
                        $query->where('nama', 'like', "%{$value}%")
                            ->orWhere('nik', 'like', "%{$value}%");
                    });
                }),
            ])
            ->allowedSorts(['nik', 'nama'])
            ->jsonPaginate();
    }

    public function listStatistik($kategori, $kodeDesa): array|object
    {
        $this->setKodeDesa($kodeDesa);
        $this->setKategoriStatistik($kategori);
        if($this->getKodeDesa()) {
            $configDesa = Config::where('kode_desa', $this->getKodeDesa())->first()->id;
            request()->merge(['config_desa' => $configDesa]);
        }
        return collect(match ($kategori) {
            'rentang-umur' => $this->caseRentangUmur(),
            'kategori-umur' => $this->caseKategoriUmur(),
            'akta-kelahiran' => $this->caseAktaKelahiran(),
            'akta-nikah' => $this->caseAktaNikah(),
            'status-covid' => $this->caseStatusCovid(),
            'suku' => $this->caseSuku(),
            'ktp' => $this->caseKtp(),
            default => $this->caseWithReferensi($kategori),
        })->toArray();
    }

    public function listTahun()
    {
        return LogPenduduk::tahun()->first();
    }

    private function tabelReferensi($kategori): array|object|null
    {
        return match ($kategori) {
            'status-kehamilan' => [
                'tabelReferensi' => 'ref_penduduk_hamil',
                'idReferensi' => 'hamil',
                'whereHeader' => 'tweb_penduduk.sex = 2',
                'whereFooter' => 'tweb_penduduk.sex = 2',
            ],
            'pendidikan-dalam-kk' => [
                'tabelReferensi' => 'tweb_penduduk_pendidikan_kk',
                'idReferensi' => 'pendidikan_kk_id',
                'whereHeader' => null,
                'whereFooter' => null,
            ],
            'pendidikan-sedang-ditempuh' => [
                'tabelReferensi' => 'tweb_penduduk_pendidikan',
                'idReferensi' => 'pendidikan_sedang_id',
                'whereHeader' => null,
                'whereFooter' => null,
            ],
            'bpjs-ketenagakerjaan' => [
                'tabelReferensi' => 'tweb_penduduk_pekerjaan',
                'idReferensi' => 'pekerjaan_id',
                'whereHeader' => '(bpjs_ketenagakerjaan IS NOT NULL && bpjs_ketenagakerjaan != "")',
                'whereFooter' => null,
            ],
            'jenis-kelamin' => [
                'idReferensi' => 'sex',
                'tabelReferensi' => 'tweb_penduduk_sex',
                'whereHeader' => null,
                'whereFooter' => null,
            ],
            'agama' => [
                'tabelReferensi' => 'tweb_penduduk_agama',
                'idReferensi' => 'agama_id',
                'whereHeader' => null,
                'whereFooter' => null,
            ],
            'warga-negara' => [
                'idReferensi' => 'warganegara_id',
                'tabelReferensi' => 'tweb_penduduk_warganegara',
                'whereHeader' => null,
                'whereFooter' => null,
            ],
            'pekerjaan' => [
                'tabelReferensi' => 'tweb_penduduk_pekerjaan',
                'idReferensi' => 'pekerjaan_id',
                'whereHeader' => null,
                'whereFooter' => null,
            ],
            'hubungan-dalam-kk' => [
                'tabelReferensi' => 'tweb_penduduk_hubungan',
                'idReferensi' => 'kk_level',
                'whereHeader' => null,
                'whereFooter' => null,
            ],
            'golongan-darah' => [
                'tabelReferensi' => 'tweb_golongan_darah',
                'idReferensi' => 'golongan_darah_id',
                'whereHeader' => null,
                'whereFooter' => null,
            ],
            'status-penduduk' => [
                'idReferensi' => 'status',
                'tabelReferensi' => 'tweb_penduduk_status',
                'whereHeader' => null,
                'whereFooter' => null,
            ],
            'akseptor-kb' => [
                'idReferensi' => 'cara_kb_id',
                'tabelReferensi' => 'tweb_cara_kb',
                'whereHeader' => null,
                'whereFooter' => null,
            ],
            'penyakit-menahun' => [
                'idReferensi' => 'sakit_menahun_id',
                'tabelReferensi' => 'tweb_sakit_menahun',
                'whereHeader' => null,
                'whereFooter' => null,
            ],
            'penyandang-cacat' => [
                'idReferensi' => 'cacat_id',
                'tabelReferensi' => 'tweb_cacat',
                'whereHeader' => null,
                'whereFooter' => null,
            ],
            'status-perkawinan' => [
                'idReferensi' => 'status_kawin',
                'tabelReferensi' => 'tweb_penduduk_kawin',
                'whereHeader' => null,
                'whereFooter' => null,
            ],
            'asuransi-kesehatan' => [
                'idReferensi' => 'id_asuransi',
                'tabelReferensi' => 'tweb_penduduk_asuransi',
                'whereHeader' => null,
                'whereFooter' => null,
            ],
            default => null,
        };
    }

    /**
     * @param $data_header  collection
     * @param $query_footer collection
     *
     * return array
     */
    private function listFooter($data_header, $query_footer): array|object
    {
        $data_header = collect($data_header);
        if (count($data_header) > 0) {
            $jumlah_laki_laki = $data_header->sum('laki_laki');
            $jumlah_perempuan = $data_header->sum('perempuan');
            $jumlah = $jumlah_laki_laki + $jumlah_perempuan;
        } else {
            $jumlah_laki_laki = 0;
            $jumlah_perempuan = 0;
            $jumlah = 0;
        }

        $total_laki_laki = $query_footer->sum('laki_laki');
        $total_perempuan = $query_footer->sum('perempuan');
        $total = $total_laki_laki + $total_perempuan;

        return [
            [
                'nama' => 'Jumlah',
                'jumlah' => $jumlah,
                'laki_laki' => $jumlah_laki_laki,
                'perempuan' => $jumlah_perempuan,
                'kriteria' => json_encode(['jumlah' => $this->getKategoriStatistik()]),
            ],
            [
                'nama' => 'Belum Mengisi',
                'jumlah' => $total - $jumlah,
                'laki_laki' => $total_laki_laki - $jumlah_laki_laki,
                'perempuan' => $total_perempuan - $jumlah_perempuan,
                'kriteria' => json_encode(['belum mengisi' => $this->getKategoriStatistik()]),
            ],
            [
                'nama' => 'Total',
                'jumlah' => $total,
                'laki_laki' => $total_laki_laki,
                'perempuan' => $total_perempuan,
                'kriteria' => json_encode(['total' => $this->getKategoriStatistik()]),
            ],
        ];
    }

    private function caseRentangUmur(): array|object
    {
        $umur = Umur::countStatistikUmur()->status(Umur::RENTANG)->get();
        $query = $this->countStatistikPendudukHidup();

        return [
            'header' => $umur,
            'footer' => $this->listFooter($umur, $query),
        ];
    }

    private function caseKategoriUmur(): array|object
    {
        $umur = (new Umur())->setKlasifikasi(Umur::KATEGORI)->countStatistikUmur()->status(Umur::KATEGORI)->get();
        $query = $this->countStatistikPendudukHidup();

        return [
            'header' => $umur,
            'footer' => $this->listFooter($umur, $query),
        ];
    }

    private function caseAktaKelahiran(): array|object
    {
        $umur = Umur::countStatistikAkta()->status(Umur::RENTANG)->get();
        $query = $this->countStatistikPendudukHidup();

        return [
            'header' => $umur,
            'footer' => $this->listFooter($umur, $query),
        ];
    }

    private function caseAktaNikah(): array|object
    {
        $umur = Umur::countStatistikAktaNikah()->status(Umur::RENTANG)->get();
        $query = $this->countStatistikPendudukHidup();

        return [
            'header' => $umur,
            'footer' => $this->listFooter($umur, $query),
        ];
    }

    private function caseWithReferensi(string $kategori): array|object
    {
        $referensi = $this->tabelReferensi($kategori);
        $header = $this->countStatistikByKategori($referensi['tabelReferensi'], $referensi['idReferensi'], $referensi['whereHeader']);
        $query = $this->countStatistikPendudukHidup($referensi['whereFooter']);

        return [
            'header' => $header,
            'footer' => $this->listFooter($header, $query),
        ];
    }

    private function countStatistikPendudukHidup(string $where = null): array|object
    {
        $tanggalPeristiwa = null;
        $configDesa = null;
        if (request('config_desa')) {
            $configDesa = request('config_desa');
        }

        if (isset(request('filter')['tahun']) || isset(request('filter')['bulan'])) {
            $periode = [request('filter')['tahun'] ?? date('Y'), request('filter')['bulan'] ?? '12', '01'];
            $tanggalPeristiwa = Carbon::parse(implode('-', $periode))->endOfMonth()->format('Y-m-d');
        }
        $logPenduduk = LogPenduduk::select(['log_penduduk.id_pend'])->peristiwaTerakhir($tanggalPeristiwa, $configDesa)->tidakMati()->toBoundSql();
        $penduduk = Penduduk::countStatistik()->join(DB::raw("($logPenduduk) as log"), 'log.id_pend', '=', 'tweb_penduduk.id');
        if (! isset(request('filter')['tahun']) && ! isset(request('filter')['bulan'])) {
            $penduduk->status();
        }

        if($where) {
            $penduduk->whereRaw($where);
        }

        if ($configDesa) {
            $penduduk->filterDesa();
        }

        return $penduduk->get();
    }

    public function countStatistikByKategori(string $tabelReferensi, string $idReferensi, string $whereFooter = null): array|object
    {
        $query = DB::connection('openkab')->table("{$tabelReferensi}")
            ->select("{$tabelReferensi}.id", "{$tabelReferensi}.nama");

        if (session()->has('desa')) {
            $query->where('tweb_penduduk.config_id', session('desa.id'));
        }

        if (request('config_desa')) {
            $query->where('tweb_penduduk.config_id', request('config_desa'));
        }

        if ($whereFooter) {
            $query->whereRaw($whereFooter);
        }

        $tanggalPeristiwa = null;
        if (isset(request('filter')['tahun']) || isset(request('filter')['bulan'])) {
            $periode = [request('filter')['tahun'] ?? date('Y'), request('filter')['bulan'] ?? '12', '01'];
            $tanggalPeristiwa = Carbon::parse(implode('-', $periode))->endOfMonth()->format('Y-m-d');
        }
        $logPenduduk = LogPenduduk::select(['log_penduduk.id_pend'])->peristiwaTerakhir($tanggalPeristiwa)->tidakMati()->toBoundSql();

        $sql = $query->selectRaw('COUNT(CASE WHEN tweb_penduduk.sex = 1 THEN tweb_penduduk.id END) AS laki_laki')
            ->selectRaw('COUNT(CASE WHEN tweb_penduduk.sex = 2 THEN tweb_penduduk.id END) AS perempuan')
            ->selectRaw("concat('{\"{$idReferensi}\":',{$tabelReferensi}.id,'}') as kriteria")
            ->join('tweb_penduduk', "tweb_penduduk.{$idReferensi}", '=', "{$tabelReferensi}.id", 'left')
            ->where('tweb_penduduk.status_dasar', 1)
            ->join(DB::raw("($logPenduduk) as log"), 'log.id_pend', '=', 'tweb_penduduk.id')
            ->groupBy("{$tabelReferensi}.id", "{$tabelReferensi}.nama");

        return $sql->get();
    }

    private function caseSuku(): array|object
    {
        $umur = Penduduk::CountStatistikSuku()->orderBy('id')->get();
        $query = $this->countStatistikPendudukHidup();

        return [
            'header' => $umur,
            'footer' => $this->listFooter($umur, $query),
        ];
    }

    private function caseKtp()
    {
        $whereFooter = "((DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(tweb_penduduk.tanggallahir)), '%Y')+0)>=17 OR (tweb_penduduk.status_kawin IS NOT NULL AND tweb_penduduk.status_kawin <> 1))";
        $umur = Ktp::countStatistik()->orderBy('id')->get();
        $query = $this->countStatistikPendudukHidup($whereFooter);

        return [
            'header' => $umur,
            'footer' => $this->listFooter($umur, $query),
        ];
    }

    private function caseStatusCovid(): array|object
    {
        $covid = Covid::countStatistik()->orderBy('id')->get();
        $query = $this->countStatistikPendudukHidup();

        return [
            'header' => $covid,
            'footer' => $this->listFooter($covid, $query),
        ];
    }

    public function summary()
    {
        return QueryBuilder::for(Penduduk::status())
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

    public function listPendudukPendidikan()
    {
        return QueryBuilder::for(Penduduk::filterWilayah())
            ->allowedFields('*')  // Tentukan field yang diizinkan untuk dipilih
            ->allowedFilters([  // Tentukan filter yang diizinkan
                AllowedFilter::exact('id'),
                AllowedFilter::exact('sex'),
                AllowedFilter::exact('status'),
                AllowedFilter::exact('status_dasar'),
                AllowedFilter::exact('keluarga.no_kk'),
                AllowedFilter::exact('clusterDesa.dusun'),
                AllowedFilter::exact('clusterDesa.rw'),
                AllowedFilter::exact('clusterDesa.rt'),
                AllowedFilter::callback('search', function ($query, $value) {
                    $query->where(function ($query) use ($value) {
                        $query->where('p.nama', 'like', "%{$value}%")
                            ->orWhere('p.nik', 'like', "%{$value}%")
                            ->orWhere('p.tag_id_card', 'like', "%{$value}%");
                    });
                }),
            ])
            ->allowedSorts([  // Tentukan kolom yang dapat digunakan untuk sorting
                'p.nik',
                'p.nama',
                'p.umur',
                'p.created_at',
            ])
            ->jsonPaginate();  // Melakukan pagination dan mengembalikan data dalam
    }

    public function listPendudukKetenagakerjaan()
    {
        return QueryBuilder::for(Penduduk::filterWilayah())
            ->allowedFields('*')  // Tentukan field yang diizinkan untuk dipilih
            ->allowedFilters([  // Tentukan filter yang diizinkan
                AllowedFilter::exact('id'),
                AllowedFilter::exact('sex'),
                AllowedFilter::exact('status'),
                AllowedFilter::exact('status_dasar'),
                AllowedFilter::exact('keluarga.no_kk'),
                AllowedFilter::exact('clusterDesa.dusun'),
                AllowedFilter::exact('clusterDesa.rw'),
                AllowedFilter::exact('clusterDesa.rt'),
                AllowedFilter::callback('search', function ($query, $value) {
                    $query->where(function ($query) use ($value) {
                        $query->where('nama', 'like', "%{$value}%")
                            ->orWhere('nik', 'like', "%{$value}%")
                            ->orWhere('tag_id_card', 'like', "%{$value}%");
                    });
                }),
            ])
            ->allowedSorts([  // Tentukan kolom yang dapat digunakan untuk sorting
                'nik',
                'nama',
                'umur',
                'created_at',
            ])
            ->jsonPaginate();
    }

    public function listPendudukSyncOpenDk()
    {
        return QueryBuilder::for(Penduduk::withRef())
            ->allowedFields('*')
            ->allowedFilters([
                AllowedFilter::exact('id'),
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
                        $query->where('nama', 'like', "%{$value}%")
                            ->orWhere('nik', 'like', "%{$value}%")
                            ->orWhere('tag_id_card', 'like', "%{$value}%")
                            ->orWhereHas('config', function ($query) use ($value) {
                                $query->where('nama_desa', 'like', "%{$value}%");
                            });
                    });
                }),
            ])
            ->allowedSorts([
                'nik',
                'foto',
                'nama',
                'umur',
                'nama_desa',
                'created_at',
            ])
            ->jsonPaginate();
    }

    protected function setKodeDesa($kodeDesa)
    {
        $this->kodeDesa = $kodeDesa;
    }

    protected function getKodeDesa()
    {
        return $this->kodeDesa;
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
