<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

class StatusKeanggotaanEnum extends Enum
{
    public const KETUA           = 1;
    public const WAKIL_KETUA     = 2;
    public const SEKRETARIS           = 3;
    public const WAKIL_SEKRETARIS     = 4;
    public const BENDAHARA           = 5;
    public const WAKIL_BENDAHARA     = 6;
    public const ANGGOTA              = 7;

    /**
     * Override method all()
     */
    public static function getDescription(mixed $value): string
    {
        return match ($value) {
            self::KETUA               => 'Ketua/Kepala Adat',
            self::WAKIL_KETUA         => 'Wakil Ketua Adat',
            self::SEKRETARIS           => 'Sekretaris',
            self::WAKIL_SEKRETARIS     => 'Wakil Sekretaris',
            self::BENDAHARA           => 'Bendahara',
            self::WAKIL_BENDAHARA     => 'Wakil Bendahara',
            self::ANGGOTA              => 'Anggota',
            default => '',
        };
    }
}
