<?php

namespace App\Domain\Entities;

use App\Domain\Enums\StatusEnum;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

class User extends AbstractEntity
{
    public function __construct(
        protected ?string $id = null,
        private ?string $first_name = null,
        private ?string $last_name = null,
        private ?string $avatar_url = null,
        private ?string $website = null,
        private ?string $bio = null,
        private StatusEnum|string|null $status = null,
        protected ?string $created_at = null,
        protected ?string $updated_at = null,
        protected ?string $deleted_at = null
    ) {
        if ($status) {
            $this->setStatus($status);
        }
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function getAvatarUrl(): ?string
    {
        return $this->avatar_url;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function getStatus(): ?StatusEnum
    {
        return $this->status;
    }

    #[Pure]
    public function getFullName(): string
    {
        $fullName = $this->getFirstName();
        if ($this->getLastName()) {
            $fullName .= " {$this->getLastName()}";
        }
        return $fullName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->first_name = $firstName;
    }

    public function setLastName(string $lastName): void
    {
        $this->last_name = $lastName;
    }

    public function setAvatarUrl(string $avatarUrl): void
    {
        $this->avatar_url = $avatarUrl;
    }

    public function setWebsite(string $website): void
    {
        $this->website = $website;
    }

    public function setBio(string $bio): void
    {
        $this->bio = $bio;
    }

    public function setStatus(StatusEnum|string $status): void
    {
        if (is_string($status)) {
            $status = StatusEnum::from($status);
        }
        $this->status = $status;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'avatar_url' => $this->avatar_url,
            'website' => $this->website,
            'bio' => $this->bio,
            'status' => $this->status?->value ?? null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }

    public static function fromArray(array $params): static
    {
        return new self(
            id: $params['id'] ?? null,
            first_name: $params['first_name'] ?? null,
            last_name: $params['last_name'] ?? null,
            avatar_url: $params['avatar_url'] ?? null,
            website: $params['website'] ?? null,
            bio: $params['bio'] ?? null,
            status: $params['status'] ?? null,
            created_at: $params['created_at'] ?? null,
            updated_at: $params['updated_at'] ?? null,
            deleted_at: $params['deleted_at'] ?? null
        );
    }
}
