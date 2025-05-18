<?php

namespace App\Models;

use App\Models\Enums\StatusDasarEnum;
use App\Models\Traits\FilterWilayahTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Support\Facades\DB;

class LogPenduduk extends BaseModel
{
    use HasFactory, FilterWilayahTrait;

    /**
     * {@inheritdoc}
     */
    protected $table = 'log_penduduk';

    /**
     * {@inheritdoc}
     */
    protected $guarded = [];

    /**
     * {@inheritdoc}
     */
    public $timestamps = false;

    const BARU_LAHIR = 1;

    const MATI = 2;

    const PINDAH_KELUAR = 3;

    const HILANG = 4;

    const BARU_PINDAH_MASUK = 5;

    const TIDAK_TETAP_PERGI = 6;

    const PERISTIWA = [1, 2, 3, 4];

    public static function kodePeristiwa(): array
    {
        return [
            self::BARU_LAHIR        => 'Baru Lahir',
            self::MATI              => 'Mati',
            self::PINDAH_KELUAR     => 'Pindah Keluar',
            self::HILANG            => 'Hilang',
            self::BARU_PINDAH_MASUK => 'Baru Pindah Masuk',
            self::TIDAK_TETAP_PERGI => 'Tidak Tetap Pergi',
        ];
    }

    /**
     * Get the post that owns the comment.
     */
    public function penduduk()
    {
        return $this->belongsTo(Penduduk::class, 'id_pend', 'id');
    }

    /**
     * Get the post that owns the comment.
     */
    public function keluarga()
    {
        return $this->hasOneThrough(Keluarga::class, Penduduk::class, 'id', 'id', 'id_pend', 'id_kk');
    }

    public function scopeTahun($query)
    {
        return $query->selectRaw('YEAR(MIN(tgl_peristiwa)) AS tahun_awal, YEAR(MAX(tgl_peristiwa)) AS tahun_akhir');
    }

    public function scopeTidakMati($query)
    {
        return $query->where('kode_peristiwa', '!=', StatusDasarEnum::MATI);
    }

    public function scopePeristiwaSampai($query, $tanggal)
    {
        return $query->where('tgl_peristiwa', '<=', $tanggal);
    }

    public function scopePeristiwaTerakhir($query, $tanggal = null, $configId = null)
    {
        if (! empty($tanggal)) {
            $query->where('tgl_peristiwa', '<=', $tanggal);
        }

        if (! empty($configId)) {
            $query->where('config_id', $configId);
        }

        $subQuery = DB::raw(
            '(SELECT MAX(id) as id, id_pend from log_penduduk group by id_pend) as logMax'
        );

        if (! empty($configId)) {
            $subQuery = DB::raw(
                '(SELECT MAX(id) as id, id_pend from log_penduduk where config_id = '.$configId.' group by id_pend) as logMax'
            );
        }

        return $query->join($subQuery, 'logMax.id', '=', 'log_penduduk.id');
    }

    // public function scopePeristiwaSampaiDengan($query, string $tanggal)
    // {
    //     // $configId = session()->get('config_id', 0);
    //     $configId = identitas('id') ?? 0;
    //     $subQuery = DB::raw(
    //         '(SELECT MAX(id) as id, id_pend from log_penduduk where config_id = ' . $configId . ' and tgl_lapor <= \'' . $tanggal . ' 23:59:59\' group by id_pend) as logMax'
    //     );

    //     return $query->join($subQuery, 'logMax.id', '=', 'log_penduduk.id');
    // }

    public function scopePeristiwaSampaiDengan($query, string $tanggal)
    {
        $configId = session()->get('config_id', 0);
        // $configId = identitas('id');

        // Subquery: dapatkan ID log terakhir per penduduk
        $logMaxSub = DB::connection('openkab')->table('log_penduduk as lp1')
            ->select(DB::raw('MAX(lp1.id) as id'))
            ->where('lp1.config_id', $configId)
            ->where('lp1.tgl_lapor', '<=', $tanggal . ' 23:59:59')
            ->groupBy('lp1.id_pend');

        // Join dengan log_penduduk yang sedang di-query
        return $query->joinSub($logMaxSub, 'logMax', function ($join) {
            $join->on('logMax.id', '=', 'log_penduduk.id');
        });
    }

}
