<?php
namespace App\Enums;

use BenSampo\Enum\Enum;

class FrekwensiAktivitasKeagamaanEnum extends Enum
{
    public const SATU_KALI               = 1;
    public const DUA_KALI               = 2;
    public const TIGA_KALI               = 3;
    public const BESAR_TIGA_KALI    = 4;

    /**
     * Override method all()
     */
    public static function getDescription($value): string
    {
        return match ($value) {
            self::SATU_KALI               => '1 Kali',
            self::DUA_KALI               => '2 Kali',
            self::TIGA_KALI               => '3 Kali',
            self::BESAR_TIGA_KALI         => '>3 Kali',
            default => '',
        };
    }
}
