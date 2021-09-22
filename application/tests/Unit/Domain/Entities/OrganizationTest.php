<?php

namespace Tests\Unit\Domain\Entities;

use Faker\Provider\pt_BR\Company;
use Illuminate\Support\Str;
use Tests\TestCase;

class OrganizationTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->faker->addProvider(new Company($this->faker));
    }

    private function getEntityInput(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'owner_id' => (string) Str::uuid(),
            'corporate_name' => $this->faker->company(),
            'fantasy_name' => $this->faker->company(),
            'logo_url' => $this->faker->imageUrl(),
            'website' => $this->faker->url(),
            'description' => $this->faker->paragraphs(10, true),
            'created_at' => $this->now(),
            'updated_at' => $this->tomorrow(),
            'deleted_at' => $this->nextMonth(),
        ];
    }

    private function getNewEntityInstance(array $input): Organization
    {
        return new Organization(
            id: $input['id'],
            owner_id: $input['owner_id'],
            corporate_name: $input['corporate_name'],
            fantasy_name: $input['fantasy_name'],
            logo_url: $input['logo_url'],
            website: $input['website'],
            description: $input['description'],
            status: 'UNDER_REVIEW',
            created_at: $input['created_at'],
            updated_at: $input['updated_at'],
            deleted_at: $input['deleted_at']
        );
    }

    private static function assertEntityGetters(array $input, Organization $entity): void
    {
        self::assertEquals($input['id'], $entity->getId());
        self::assertEquals($input['owner_id'], $entity->getOwnerId());
        self::assertEquals($input['corporate_name'], $entity->getCorporateName());
        self::assertEquals($input['fantasy_name'], $entity->getFantasyName());
        self::assertEquals($input['logo_url'], $entity->getLogoUrl());
        self::assertEquals($input['website'], $entity->getWebsite());
        self::assertEquals($input['description'], $entity->getDescription());
        self::assertEquals(OrganizationStatusEnum::UNDER_REVIEW(), $entity->getStatus());
        self::assertEquals($input['created_at'], $entity->getCreatedAt());
        self::assertEquals($input['updated_at'], $entity->getUpdatedAt());
        self::assertEquals($input['deleted_at'], $entity->getDeletedAt());
    }

    /**
     * @test para garantir que uma instância vazia não irá resultar em erros inesperados
     */
    public function shouldInstanceWithoutInput(): void
    {
        $entity = new Organization;
        self::assertInstanceOf(Organization::class, $entity);
    }

    /**
     * @test para garantir a possibilidade de gerar uma instância com os dados preenchidos
     */
    public function shouldInstanceWithInputs(): void
    {
        $input = $this->getEntityInput();
        $entity = $this->getNewEntityInstance($input);
        self::assertEntityGetters($input, $entity);
    }

    /**
     * @dataProvider settersDataProvider
     * @test
     *
     * @param string $key
     * @param string $setter
     * @param string $getter
     */
    public function shouldSetWithSetter(string $key, string $setter, string $getter): void
    {
        $input = $this->getEntityInput();
        $entity = new Organization;
        $entity->{$setter}($input[$key]);
        self::assertEquals($input[$key], $entity->{$getter}());
    }

    public function settersDataProvider(): array
    {
        return [
            ['owner_id', 'setOwnerId', 'getOwnerId'],
            ['corporate_name', 'setCorporateName', 'getCorporateName'],
            ['fantasy_name', 'setFantasyName', 'getFantasyName'],
            ['logo_url', 'setLogoUrl', 'getLogoUrl'],
            ['website', 'setWebsite', 'getWebsite'],
            ['description', 'setDescription', 'getDescription'],
        ];
    }

    /**
     * @dataProvider statusEnumsDataProvider
     * @test para garantir que o setter de status está funcionando
     */
    public function shouldSetStatusAsStringWithSetter(string $statusValue): void
    {
        $entity = new Organization;
        $entity->setStatus($statusValue);
        self::assertEquals($statusValue, (string) $entity->getStatus());
    }


    /**
     * @dataProvider statusEnumsDataProvider
     * @test para garantir que o setter de status está funcionando
     */
    public function shouldSetStatusAsEnumWithSetter(string $statusValue): void
    {
        $status = OrganizationStatusEnum::from($statusValue);
        $entity = new Organization;
        $entity->setStatus($status);
        self::assertEquals($status, $entity->getStatus());
    }

    /**
     * @dataProvider statusEnumsDataProvider
     * @test para garantir que a entidade faz o cast para OrganizationStatusEnum
     */
    public function shouldInstanceStatusAsString(string $statusValue): void
    {
        $entity = new Organization(status: $statusValue);
        self::assertEquals($statusValue, (string) $entity->getStatus());
    }

    /**
     * @dataProvider statusEnumsDataProvider
     * @test para garantir que a entidade faz o cast para OrganizationStatusEnum
     */
    public function shouldInstanceStatusAsEnums(string $statusValue): void
    {
        $status = OrganizationStatusEnum::from($statusValue);
        $entity = new Organization(status: $status);
        self::assertEquals($status, $entity->getStatus());
    }

    public function statusEnumsDataProvider(): array
    {
        return [
            ['DRAFT'],
            ['UNDER_REVIEW'],
            ['ACTIVE'],
        ];
    }

    /**
     * @test para garantir que a entidade pode ser facilmente convertida para array
     */
    public function shouldCastToArray(): void
    {
        $input = $this->getEntityInput();
        $entity = $this->getNewEntityInstance($input);
        $entityArray = $entity->jsonSerialize();
        self::assertEquals([
            'id' => $input['id'],
            'owner_id' => $input['owner_id'],
            'corporate_name' => $input['corporate_name'],
            'fantasy_name' => $input['fantasy_name'],
            'logo_url' => $input['logo_url'],
            'website' =>$input['website'],
            'description' => $input['description'],
            'status' => 'UNDER_REVIEW',
            'created_at' => $input['created_at'],
            'updated_at' => $input['updated_at'],
            'deleted_at' => $input['deleted_at'],
        ], $entityArray);
    }

    /**
     * @test para garantir que a entidade pode ser facilmente ser instanciada a partir de um array
     */
    public function shouldInstanceFromArray(): void
    {
        $input = $this->getEntityInput();
        $entity = Organization::fromArray([
            'id' => $input['id'],
            'owner_id' => $input['owner_id'],
            'corporate_name' => $input['corporate_name'],
            'fantasy_name' => $input['fantasy_name'],
            'logo_url' => $input['logo_url'],
            'website' =>$input['website'],
            'description' => $input['description'],
            'status' => 'UNDER_REVIEW',
            'created_at' => $input['created_at'],
            'updated_at' => $input['updated_at'],
            'deleted_at' => $input['deleted_at'],
        ]);
        self::assertEntityGetters($input, $entity);
    }
}
