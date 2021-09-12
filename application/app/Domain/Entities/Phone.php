<?php

namespace App\Domain\Entities;

use App\Domain\Enums\PrivacyEnum;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

class Phone extends AbstractEntity
{
    public function __construct(
        protected ?string $id = null,
        private ?string $country_code = null,
        private ?string $area_code = null,
        private ?string $number = null,
        private PrivacyEnum|string|null $privacy = null,
        protected ?string $created_at = null,
        protected ?string $updated_at = null,
        protected ?string $deleted_at = null
    ) {
        if ($privacy) {
            $this->setPrivacy($privacy);
        }
    }

    public function getCountryCode(): ?string
    {
        return $this->country_code;
    }

    public function getAreaCode(): ?string
    {
        return $this->area_code;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function getPrivacy(): ?PrivacyEnum
    {
        return $this->privacy;
    }

    public function setCountryCode(string $countryCode): void
    {
        $this->country_code = $countryCode;
    }

    public function setAreaCode(string $areaCode): void
    {
        $this->area_code = $areaCode;
    }

    public function setNumber(string $number): void
    {
        $this->number = $number;
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
        'country_code' => "null|string",
        'area_code' => "null|string",
        'number' => "null|string",
        'privacy' => "null|string",
        'created_at' => "null|string",
        'updated_at' => "null|string",
        'deleted_at' => "null|string"
    ])]
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'country_code' => $this->country_code,
            'area_code' => $this->area_code,
            'number' => $this->number,
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
            country_code: $params['country_code'] ?? null,
            area_code: $params['area_code'] ?? null,
            number: $params['number'] ?? null,
            privacy: $params['privacy'] ?? null,
            created_at: $params['created_at'] ?? null,
            updated_at: $params['updated_at'] ?? null,
            deleted_at: $params['deleted_at'] ?? null
        );
    }
}
