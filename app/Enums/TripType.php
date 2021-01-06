<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OneWay()
 * @method static static RoundTrip()
 * @method static static OpenJaw()
 * @method static static MultiCity()
 * @method static static Unsupported()
 */
final class TripType extends Enum
{
    const OneWay =   0;
    const RoundTrip =   1;
    const OpenJaw = 2;
    const MultiCity  = 3;
    const Unsupported  = 4;

    public static function getDescription($value): string
    {
        if ($value === self::OneWay) {
            return 'one-way';
        }

        if ($value === self::RoundTrip) {
            return 'round-trip';
        }

        if ($value === self::OpenJaw) {
            return 'open-jaw';
        }

        if ($value === self::MultiCity) {
            return 'multi-city';
        }

        if ($value === self::Unsupported) {
            return 'unsupported';
        }

        return parent::getDescription($value);
    }
}
