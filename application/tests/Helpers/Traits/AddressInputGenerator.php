<?php

namespace Tests\Helpers\Traits;

use App\Domain\Entities\Address;
use Illuminate\Support\Str;

trait AddressInputGenerator
{
    protected function getEntityInput(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'title' => $this->faker->word(),
            'postal_code' => $this->faker->postcode(),
            'country' => $this->faker->countryCode(),
            'state' => $this->faker->state(),
            'city' => $this->faker->city(),
            'neighborhood' => $this->faker->words(2, true),
            'street' => $this->faker->streetName(),
            'number' => $this->faker->boolean() ? "{$this->faker->randomNumber()}" : 'S/N',
            'complement' => $this->faker->boolean() ? $this->faker->words(4, true) : '',
            'created_at' => $this->now(),
            'updated_at' => $this->tomorrow(),
            'deleted_at' => $this->nextMonth(),
        ];
    }

    protected function getNewEntityInstance(array $input): Address
    {
        return new Address(
            id: $input['id'],
            title: $input['title'],
            postal_code: $input['postal_code'],
            country: $input['country'],
            state: $input['state'],
            city: $input['city'],
            neighborhood: $input['neighborhood'],
            street: $input['street'],
            number: $input['number'],
            complement: $input['complement'],
            privacy: 'PUBLIC',
            created_at: $input['created_at'],
            updated_at: $input['updated_at'],
            deleted_at: $input['deleted_at']
        );
    }
}
