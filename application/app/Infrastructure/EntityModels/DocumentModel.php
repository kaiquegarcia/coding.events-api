<?php

namespace App\Infrastructure\EntityModels;

use App\Domain\Entities\Document;
use App\Domain\Enums\DocumentTypeEnum;
use App\Domain\Enums\PrivacyEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DocumentModel extends AbstractModel
{
    use HasFactory;
    public string $entityClass = Document::class;
    protected $table = 'documents';
    protected $fillable = [
        'id',
        'document_type',
        'value',
        'privacy',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected $casts = [
        'document_type' => DocumentTypeEnum::class . ':nullable',
        'privacy' => PrivacyEnum::class . ':nullable',
    ];
}
