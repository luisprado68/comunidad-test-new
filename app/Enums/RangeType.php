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
    const hierro = 1;
    const bronce = 2;
    const plata = 3;
    const oro = 4;
    const platino = 5;
    const esmeralda = 6;
    const diamante = 7;
}
