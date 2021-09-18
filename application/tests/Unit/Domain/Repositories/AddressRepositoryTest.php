<?php

namespace Tests\Unit\Domain\Repositories;

use App\Domain\Entities\Address;
use Faker\Provider\pt_BR\Address as FakerAddress;
use Illuminate\Support\Str;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\Helpers\Traits\AddressInputGenerator;
use Tests\TestCase;

class AddressRepositoryTest extends TestCase
{
    use AddressInputGenerator, DatabaseMigrations;

    private AddressRepositoryInterface $repository;
    public function setUp(): void
    {
        parent::setUp();
        $this->repository = app(AddressRepositoryInterface::class);
        $this->faker->addProvider(new FakerAddress($this->faker));
    }

    /**
     * @test
     */
    public function shouldCreateFromEntity(): void
    {
        $input = $this->getEntityInput();
        $entity = $this->getNewEntityInstance($input);
        $entity = $this->repository->save($entity);
        self::assertNotNull($entity->getId());
        self::assertNotNull($entity->getCreatedAt());
    }

    /**
     * @test
     */
    public function shouldUpdateFromEntity(): void
    {
        $model = AddressModel::factory()->create()->first();
        $entity = $model->getEntity();
        $entity->setTitle('Addr Test');
        $entity = $this->repository->save($entity);
        self::assertEquals('Addr Test', $entity->getValue());
        self::assertEquals($model->id, $entity->getId());
        self::assertNotNull($entity->getUpdatedAt());
    }

    /**
     * @test
     */
    public function shouldListAllAsAddressEntity(): void
    {
        AddressModel::factory()->count(10)->create();
        $entities = $this->repository->all();
        self::assertCount(10, $entities);
    }

    /**
     * @test
     */
    public function shouldFindAddress(): void
    {
        $model = AddressModel::factory()->count(10)->create()->first();
        $entity = $this->repository->find($model->id);
        self::assertNotNull($entity);
        self::assertEquals($model->id, $entity->getId());
    }

    /**
     * @test
     */
    public function shouldDeleteWithSoftDelete(): void
    {
        $entityModel = AddressModel::factory()->create()->first();
        $deleted = $this->repository->delete($entityModel->id);
        self::assertTrue($deleted);

        $entity = $this->repository->find($entityModel->id);
        self::assertNull($entity);

        $model = AddressModel::withTrashed()->where('id', $entityModel->id)->get()->first();
        self::assertNotNull($model);
    }

    /**
     * @test
     */
    public function shouldRestoreTrashed(): void
    {
        $model = AddressModel::factory()->create()->first();
        $deleted = $this->repository->delete($model->id);
        self::assertTrue($deleted);

        $restored = $this->repository->restore($model->id);
        self::assertTrue($restored);

        $entity = $this->repository->find($model->id);
        self::assertNotNull($entity);
    }
}
