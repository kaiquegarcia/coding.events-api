<?php

namespace Tests\Unit\Domain\Repositories;

use App\Domain\Entities\Organization;
use App\Domain\Entities\User;
use App\Infrastructure\EntityModels\UserModel;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

class OrganizationRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    private OrganizationRepositoryInterface $repository;
    public function setUp(): void
    {
        parent::setUp();
        $this->repository = app(OrganizationRepositoryInterface::class);
    }

    /**
     * @test
     */
    public function shouldCreateFromEntity(): void
    {
        /** @var OrganizationModel $model */
        $model = OrganizationModel::factory()->makeOne();

        /** @var Organization $entity */
        $entity = $model->getEntity();
        $entity = $this->repository->save($entity);

        self::assertNotNull($entity->getId());
        self::assertNotNull($entity->getCreatedAt());
    }

    /**
     * @test
     */
    public function shouldUpdateFromEntity(): void
    {
        $model = OrganizationModel::factory()->create()->first();
        $entity = $model->getEntity();
        $entity->setFantasyName('Picpay');
        $entity = $this->repository->save($entity);
        self::assertEquals('Picpay', $entity->getFantasyName());
        self::assertEquals($model->id, $entity->getId());
        self::assertNotNull($entity->getUpdatedAt());
    }

    /**
     * @test
     */
    public function shouldListAllAsOrganizationEntity(): void
    {
        OrganizationModel::factory()->count(10)->create();
        $entities = $this->repository->all();
        self::assertCount(10, $entities);
    }

    /**
     * @test
     */
    public function shouldFindOrganization(): void
    {
        $model = OrganizationModel::factory()->count(10)->create()->first();
        $entity = $this->repository->find($model->id);
        self::assertNotNull($entity);
        self::assertEquals($model->id, $entity->getId());
    }

    /**
     * @test
     */
    public function shouldDeleteWithSoftDelete(): void
    {
        $entityModel = OrganizationModel::factory()->create()->first();
        $deleted = $this->repository->delete($entityModel->id);
        self::assertTrue($deleted);

        $entity = $this->repository->find($entityModel->id);
        self::assertNull($entity);

        $model = OrganizationModel::withTrashed()->where('id', $entityModel->id)->get()->first();
        self::assertNotNull($model);
    }

    /**
     * @test
     */
    public function shouldRestoreTrashed(): void
    {
        $model = OrganizationModel::factory()->create()->first();
        $deleted = $this->repository->delete($model->id);
        self::assertTrue($deleted);

        $restored = $this->repository->restore($model->id);
        self::assertTrue($restored);

        $entity = $this->repository->find($model->id);
        self::assertNotNull($entity);
    }

    public function shouldFindOrganizationOwner(): void
    {
        $user = UserModel::factory()->create()->first();
        $model = OrganizationModel::factory([
            'owner_id' => $user->id,
        ])->create()->first();
        $entity = $model->getEntity();

        $owner = $this->repository->findOwnerFrom($entity);
        self::assertInstanceOf(User::class, $owner);
        self::assertEquals($user->id, $owner->getId());
    }
}
