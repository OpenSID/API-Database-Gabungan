<?php

namespace App\Models;

use App\Models\Enums\StatusEnum;
use App\Models\Traits\FilterWilayahTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DataPresisiAgama extends BaseUuidModel
{
    use HasFactory, FilterWilayahTrait;

    protected $guarded = [];

    protected $table = 'data_presisi_aktivitas_agama';

    public function keluarga()
    {
        return $this->hasOne(Keluarga::class, 'id', 'keluarga_id');
    }

    public function rtm()
    {
        return $this->hasOne(Rtm::class, 'id', 'rtm_id');
    }

    public function penduduk()
    {
        return $this->belongsTo(PendudukSaja::class, 'anggota_id', 'id');
    }

    public function config()
    {
        return $this->hasOne(Config::class, 'id', 'config_id');
    }

    public function anggota()
    {
        return $this->hasMany(DataPresisiAgama::class, 'rtm_id', 'rtm_id')->where('data_presisi_tahun_id', '!=', $this->data_presisi_tahun_id);
    }

    public function listAnggota()
    {
        return $this->anggota()->with('penduduk');
    }

    public function tahun()
    {
        return $this->hasOne(DataPresisiTahun::class, 'uuid', 'data_presisi_tahun_id');
    }

    protected function scopeKepalaRtm($query)
    {
        return $query->join('tweb_rtm', function ($join) {
            $join->on('tweb_rtm.id', '=', 'data_presisi_aktivitas_agama.rtm_id')
                ->on('tweb_rtm.nik_kepala', '=', 'data_presisi_aktivitas_agama.anggota_id');
        });
    }

    protected function scopeTahunAktif($query)
    {
        return $query->whereHas('tahun', function ($query) {
            $query->where('data_presisi_tahun.status', StatusEnum::aktif);
        });
    }

}
