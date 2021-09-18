<?php

namespace App\Domain\Entities;

use App\Domain\Enums\DocumentTypeEnum;
use App\Domain\Enums\PrivacyEnum;
use JetBrains\PhpStorm\ArrayShape;

class Address extends AbstractEntity
{
    public function __construct(
        protected ?string $id = null,
        private ?string $title = null,
        private ?string $postal_code = null,
        private ?string $country = null,
        private ?string $state = null,
        private ?string $city = null,
        private ?string $neighborhood = null,
        private ?string $street = null,
        private ?string $number = null,
        private ?string $complement = null,
        private PrivacyEnum|string|null $privacy = null,
        protected ?string $created_at = null,
        protected ?string $updated_at = null,
        protected ?string $deleted_at = null
    ) {
        if ($privacy) {
            $this->setPrivacy($privacy);
        }
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getPostalCode(): ?string
    {
        return $this->postal_code;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function getNeighborhood(): ?string
    {
        return $this->neighborhood;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function getComplement(): ?string
    {
        return $this->complement;
    }

    public function getPrivacy(): ?PrivacyEnum
    {
        return $this->privacy;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setPostalCode(string $postalCode): void
    {
        $this->postal_code = $postalCode;
    }

    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    public function setState(string $state): void
    {
        $this->state = $state;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function setNeighborhood(string $neighborhood): void
    {
        $this->neighborhood = $neighborhood;
    }

    public function setStreet(string $street): void
    {
        $this->street = $street;
    }

    public function setNumber(string $number): void
    {
        $this->number = $number;
    }

    public function setComplement(string $complement): void
    {
        $this->complement = $complement;
    }

    public function setPrivacy(PrivacyEnum|string $privacy): void
    {
        if (is_string($privacy)) {
            $privacy = PrivacyEnum::from($privacy);
        }
        $this->privacy = $privacy;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'postal_code' => $this->postal_code,
            'country' => $this->country,
            'state' => $this->state,
            'city' => $this->city,
            'neighborhood' => $this->neighborhood,
            'street' => $this->street,
            'number' => $this->number,
            'complement' => $this->complement,
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
            title: $params['title'] ?? null,
            postal_code: $params['postal_code'] ?? null,
            country: $params['country'] ?? null,
            state: $params['state'] ?? null,
            city: $params['city'] ?? null,
            neighborhood: $params['neighborhood'] ?? null,
            street: $params['street'] ?? null,
            number: $params['number'] ?? null,
            complement: $params['complement'] ?? null,
            privacy: $params['privacy'] ?? null,
            created_at: $params['created_at'] ?? null,
            updated_at: $params['updated_at'] ?? null,
            deleted_at: $params['deleted_at'] ?? null
        );
    }
}
