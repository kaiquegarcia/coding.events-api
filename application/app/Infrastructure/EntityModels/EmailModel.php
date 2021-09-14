<?php

namespace App\Infrastructure\EntityModels;

use App\Domain\Entities\Email;
use App\Domain\Enums\PrivacyEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmailModel extends AbstractModel
{
    use HasFactory;
    public string $entityClass = Email::class;
    protected $table = 'emails';
    protected $fillable = [
        'id',
        'username',
        'domain',
        'privacy',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected $casts = [
        'privacy' => PrivacyEnum::class . ':nullable',
    ];
}
