<?php

namespace Database\Factories\Infrastructure\EntityModels;

use App\Domain\Enums\DocumentTypeEnum;
use App\Domain\Enums\PrivacyEnum;
use App\Infrastructure\EntityModels\DocumentModel;
use Faker\Provider\pt_BR\Person;
use Illuminate\Database\Eloquent\Factories\Factory;
use Spatie\Enum\Laravel\Faker\FakerEnumProvider;

class DocumentModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DocumentModel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $this->faker->addProvider(new FakerEnumProvider($this->faker));
        $this->faker->addProvider(new Person($this->faker));
        /** @var DocumentTypeEnum $documentType */
        $documentType = $this->faker->randomElement([DocumentTypeEnum::CNPJ(), DocumentTypeEnum::CPF()]);
        $value = $documentType->equals(DocumentTypeEnum::CPF()) ? $this->faker->cpf() : $this->faker->cnpj();
        return [
            'document_type' => $documentType,
            'value' => $value,
            'privacy' => $this->faker->randomEnum(PrivacyEnum::class),
        ];
    }
}
