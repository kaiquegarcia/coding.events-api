<?php

namespace Tests\Unit\Domain\Repositories;

use App\Domain\Entities\Organization;
use App\Domain\Entities\User;
use App\Domain\Repositories\OrganizationRepositoryInterface;
use App\Infrastructure\EntityModels\OrganizationModel;
use App\Infrastructure\EntityModels\UserModel;
use Illuminate\Support\Collection;
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
        $model = OrganizationModel::factory()->createOne();
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
        $entityModel = OrganizationModel::factory()->createOne();
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
        $model = OrganizationModel::factory()->createOne();
        $deleted = $this->repository->delete($model->id);
        self::assertTrue($deleted);

        $restored = $this->repository->restore($model->id);
        self::assertTrue($restored);

        $entity = $this->repository->find($model->id);
        self::assertNotNull($entity);
    }

    public function shouldFindOrganizationOwner(): void
    {
        $user = UserModel::factory()->createOne();
        $model = OrganizationModel::factory([
            'owner_id' => $user->id,
        ])->createOne();
        $entity = $model->getEntity();

        $owner = $this->repository->findOwnerFrom($entity);
        self::assertInstanceOf(User::class, $owner);
        self::assertEquals($user->id, $owner->getId());
    }

    public function shouldAddOwnerAsOrganizationManager(): void
    {
        $user = UserModel::factory()->createOne();
        $organization = OrganizationModel::factory([
            'owner_id' => $user->id,
        ])->createOne();
        $relationships = OrganizationManagerModel::query()
            ->where('organization_id', $organization->id)
            ->get();
        self::assertCount(1, $relationships);
        self::assertEquals($user->id, $relationships->first()->user_id);
    }

    public function shouldTransferOrganizationOwnership(): void
    {
        $user = UserModel::factory()->createOne();
        $organization = OrganizationModel::factory([
            'owner_id' => $user->id,
        ])->createOne();
        $entity = $organization->getEntity();
        $newOwner = UserModel::factory()->createOne();

        $this->repository->transferOwnership($entity, $newOwner->getEntity());

        $owner = $this->repository->findOwnerFrom($entity);
        self::assertEquals($newOwner->id, $owner->getId());

        $relationships = OrganizationManagerModel::query()
            ->where('organization_id', $organization->id)
            ->get();
        self::assertCount(2, $relationships);
        foreach ($relationships as $relationship) {
            self::assertContains($relationship->user_id, [$user->id, $newOwner->id]);
            self::assertEquals(OrganizationManagerStatusEnum::ACCEPTED(), $relationship->status);
            // old owner keeps as admin
            if ($relationship->user_id === $user->id) {
                self::assertEquals(OrganizationManagerRolesEnum::ADMIN(), $relationship->role);
            }
        }
    }

    public function shouldNotTransferOrganizationOwnershipToTheCurrentOwner(): void
    {
        $user = UserModel::factory()->createOne();
        $model = OrganizationModel::factory([
            'owner_id' => $user->id,
        ])->createOne();
        $entity = $model->getEntity();

        self::expectException(UserArealdyOwnsTheOrganizationException::class);
        $this->repository->transferOwnership($entity, $user->getEntity());
    }

    public function shouldInviteOrganizationManagers(): void
    {
        $owner = UserModel::factory()->createOne();
        /** @var Organization $organization */
        $organization = OrganizationModel::factory(['owner_id' => $owner->id])
            ->createOne()
            ->getEntity();

        $managersCount = 5;
        $managers = UserModel::factory()->count($managersCount)->create();
        foreach($managers as $manager) {
            $this->repository->inviteManager(
                $organization,
                $manager->getEntity(),
                OrganizationManagerRolesEnum::ADMIN()
            );
        }
        $relationships = OrganizationManagerModel::query()
            ->where('organization_id', $organization->getId())
            ->get();

        self::assertEquals(
            $managersCount + 1, // owner counts too
            count($relationships)
        );

        foreach($relationships as $relationship) {
            $expectedStatus = $relationship->user_id === $owner->id
                ? OrganizationManagerStatusEnum::ACCEPTED()
                : OrganizationManagerStatusEnum::INVITED();
            self::assertEquals($expectedStatus, $relationship->status);
        }
    }

    public function shouldNotListUnacceptedOrganizationManagers(): void
    {
        $owner = UserModel::factory()->createOne();
        /** @var Organization $organization */
        $organization = OrganizationModel::factory(['owner_id' => $owner->id])
            ->createOne()
            ->getEntity();
        $manager = UserModel::factory()
            ->createOne()
            ->getEntity();

        $this->repository->inviteManager(
            $organization,
            $manager,
            OrganizationManagerRolesEnum::ADMIN()
        );

        /** @var Collection $managers */
        $managers = $this->repository->findManagersFrom($organization);
        self::assertCount(1, $managers);
        self::assertEquals($owner->id, $managers->first()->getId());
    }

    public function shouldNotInviteTheOwner(): void
    {
        $owner = UserModel::factory()->createOne();
        /** @var Organization $organization */
        $organization = OrganizationModel::factory(['owner_id' => $owner->id])
            ->createOne()
            ->getEntity();

        self::expectException(CannotInviteOrganizationOwnerException::class);

        $this->repository->inviteManager(
            $organization,
            $owner->getEntity(),
            OrganizationManagerRolesEnum::ADMIN()
        );
    }

    public function shouldNotInviteTheSameManagerMoreThanOnce(): void
    {
        $owner = UserModel::factory()->createOne();
        /** @var Organization $organization */
        $organization = OrganizationModel::factory(['owner_id' => $owner->id])
            ->createOne()
            ->getEntity();
        $manager = UserModel::factory()
            ->createOne()
            ->getEntity();

        $this->repository->inviteManager(
            $organization,
            $manager,
            OrganizationManagerRolesEnum::ADMIN()
        );

        self::expectException(ManagerAlreadyInvitedException::class);
        $this->repository->inviteManager(
            $organization,
            $manager,
            OrganizationManagerRolesEnum::ADMIN()
        );
    }

    public function shouldAcceptOrganizationManagerInvitation(): void
    {
        /** @var Organization $organization */
        $organization = OrganizationModel::factory()
            ->createOne()
            ->getEntity();
        /** @var User $manager */
        $manager = UserModel::factory()
            ->createOne()
            ->getEntity();

        $this->repository->inviteManager(
            $organization,
            $manager,
            OrganizationManagerRolesEnum::ADMIN()
        );

        $this->repository->acceptManager(
            $organization,
            $manager
        );

        $relationship = OrganizationManagerModel::query()
            ->where('organization_id', $organization->getId())
            ->where('user_id', $manager->getId())
            ->get()
            ->first();
        self::assertEquals(OrganizationManagerStatusEnum::ACCEPTED(), $relationship->status);
    }

    public function shouldRejectOrganizationManagerInvitation(): void
    {
        $owner = UserModel::factory()->createOne();
        /** @var Organization $organization */
        $organization = OrganizationModel::factory(['owner_id' => $owner->id])
            ->createOne()
            ->getEntity();
        /** @var User $manager */
        $manager = UserModel::factory()
            ->createOne()
            ->getEntity();

        $this->repository->inviteManager(
            $organization,
            $manager,
            OrganizationManagerRolesEnum::ADMIN()
        );

        $this->repository->rejectManager(
            $organization,
            $manager
        );

        $relationship = OrganizationManagerModel::query()
            ->where('organization_id', $organization->getId())
            ->where('user_id', $manager->getId())
            ->get()
            ->first();
        self::assertEquals(OrganizationManagerStatusEnum::REJECTED(), $relationship->status);
    }

    public function shouldNotRejectAlreadyAcceptedOrganizationManagerInvitations(): void
    {
        $owner = UserModel::factory()->createOne();
        /** @var Organization $organization */
        $organization = OrganizationModel::factory(['owner_id' => $owner->id])
            ->createOne()
            ->getEntity();
        /** @var User $manager */
        $manager = UserModel::factory()
            ->createOne()
            ->getEntity();

        $this->repository->inviteManager(
            $organization,
            $manager,
            OrganizationManagerRolesEnum::ADMIN()
        );

        $this->repository->acceptManager(
            $organization,
            $manager
        );

        self::expectException(InvitationAlreadyAcceptedException::class);
        $this->repository->rejectManager(
            $organization,
            $manager
        );
    }

    public function shouldRevokeOrganizationManager(): void
    {
        $owner = UserModel::factory()->createOne();
        /** @var Organization $organization */
        $organization = OrganizationModel::factory(['owner_id' => $owner->id])
            ->createOne()
            ->getEntity();
        /** @var User $manager */
        $manager = UserModel::factory()
            ->createOne()
            ->getEntity();

        $this->repository->inviteManager(
            $organization,
            $manager,
            OrganizationManagerRolesEnum::ADMIN()
        );

        $this->repository->revokeManager(
            $organization,
            $manager
        );

        $relationship = OrganizationManagerModel::query()
            ->where('organization_id', $organization->getId())
            ->where('user_id', $manager->getId())
            ->get()
            ->first();
        self::assertEquals(OrganizationManagerStatusEnum::REVOKED(), $relationship->status);
    }

    public function shouldNotRevokeOrganizationOwner(): void
    {
        $owner = UserModel::factory()->createOne();
        /** @var Organization $organization */
        $organization = OrganizationModel::factory(['owner_id' => $owner->id])
            ->createOne()
            ->getEntity();

        self::expectException(CannotRevokeOrganizationOwnerException::class);
        $this->repository->revokeManager(
            $organization,
            $owner->getEntity()
        );
    }

    public function shouldListAcceptedOrganizationManagers(): void
    {
        $owner = UserModel::factory()->createOne();
        /** @var Organization $organization */
        $organization = OrganizationModel::factory(['owner_id' => $owner->id])
            ->createOne()
            ->getEntity();

        $managersCount = 5;
        $users = UserModel::factory()->count($managersCount)->create();
        $expectedUserIds = [$owner->id];
        foreach($users as $user) {
            $expectedUserIds[] = $user->id;
            $manager = $user->getEntity();
            $this->repository->inviteManager(
                $organization,
                $manager,
                OrganizationManagerRolesEnum::ADMIN()
            );
            $this->repository->acceptManager(
                $organization,
                $manager
            );
        }
        $managers = $this->repository->findManagersFrom($organization);
        self::assertCount($managersCount + 1, $managers);
        foreach($managers as $manager) {
            self::assertContains($manager->getId(), $expectedUserIds);
        }
    }
}
