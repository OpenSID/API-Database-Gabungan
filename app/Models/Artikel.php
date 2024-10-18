<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artikel extends Model
{
    /** {@inheritdoc} */
    protected $table = 'artikel';

    public function scopeTahun($query)
    {
        return $query->selectRaw('YEAR(MIN(tgl_upload)) AS tahun_awal, YEAR(MAX(tgl_upload)) AS tahun_akhir');
    }
}
