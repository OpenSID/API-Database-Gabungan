<?php

namespace App\Models;

use App\Enums\SakitMenahunEnum;
use App\Models\Traits\FilterWilayahTrait;
use App\Models\Traits\QueryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

/**
 * @property Enums\TempatDilahirkanEnum  $tempat_dilahirkan
 * @property Enums\JenisKelahiranEnum    $jenis_kelahiran
 * @property Enums\PenolongKelahiranEnum $penolong_kelahiran
 * @property \Carbon\Carbon              $tanggallahir
 */
class PendudukSaja extends Penduduk
{
    /** {@inheritdoc} */
    protected $appends = [];
}
