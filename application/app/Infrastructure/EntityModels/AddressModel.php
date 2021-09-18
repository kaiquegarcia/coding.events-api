<?php

namespace App\Infrastructure\EntityModels;

use App\Domain\Entities\Address;
use App\Domain\Enums\PrivacyEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AddressModel extends AbstractModel
{
    use HasFactory;
    public string $entityClass = Address::class;
    protected $table = 'addresses';
    protected $fillable = [
        'id',
        'title',
        'postal_code',
        'country',
        'state',
        'city',
        'neighborhood',
        'street',
        'number',
        'complement',
        'privacy',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected $casts = [
        'privacy' => PrivacyEnum::class . ':nullable',
    ];
}
