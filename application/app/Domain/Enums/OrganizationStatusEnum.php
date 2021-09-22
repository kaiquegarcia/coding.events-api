<?php

namespace App\Domain\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self DRAFT()
 * @method static self UNDER_REVIEW()
 * @method static self ACTIVE()
 */
class OrganizationStatusEnum extends Enum { }
