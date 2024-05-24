<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class RangeType extends Enum
{
    const hierro = 7;
    const bronce = 1;
    const plata = 2;
    const oro = 3;
    const platino = 4;
    const esmeralda = 5;
    const diamante = 6;
}
