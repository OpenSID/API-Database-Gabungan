<?php

namespace App\Enums;

class PindahEnum
{
    public const DESA      = 1;
    public const KECAMATAN = 2;
    public const KABUPATEN = 3;
    public const PROVINSI  = 4;

    /**
     * Override method all()
     */
    public static function all(): array
    {
        return [
            self::DESA      => 'Pindah keluar Desa/Kelurahan',
            self::KECAMATAN => 'Pindah keluar Kecamatan',
            self::KABUPATEN => 'Pindah keluar Kabupaten/Kota',
            self::PROVINSI  => 'Pindah keluar Provinsi',
        ];
    }
}
