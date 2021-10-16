<?php

namespace Database\Factories\Infrastructure\EntityModels;

use App\Domain\Enums\OrganizationStatusEnum;
use App\Infrastructure\EntityModels\OrganizationModel;
use Faker\Provider\pt_BR\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Spatie\Enum\Laravel\Faker\FakerEnumProvider;

class OrganizationModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrganizationModel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $this->faker->addProvider(new FakerEnumProvider($this->faker));
        $this->faker->addProvider(new Company($this->faker));
        return [
            'owner_id' => $this->faker->uuid(),
            'corporate_name' => $this->faker->company() . ' LTDA',
            'fantasy_name' => $this->faker->company(),
            'logo_url' => $this->faker->imageUrl(),
            'website' => $this->faker->url(),
            'description' => $this->faker->paragraphs(10, true),
            'status' => $this->faker->randomEnum(OrganizationStatusEnum::class),
        ];
    }
}
