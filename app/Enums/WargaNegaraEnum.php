<?php

namespace App\Enums;

class WargaNegaraEnum
{
    public const WNI                = 1;
    public const WNA                = 2;
    public const DUAKEWARGANEGARAAN = 3;

    /**
     * Override method all()
     */
    public static function all(): array
    {
        return [
            self::WNI                => 'WNI',
            self::WNA                => 'WNA',
            self::DUAKEWARGANEGARAAN => 'DUA KEWARGANEGARAAN',
        ];
    }
}
