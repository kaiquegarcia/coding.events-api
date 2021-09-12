<?php

namespace App\Domain\Entities;

use JsonSerializable;
use stdClass;

abstract class AbstractEntity implements JsonSerializable
{
    protected ?string $id;
    protected ?string $created_at;
    protected ?string $updated_at;
    protected ?string $deleted_at;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updated_at;
    }

    public function getDeletedAt(): ?string
    {
        return $this->deleted_at;
    }

    public function toJson(): string
    {
        return json_encode((array)$this);
    }

    public static function fromJson(array|string|stdClass $json): static
    {
        if (is_string($json)) {
            $json = json_decode($json, true);
        }
        if ($json instanceof stdClass) {
            $json = json_decode(json_encode($json), true);
        }
        return static::fromArray($json);
    }

    abstract public static function fromArray(array $params): static;
}
