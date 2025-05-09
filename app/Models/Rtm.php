<?php

namespace App\Models;

use App\Models\Traits\FilterWilayahTrait;
use App\Models\Traits\QueryTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\hasOne;

class Rtm extends BaseModel
{
    use FilterWilayahTrait;
    use QueryTrait;

    public const KATEGORI_STATISTIK = [
        'bdt' => 'BDT',
    ];

    /** {@inheritdoc} */
    protected $table = 'tweb_rtm';

    public $timestamps = false;

    protected $appends = [
        'jumlah_kk',
    ];

    /**
     * Define a one-to-one relationship.
     *
     * @return hasOne
     */
    public function kepalaKeluarga()
    {
        return $this->hasOne(Penduduk::class, 'id', 'nik_kepala');
    }
    /**
     * Definisi dengan nama baru agar tidak menjalankan query yang tidak dibutuhkan karena pada
     * model penduduk sudah ada variable $appends
     *
     * @return hasOne
     */
    public function kepalaKeluargaSaja()
    {
        return $this->hasOne(PendudukSaja::class, 'id', 'nik_kepala');
    }

    /**
     * Define a one-to-one relationship.
     *
     * @return hasMany
     */
    public function dataPresisiKesehatans(): HasMany
    {
        return $this->hasMany(DataPresisiKesehatan::class, 'rtm_id', 'id');
    }

    /**
     * Define a one-to-one relationship.
     *
     * @return hasOne
     */
    public function dataPresisiKesehatan(): hasOne
    {
        return $this->hasOne(DataPresisiKesehatan::class, 'rtm_id', 'id');
    }

     /**
     * Define a one-to-one relationship.
     *
     * @return hasMany
     */
    public function dataPresisiPangans(): HasMany
    {
        return $this->hasMany(DataPresisiPangan::class, 'rtm_id', 'id');
    }

    /**
     * Define a one-to-one relationship.
     *
     * @return hasOne
     */
    public function dataPresisiPangan(): hasOne
    {
        return $this->hasOne(DataPresisiPangan::class, 'rtm_id', 'id');
    }

    /**
     * Define a one-to-one relationship.
     *
     * @return hasMany
     */
    public function dataPresisiPendidikans(): HasMany
    {
        return $this->hasMany(DataPresisiPendidikan::class, 'rtm_id', 'id');
    }

    /**
     * Define a one-to-one relationship.
     *
     * @return hasOne
     */
    public function dataPresisiPendidikan(): hasOne
    {
        return $this->hasOne(DataPresisiPendidikan::class, 'rtm_id', 'id');
    }

    /**
     * Define a one-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function anggota()
    {
        return $this->hasMany(Penduduk::class, 'id_rtm', 'no_kk')->status();
    }

    public function ho_anggota()
    {
        return $this->hasOne(Penduduk::class, 'id_rtm', 'no_kk')->status();
    }

    /**
     * Scope query untuk bdt.
     *
     * @return Builder
     */
    public function scopeBdt($query, $value = false)
    {
        if ($value) {
            return $query->where('bdt', '!=', null);
        }

        return $query->where('bdt', '=', null);
    }

    public function scopeCountStatistik($query)
    {
        return $this->scopeConfigId($query)
            ->selectRaw('COUNT(CASE WHEN tweb_penduduk.sex = 1 THEN tweb_penduduk.id END) AS laki_laki')
            ->selectRaw('COUNT(CASE WHEN tweb_penduduk.sex = 2 THEN tweb_penduduk.id END) AS perempuan')
            ->join('tweb_penduduk', 'tweb_penduduk.id', '=', 'tweb_rtm.nik_kepala')
            ->where('tweb_penduduk.status_dasar', 1)
            ->groupBy('tweb_rtm.id');
    }

    /**
     * Scope untuk status rtm berdasarkan penduduk hidup.
     */
    public function scopeStatus($query, $value = 1)
    {
        return $query->whereHas('kepalaKeluarga', static function ($query) use ($value) {
            $query->status($value)->where('rtm_level', '1');
        });
    }

    public function getJumlahKkAttribute()
    {
        return $this->anggota()->distinct('id_kk')->count('id_kk');
    }

    public function config()
    {
        return $this->hasOne(Config::class, 'id', 'config_id');
    }

    public function dtks()
    {
        return $this->hasOne(DTKS::class, 'id_rtm', 'id');
    }
}
