<?php

namespace Database\Factories\Infrastructure\EntityModels;

use App\Domain\Enums\PrivacyEnum;
use App\Infrastructure\EntityModels\EmailModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Spatie\Enum\Laravel\Faker\FakerEnumProvider;

class EmailModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EmailModel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $this->faker->addProvider(new FakerEnumProvider($this->faker));
        $email = $this->faker->email();
        [$username, $domain] = explode('@', $email);
        return [
            'username' => $username,
            'domain' => $domain,
            'privacy' => $this->faker->randomEnum(PrivacyEnum::class),
        ];
    }
}
