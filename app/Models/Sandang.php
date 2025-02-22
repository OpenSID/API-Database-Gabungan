<?php

namespace App\Models;

use App\Models\Traits\FilterWilayahTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sandang extends BaseModel
{
    use HasFactory, FilterWilayahTrait;

    protected $guarded = [];

    protected $primaryKey = 'uuid';

    protected $table = 'data_presisi_sandang';

    public function keluarga()
    {
        return $this->hasOne(Keluarga::class, 'id', 'id_keluarga');
    }

    public function rtm()
    {
        return $this->hasOne(Rtm::class, 'id', 'id_rtm');
    }

    public function penduduk()
    {
        return $this->belongsTo(Penduduk::class, 'id_anggota', 'id');
    }

    public function config()
    {
        return $this->hasOne(Config::class, 'id', 'config_id');
    }

}
