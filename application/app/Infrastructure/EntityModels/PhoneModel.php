<?php

namespace App\Infrastructure\EntityModels;

use App\Domain\Entities\Phone;
use App\Domain\Enums\PrivacyEnum;

class PhoneModel extends AbstractModel
{
    public string $entityClass = Phone::class;
    protected $table = 'phones';
    protected $fillable = [
        'id',
        'country_code',
        'area_code',
        'number',
        'privacy',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected $casts = [
        'privacy' => PrivacyEnum::class . ':nullable',
    ];
}
