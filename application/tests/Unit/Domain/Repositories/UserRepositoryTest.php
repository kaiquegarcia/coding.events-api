<?php

namespace Tests\Unit\Domain\Repositories;

use App\Domain\Entities\User;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Infrastructure\EntityModels\UserModel;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    private UserRepositoryInterface $repository;
    public function setUp(): void
    {
        parent::setUp();
        $this->repository = app(UserRepositoryInterface::class);
    }

    /**
     * @test
     */
    public function shouldCreateFromEntity(): void
    {
        /** @var UserModel $model */
        $model = UserModel::factory()->makeOne();

        /** @var User $entity */
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
        $model = UserModel::factory()->create()->first();
        $entity = $model->getEntity();
        $entity->setFirstName('Elton');
        $entity = $this->repository->save($entity);
        self::assertEquals('Elton', $entity->getFirstName());
        self::assertEquals($model->id, $entity->getId());
        self::assertNotNull($entity->getUpdatedAt());
    }

    /**
     * @test
     */
    public function shouldListAllAsUserEntity(): void
    {
        UserModel::factory()->count(10)->create();
        $entities = $this->repository->all();
        self::assertCount(10, $entities);
    }

    /**
     * @test
     */
    public function shouldFindUser(): void
    {
        $model = UserModel::factory()->count(10)->create()->first();
        $entity = $this->repository->find($model->id);
        self::assertNotNull($entity);
        self::assertEquals($model->id, $entity->getId());
    }

    /**
     * @test
     */
    public function shouldDeleteWithSoftDelete(): void
    {
        $entityModel = UserModel::factory()->create()->first();
        $deleted = $this->repository->delete($entityModel->id);
        self::assertTrue($deleted);

        $entity = $this->repository->find($entityModel->id);
        self::assertNull($entity);

        $model = UserModel::withTrashed()->where('id', $entityModel->id)->get()->first();
        self::assertNotNull($model);
    }

    /**
     * @test
     */
    public function shouldRestoreTrashed(): void
    {
        $model = UserModel::factory()->create()->first();
        $deleted = $this->repository->delete($model->id);
        self::assertTrue($deleted);

        $restored = $this->repository->restore($model->id);
        self::assertTrue($restored);

        $entity = $this->repository->find($model->id);
        self::assertNotNull($entity);
    }
}
