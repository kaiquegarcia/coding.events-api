<?php

namespace Tests\Unit\Domain\Entities;

use App\Domain\Entities\User;
use App\Domain\Enums\UserStatusEnum;
use Faker\Provider\pt_BR\Person;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->faker->addProvider(new Person($this->faker));
    }

    #[ArrayShape([
        'id' => "string",
        'first_name' => "string",
        'last_name' => "string",
        'avatar_url' => "string",
        'website' => "string",
        'bio' => "array|string",
        'created_at' => "string",
        'updated_at' => "string",
        'deleted_at' => "string"
    ])]
    private function getEntityInput(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'avatar_url' => $this->faker->imageUrl(),
            'website' => $this->faker->url(),
            'bio' => $this->faker->paragraphs(10, true),
            'created_at' => $this->now(),
            'updated_at' => $this->tomorrow(),
            'deleted_at' => $this->nextMonth(),
        ];
    }

    private function getNewEntityInstance(array $input): User
    {
        return new User(
            id: $input['id'],
            first_name: $input['first_name'],
            last_name: $input['last_name'],
            avatar_url: $input['avatar_url'],
            website: $input['website'],
            bio: $input['bio'],
            status: 'PENDING',
            created_at: $input['created_at'],
            updated_at: $input['updated_at'],
            deleted_at: $input['deleted_at']
        );
    }

    private static function assertEntityGetters(array $input, User $entity): void
    {
        self::assertEquals($input['id'], $entity->getId());
        self::assertEquals($input['first_name'], $entity->getFirstName());
        self::assertEquals($input['last_name'], $entity->getLastName());
        self::assertEquals($input['avatar_url'], $entity->getAvatarUrl());
        self::assertEquals($input['website'], $entity->getWebsite());
        self::assertEquals($input['bio'], $entity->getBio());
        self::assertEquals(UserStatusEnum::PENDING(), $entity->getStatus());
        self::assertEquals($input['created_at'], $entity->getCreatedAt());
        self::assertEquals($input['updated_at'], $entity->getUpdatedAt());
        self::assertEquals($input['deleted_at'], $entity->getDeletedAt());
    }

    /**
     * @test para garantir que uma instância vazia não irá resultar em erros inesperados
     */
    public function shouldInstanceWithoutInput(): void
    {
        $user = new User;
        self::assertInstanceOf(User::class, $user);
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
        $entity = new User;
        $entity->{$setter}($input[$key]);
        self::assertEquals($input[$key], $entity->{$getter}());
    }

    public function settersDataProvider(): array
    {
        return [
            ['first_name', 'setFirstName', 'getFirstName'],
            ['last_name', 'setLastName', 'getLastName'],
            ['avatar_url', 'setAvatarUrl', 'getAvatarUrl'],
            ['website', 'setWebsite', 'getWebsite'],
            ['bio', 'setBio', 'getBio'],
        ];
    }

    /**
     * @dataProvider statusEnumsDataProvider
     * @test para garantir que o setter de status está funcionando
     */
    public function shouldSetStatusAsStringWithSetter(string $statusValue): void
    {
        $user = new User;
        $user->setStatus($statusValue);
        self::assertEquals($statusValue, (string) $user->getStatus());
    }


    /**
     * @dataProvider statusEnumsDataProvider
     * @test para garantir que o setter de status está funcionando
     */
    public function shouldSetStatusAsEnumWithSetter(string $statusValue): void
    {
        $status = UserStatusEnum::from($statusValue);
        $user = new User;
        $user->setStatus($status);
        self::assertEquals($status, $user->getStatus());
    }

    /**
     * @dataProvider statusEnumsDataProvider
     * @test para garantir que a entidade faz o cast para StatusEnum
     */
    public function shouldInstanceStatusAsString(string $statusValue): void
    {
        $user = new User(status: $statusValue);
        self::assertEquals($statusValue, (string) $user->getStatus());
    }

    /**
     * @dataProvider statusEnumsDataProvider
     * @test para garantir que a entidade faz o cast para StatusEnum
     */
    public function shouldInstanceStatusAsEnums(string $statusValue): void
    {
        $status = UserStatusEnum::from($statusValue);
        $user = new User(status: $status);
        self::assertEquals($status, $user->getStatus());
    }

    public function statusEnumsDataProvider(): array
    {
        return [
            ['PENDING'],
            ['ACTIVE'],
            ['BANNED'],
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
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'],
            'avatar_url' => $input['avatar_url'],
            'website' =>$input['website'],
            'bio' => $input['bio'],
            'status' => 'PENDING',
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
        $entity = User::fromArray([
            'id' => $input['id'],
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'],
            'avatar_url' => $input['avatar_url'],
            'website' =>$input['website'],
            'bio' => $input['bio'],
            'status' => 'PENDING',
            'created_at' => $input['created_at'],
            'updated_at' => $input['updated_at'],
            'deleted_at' => $input['deleted_at'],
        ]);
        self::assertEntityGetters($input, $entity);
    }

    /**
     * @test
     */
    public function shouldReturnFullName(): void
    {
        $input = $this->getEntityInput();
        $fullName = implode(' ', [$input['first_name'], $input['last_name']]);
        $entity = new User(
            first_name: $input['first_name'],
            last_name: $input['last_name']
        );
        self::assertEquals($fullName, $entity->getFullName());
    }
}
