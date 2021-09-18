<?php

namespace Tests\Unit\Domain\Repositories;

use App\Domain\Entities\Document;
use App\Domain\Enums\DocumentTypeEnum;
use App\Domain\Enums\PrivacyEnum;
use App\Domain\Repositories\DocumentRepositoryInterface;
use App\Infrastructure\EntityModels\DocumentModel;
use Faker\Provider\pt_BR\Person;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

class DocumentRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    private DocumentRepositoryInterface $repository;
    public function setUp(): void
    {
        parent::setUp();
        $this->repository = app(DocumentRepositoryInterface::class);
        $this->faker->addProvider(new Person($this->faker));
    }

    /**
     * @test
     */
    public function shouldCreateFromEntity(): void
    {
        $cpf = $this->faker->cpf();
        $document = new Document(
            document_type: DocumentTypeEnum::CPF(),
            value: $cpf,
            privacy: PrivacyEnum::SUBSCRIBERS()
        );
        $document = $this->repository->save($document);
        self::assertNotNull($document->getId());
        self::assertNotNull($document->getCreatedAt());
    }

    /**
     * @test
     */
    public function shouldUpdateFromEntity(): void
    {
        $model = DocumentModel::factory()->create()->first();
        $document = $model->getEntity();
        $document->setValue('31231');
        $document = $this->repository->save($document);
        self::assertEquals('31231', $document->getValue());
        self::assertEquals($model->id, $document->getId());
        self::assertNotNull($document->getUpdatedAt());
    }

    /**
     * @test
     */
    public function shouldListAllAsDocumentEntity(): void
    {
        DocumentModel::factory()->count(10)->create();
        $documents = $this->repository->all();
        self::assertCount(10, $documents);
    }

    /**
     * @test
     */
    public function shouldFindDocument(): void
    {
        $model = DocumentModel::factory()->count(10)->create()->first();
        $document = $this->repository->find($model->id);
        self::assertNotNull($document);
        self::assertEquals($model->id, $document->getId());
    }

    /**
     * @test
     */
    public function shouldDeleteWithSoftDelete(): void
    {
        $documentModel = DocumentModel::factory()->create()->first();
        $deleted = $this->repository->delete($documentModel->id);
        self::assertTrue($deleted);

        $document = $this->repository->find($documentModel->id);
        self::assertNull($document);

        $model = DocumentModel::withTrashed()->where('id', $documentModel->id)->get()->first();
        self::assertNotNull($model);
    }

    /**
     * @test
     */
    public function shouldRestoreTrashed(): void
    {
        $model = DocumentModel::factory()->create()->first();
        $deleted = $this->repository->delete($model->id);
        self::assertTrue($deleted);

        $restored = $this->repository->restore($model->id);
        self::assertTrue($restored);

        $document = $this->repository->find($model->id);
        self::assertNotNull($document);
    }
}
