<?php

namespace App\Infrastructure\EntityModels;

use App\Domain\Entities\User;
use App\Domain\Enums\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserModel extends AbstractModel
{
    use HasFactory;
    public string $entityClass = User::class;
    protected $table = 'users';
    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'avatar_url',
        'website',
        'bio',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected $casts = [
        'status' => StatusEnum::class . ':nullable',
    ];
}
