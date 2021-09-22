<?php

namespace Database\Factories\Infrastructure\EntityModels;

use App\Domain\Enums\UserStatusEnum;
use App\Infrastructure\EntityModels\UserModel;
use Faker\Provider\pt_BR\Person;
use Illuminate\Database\Eloquent\Factories\Factory;
use Spatie\Enum\Laravel\Faker\FakerEnumProvider;

class UserModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserModel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $this->faker->addProvider(new FakerEnumProvider($this->faker));
        $this->faker->addProvider(new Person($this->faker));
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'avatar_url' => $this->faker->imageUrl(),
            'website' => $this->faker->url(),
            'bio' => $this->faker->paragraphs(10, true),
            'status' => $this->faker->randomEnum(UserStatusEnum::class),
        ];
    }
}
