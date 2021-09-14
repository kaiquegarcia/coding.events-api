<?php

namespace Tests\Unit\Domain\Repositories;

use App\Domain\Entities\Email;
use App\Domain\Enums\PrivacyEnum;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

class EmailRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    private EmailRepositoryInterface $repository;
    public function setUp(): void
    {
        parent::setUp();
        $this->repository = app(EmailRepositoryInterface::class);
    }

    /**
     * @test
     */
    public function shouldCreateFromEntity(): void
    {
        $email = $this->faker->email();
        [$username, $domain] = explode('@', $email);
        $email = new Email(
            username: $username,
            domain: $domain,
            privacy: PrivacyEnum::SUBSCRIBERS()
        );
        $email = $this->repository->save($email);
        self::assertNotNull($email->getId());
        self::assertNotNull($email->getCreatedAt());
    }

    /**
     * @test
     */
    public function shouldUpdateFromEntity(): void
    {
        $model = EmailModel::factory()->create()->first();
        $email = $model->getEntity();
        $email->setUsername('me1');
        $email = $this->repository->save($email);
        self::assertEquals('me1', $email->getAreaCode());
        self::assertEquals($model->id, $email->getId());
        self::assertNotNull($email->getUpdatedAt());
    }

    /**
     * @test
     */
    public function shouldListAllAsEmailEntity(): void
    {
        EmailModel::factory()->count(10)->create();
        $emails = $this->repository->all();
        self::assertCount(10, $emails);
    }

    /**
     * @test
     */
    public function shouldFindEmail(): void
    {
        $model = EmailModel::factory()->count(10)->create()->first();
        $email = $this->repository->find($model->id);
        self::assertNotNull($email);
        self::assertEquals($model->id, $email->getId());
    }

    /**
     * @test
     */
    public function shouldDeleteWithSoftDelete(): void
    {
        $emailModel = EmailModel::factory()->create()->first();
        $deleted = $this->repository->delete($emailModel->id);
        self::assertTrue($deleted);

        $email = $this->repository->find($emailModel->id);
        self::assertNull($email);

        $model = EmailModel::withTrashed()->where('id', $emailModel->id)->get()->first();
        self::assertNotNull($model);
    }

    /**
     * @test
     */
    public function shouldRestoreTrashed(): void
    {
        $model = EmailModel::factory()->create()->first();
        $deleted = $this->repository->delete($model->id);
        self::assertTrue($deleted);

        $restored = $this->repository->restore($model->id);
        self::assertTrue($restored);

        $email = $this->repository->find($model->id);
        self::assertNotNull($email);
    }
}
