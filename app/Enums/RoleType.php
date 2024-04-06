<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class RoleType extends Enum
{
    const admin = 1;
    const streamer = 2;
    const god = 3;
    const admin_twich = 4;
    const mod = 5;
}
