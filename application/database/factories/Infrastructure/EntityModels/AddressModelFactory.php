<?php

namespace Database\Factories\Infrastructure\EntityModels;

use App\Domain\Enums\PrivacyEnum;
use App\Infrastructure\EntityModels\AddressModel;
use Faker\Provider\pt_BR\Address as FakerAddress;
use Illuminate\Database\Eloquent\Factories\Factory;
use Spatie\Enum\Laravel\Faker\FakerEnumProvider;

class AddressModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AddressModel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $this->faker->addProvider(new FakerEnumProvider($this->faker));
        $this->faker->addProvider(new FakerAddress($this->faker));
        return [
            'title' => $this->faker->word(),
            'postal_code' => $this->faker->postcode(),
            'country' => $this->faker->countryCode(),
            'state' => $this->faker->state(),
            'city' => $this->faker->city(),
            'neighborhood' => $this->faker->words(2, true),
            'street' => $this->faker->streetName(),
            'number' => $this->faker->boolean() ? "{$this->faker->randomNumber()}" : 'S/N',
            'complement' => $this->faker->boolean() ? $this->faker->words(4, true) : '',
            'privacy' => $this->faker->randomEnum(PrivacyEnum::class),
        ];
    }
}
