<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
class StatusPendudukEnum extends Enum
{
    public const TETAP = 1;

    public const TIDAK_TETAP = 2;

    /**
     * Override method all().
     */
    public static function all(): array
    {
        return [
            self::TETAP => 'Tetap',
            self::TIDAK_TETAP => 'Tidak Tetap',
        ];
    }
}
