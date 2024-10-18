<?php

namespace App\Models\Traits;

use App\Models\Config;

trait QueryTrait
{
    /**
     * Select untuk Statistik menggunakan case.
     */
    public function selectCountStatistikWithCase($query)
    {
        return $query
            ->selectRaw('COUNT(CASE WHEN tweb_penduduk.sex = 1 THEN tweb_penduduk.id END) AS laki_laki')
            ->selectRaw('COUNT(CASE WHEN tweb_penduduk.sex = 2 THEN tweb_penduduk.id END) AS perempuan');
    }

    /**
     * Select untuk Statistik menggunakan subquery.
     */
    public function selectCountStatistikWithSubQuery($query, $where = null, $umur = false)
    {
        if ($umur) {
            $where = "AND (DATE_FORMAT(FROM_DAYS(TO_DAYS( NOW()) - TO_DAYS(tanggallahir)) , '%Y')+0)>=dari AND (DATE_FORMAT(FROM_DAYS( TO_DAYS(NOW()) - TO_DAYS(tanggallahir)) , '%Y')+0) <= sampai $where";
        }

        return $query
            ->selectRaw("(SELECT COUNT(tweb_penduduk.id) FROM tweb_penduduk WHERE tweb_penduduk.`sex` = '1' AND tweb_penduduk.`status_dasar` = 1 $where) as laki_laki")
            ->selectRaw("(SELECT COUNT(tweb_penduduk.id) FROM tweb_penduduk WHERE tweb_penduduk.`sex` = '2' AND tweb_penduduk.`status_dasar` = 1 $where) as perempuan");
    }

    public function scopeWhereRaws($query, $where)
    {
        return $query->when($where, function ($query) use ($where) {
            $query->whereRaw($where);
        });
    }

    public function scopeConfigId($query)
    {
        // from web request
        if (session()->has('desa')) {
            $query->where("{$this->table}.config_id", session('desa.id'));
        }

        // from api request
        if (request()->hasHeader('X-Desa') && ! empty(request()->header('X-Desa'))) {
            if ($config = Config::where('kode_desa', request()->header('X-Desa'))->first()) {
                $query->where("{$this->table}.config_id", $config->id);
            }
        }

        return $query;
    }

    public function scopeFilters($query, array $filters = [], $column = self::CREATED_AT)
    {
        return $query->when($filters['tahun'], function ($query) use ($filters, $column) {
            $query->whereYear($column, '<=', $filters['tahun'])
                ->when($filters['bulan'], function ($query) use ($filters, $column) {
                    $query->whereMonth($column, '<=', $filters['bulan']);
                });
        });
    }

    public function scopeMinMaxTahun($query, $column = self::CREATED_AT)
    {
        return $query->selectRaw("YEAR(MIN({$column})) AS tahun_awal, YEAR(MAX({$column})) AS tahun_akhir");
    }
}