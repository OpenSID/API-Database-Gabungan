<?php

namespace App\Models;

use App\Models\Traits\FilterWilayahTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataPresisiSeniBudaya extends BaseModel
{
    use HasFactory, FilterWilayahTrait;

    protected $guarded = [];

    protected $primaryKey = 'uuid';

    protected $table = 'data_presisi_seni_budaya';

    protected $casts = [
        'jenis_seni_yang_dikuasai' => 'json'
    ];

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
        return $this->belongsTo(Penduduk::class, 'anggota_id', 'id');
    }

    public function config()
    {
        return $this->hasOne(Config::class, 'id', 'config_id');
    }

}
