<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Builder;

class Config extends BaseModel
{
    use HasFactory;

    /** {@inheritdoc} */
    protected $table = 'config';

    /**
     * Get all of the artikel for the Config.
     */
    public function artikel(): HasMany
    {
        return $this->hasMany(Artikel::class, 'config_id', 'id');
    }

    /**
     * Get all of the traffic for the Config.
     */
    public function traffic(): HasMany
    {
        return $this->hasMany(Traffic::class, 'config_id', 'id');
    }

    /**
     * Get all of the penduduk for the Config.
     */
    public function penduduk(): HasMany
    {
        return $this->hasMany(Penduduk::class, 'config_id', 'id');
    }

    /**
     * Get all of the rtm for the Config.
     */
    public function rtm(): HasMany
    {
        return $this->hasMany(Rtm::class, 'config_id', 'id');
    }

    /**
     * Get all of the Keluarga for the Config.
     */
    public function Keluarga(): HasMany
    {
        return $this->hasMany(Keluarga::class, 'config_id', 'id');
    }

    public function scopeOrderByArtikel($query)
    {
        return $query->orderByRaw('(SELECT COUNT(*) FROM artikel WHERE config_id = config.id) DESC');
    }

    public function scopeOrderByTraffic($query)
    {
        return $query->orderByRaw('(SELECT sum(jumlah) FROM sys_traffic WHERE config_id = config.id) DESC');
    }

    /**
     * Get the sebutanDesa associated with the Config.
     */
    public function sebutanDesa(): HasOne
    {
        return $this->hasOne(SettingAplikasi::class, 'config_id', 'id')->where('key', 'sebutan_desa');
    }

    // public function scopeAppKey($query, $appKey = null)
    // {
    //     return $query->where('app_key', $appKey ?? get_app_key());
    // }

    public function scopeAppKey($query)
    {
        if (Schema::hasColumn($this->table, 'app_key')) {
            $query->where('app_key', get_app_key());
        }

        return $query;
    }

}
