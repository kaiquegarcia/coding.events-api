<?php

namespace App\Domain\Entities;

use App\Domain\Enums\PrivacyEnum;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

class Email extends AbstractEntity
{
    public function __construct(
        protected ?string $id = null,
        private ?string $username = null,
        private ?string $domain = null,
        private PrivacyEnum|string|null $privacy = null,
        protected ?string $created_at = null,
        protected ?string $updated_at = null,
        protected ?string $deleted_at = null
    ) {
        if ($privacy) {
            $this->setPrivacy($privacy);
        }
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getDomain(): ?string
    {
        return $this->domain;
    }

    public function getPrivacy(): ?PrivacyEnum
    {
        return $this->privacy;
    }

    #[Pure]
    public function getFullEmail(): string
    {
        return "{$this->getUsername()}@{$this->getDomain()}";
    }

    public function setUsername(string $countryCode): void
    {
        $this->username = $countryCode;
    }

    public function setDomain(string $areaCode): void
    {
        $this->domain = $areaCode;
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
        'username' => "null|string",
        'domain' => "null|string",
        'privacy' => "null|string",
        'created_at' => "null|string",
        'updated_at' => "null|string",
        'deleted_at' => "null|string"
    ])]
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'domain' => $this->domain,
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
            username: $params['username'] ?? null,
            domain: $params['domain'] ?? null,
            privacy: $params['privacy'] ?? null,
            created_at: $params['created_at'] ?? null,
            updated_at: $params['updated_at'] ?? null,
            deleted_at: $params['deleted_at'] ?? null
        );
    }
}
