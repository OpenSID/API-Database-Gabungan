<?php

namespace App\Models;

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
