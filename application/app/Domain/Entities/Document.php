<?php

namespace App\Domain\Entities;

use App\Domain\Enums\DocumentTypeEnum;
use App\Domain\Enums\PrivacyEnum;
use JetBrains\PhpStorm\ArrayShape;

class Document extends AbstractEntity
{
    public function __construct(
        protected ?string $id = null,
        private DocumentTypeEnum|string|null $document_type = null,
        private ?string $value = null,
        private PrivacyEnum|string|null $privacy = null,
        protected ?string $created_at = null,
        protected ?string $updated_at = null,
        protected ?string $deleted_at = null
    ) {
        if ($document_type) {
            $this->setDocumentType($document_type);
        }
        if ($privacy) {
            $this->setPrivacy($privacy);
        }
    }

    public function getDocumentType(): ?DocumentTypeEnum
    {
        return $this->document_type;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function getPrivacy(): ?PrivacyEnum
    {
        return $this->privacy;
    }

    public function setDocumentType(DocumentTypeEnum|string $documentType): void
    {
        if (is_string($documentType)) {
            $documentType = DocumentTypeEnum::from($documentType);
        }
        $this->document_type = $documentType;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    public function setPrivacy(PrivacyEnum|string $privacy): void
    {
        if (is_string($privacy)) {
            $privacy = PrivacyEnum::from($privacy);
        }
        $this->privacy = $privacy;
    }

    #[ArrayShape([
        'id' => "null|string",
        'document_type' => "null|string",
        'value' => "null|string",
        'privacy' => "null|string",
        'created_at' => "null|string",
        'updated_at' => "null|string",
        'deleted_at' => "null|string"
    ])]
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'document_type' => $this->document_type?->value ?? null,
            'value' => $this->value,
            'privacy' => $this->privacy?->value ?? null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }

    public static function fromArray(array $params): static
    {
        return new self(
            id: $params['id'] ?? null,
            document_type: $params['document_type'] ?? null,
            value: $params['value'] ?? null,
            privacy: $params['privacy'] ?? null,
            created_at: $params['created_at'] ?? null,
            updated_at: $params['updated_at'] ?? null,
            deleted_at: $params['deleted_at'] ?? null
        );
    }
}
