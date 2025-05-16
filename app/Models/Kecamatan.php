<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Kecamatan extends BaseModel
{
    use HasFactory;

    /** {@inheritdoc} */
    protected $table = 'config';

    /**
     * Get all of the penduduk for the Config.
     */
    public function penduduk(): HasManyThrough
    {
        return $this->hasManyThrough(Penduduk::class, Config::class, 'kode_kecamatan', 'config_id', 'kode_kecamatan', 'id');
    }
}
