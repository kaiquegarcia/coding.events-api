<?php

namespace Database\Factories;

use App\Domain\Enums\PrivacyEnum;
use App\Infrastructure\EntityModels\PhoneModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Spatie\Enum\Laravel\Faker\FakerEnumProvider;

class PhoneModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PhoneModel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $this->faker->addProvider(new FakerEnumProvider($this->faker));
        return [
            'country_code' => $this->faker->numerify('+##'),
            'area_code' => $this->faker->numerify('##'),
            'number' => $this->faker->numerify('#########'),
            'privacy' => $this->faker->randomEnum(PrivacyEnum::class),
        ];
    }
}
