<?php

namespace Tests\Unit\Domain\Repositories;

use App\Domain\Entities\Phone;
use App\Domain\Enums\PrivacyEnum;
use App\Domain\Repositories\PhoneRepositoryInterface;
use App\Infrastructure\EntityModels\PhoneModel;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

class PhoneRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    private PhoneRepositoryInterface $repository;
    public function setUp(): void
    {
        parent::setUp();
        $this->repository = app(PhoneRepositoryInterface::class);
    }

    /**
     * @test
     */
    public function shouldCreateFromEntity(): void
    {
        /** @var PhoneModel $model */
        $model = PhoneModel::factory()->makeOne();

        /** @var Phone $phone */
        $phone = $model->getEntity();
        $phone = $this->repository->save($phone);

        self::assertNotNull($phone->getId());
        self::assertNotNull($phone->getCreatedAt());
    }

    /**
     * @test
     */
    public function shouldUpdateFromEntity(): void
    {
        $model = PhoneModel::factory()->create()->first();
        $phone = $model->getEntity();
        $phone->setAreaCode('123');
        $phone = $this->repository->save($phone);
        self::assertEquals('123', $phone->getAreaCode());
        self::assertEquals($model->id, $phone->getId());
        self::assertNotNull($phone->getUpdatedAt());
    }

    /**
     * @test
     */
    public function shouldListAllAsPhoneEntity(): void
    {
        PhoneModel::factory()->count(10)->create();
        $phones = $this->repository->all();
        self::assertCount(10, $phones);
    }

    /**
     * @test
     */
    public function shouldFindPhone(): void
    {
        $model = PhoneModel::factory()->count(10)->create()->first();
        $phone = $this->repository->find($model->id);
        self::assertNotNull($phone);
        self::assertEquals($model->id, $phone->getId());
    }

    /**
     * @test
     */
    public function shouldDeleteWithSoftDelete(): void
    {
        $phoneModel = PhoneModel::factory()->create()->first();
        $deleted = $this->repository->delete($phoneModel->id);
        self::assertTrue($deleted);

        $phone = $this->repository->find($phoneModel->id);
        self::assertNull($phone);

        $model = PhoneModel::withTrashed()->where('id', $phoneModel->id)->get()->first();
        self::assertNotNull($model);
    }

    /**
     * @test
     */
    public function shouldRestoreTrashed(): void
    {
        $model = PhoneModel::factory()->create()->first();
        $deleted = $this->repository->delete($model->id);
        self::assertTrue($deleted);

        $restored = $this->repository->restore($model->id);
        self::assertTrue($restored);

        $phone = $this->repository->find($model->id);
        self::assertNotNull($phone);
    }
}
