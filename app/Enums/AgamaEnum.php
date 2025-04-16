<?php
namespace App\Enums;

use BenSampo\Enum\Enum;

class AgamaEnum extends Enum
{
    public const ISLAM     = 1;
    public const KRISTEN   = 2;
    public const KATHOLIK  = 3;
    public const HINDU     = 4;
    public const BUDHA     = 5;
    public const KHONGHUCU = 6;
    public const LAINNYA   = 7;

    /**
     * Override method all()
     */
    public static function getDescription($value): string
    {
        return match ($value) {
            self::ISLAM     => 'ISLAM',
            self::KRISTEN   => 'KRISTEN',
            self::KATHOLIK  => 'KATHOLIK',
            self::HINDU     => 'HINDU',
            self::BUDHA     => 'BUDHA',
            self::KHONGHUCU => 'KHONGHUCU',
            self::LAINNYA   => 'Kepercayaan Terhadap Tuhan YME / Lainnya',
            default => '',
        };
    }
}
