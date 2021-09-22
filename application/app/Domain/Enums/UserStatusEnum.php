<?php

namespace App\Domain\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self PENDING()
 * @method static self ACTIVE()
 * @method static self BANNED()
 */
class UserStatusEnum extends Enum { }
