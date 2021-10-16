<?php

namespace App\Infrastructure\EntityModels;

use App\Domain\Entities\Organization;
use App\Domain\Enums\OrganizationStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrganizationModel extends AbstractModel
{
    use HasFactory;
    public string $entityClass = Organization::class;
    protected $table = 'organizations';
    protected $fillable = [
        'id',
        'owner_id',
        'corporate_name',
        'fantasy_name',
        'logo_url',
        'website',
        'description',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected $casts = [
        'status' => OrganizationStatusEnum::class . ':nullable',
    ];
}
