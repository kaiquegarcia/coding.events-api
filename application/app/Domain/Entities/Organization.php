<?php

namespace App\Domain\Entities;

use App\Domain\Enums\OrganizationStatusEnum;

class Organization extends AbstractEntity
{
    public function __construct(
        protected ?string $id = null,
        private ?string $owner_id = null,
        private ?string $corporate_name = null,
        private ?string $fantasy_name = null,
        private ?string $logo_url = null,
        private ?string $website = null,
        private ?string $description = null,
        private OrganizationStatusEnum|string|null $status = null,
        protected ?string $created_at = null,
        protected ?string $updated_at = null,
        protected ?string $deleted_at = null
    ) {
        if (!is_null($status)) {
            $this->setStatus($status);
        }
    }

    public function getOwnerId(): ?string
    {
        return $this->owner_id;
    }

    public function getCorporateName(): ?string
    {
        return $this->corporate_name;
    }

    public function getFantasyName(): ?string
    {
        return $this->fantasy_name;
    }

    public function getLogoUrl(): ?string
    {
        return $this->logo_url;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getStatus(): ?OrganizationStatusEnum
    {
        return $this->status;
    }

    public function setOwnerId(string $ownerId): void
    {
        $this->owner_id = $ownerId;
    }

    public function setCorporateName(string $corporateName): void
    {
        $this->corporate_name = $corporateName;
    }

    public function setFantasyName(string $fantasyName): void
    {
        $this->fantasy_name = $fantasyName;
    }

    public function setLogoUrl(string $logoUrl): void
    {
        $this->logo_url = $logoUrl;
    }

    public function setWebsite(string $website): void
    {
        $this->website = $website;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setStatus(OrganizationStatusEnum|string $status): void
    {
        if (is_string($status)) {
            $status = OrganizationStatusEnum::from($status);
        }
        $this->status = $status;
    }

    public static function fromArray(array $params): static
    {
        return new self(
            id: $params['id'] ?? null,
            owner_id: $params['owner_id'] ?? null,
            corporate_name: $params['corporate_name'] ?? null,
            fantasy_name: $params['fantasy_name'] ?? null,
            logo_url: $params['logo_url'] ?? null,
            website: $params['website'] ?? null,
            description: $params['description'] ?? null,
            status: $params['status'] ?? null,
            created_at: $params['created_at'] ?? null,
            updated_at: $params['updated_at'] ?? null,
            deleted_at: $params['deleted_at'] ?? null
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'owner_id' => $this->owner_id,
            'corporate_name' => $this->corporate_name,
            'fantasy_name' => $this->fantasy_name,
            'logo_url' => $this->logo_url,
            'website' => $this->website,
            'description' => $this->description,
            'status' => $this->status?->value,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
