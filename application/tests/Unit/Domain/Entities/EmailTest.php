<?php

namespace Tests\Unit\Domain\Entities;

use App\Domain\Entities\Email;
use App\Domain\Enums\PrivacyEnum;
use Illuminate\Support\Str;
use Tests\TestCase;

class EmailTest extends TestCase
{

    /**
     * @test para garantir que uma instância vazia não irá resultar em erros inesperados
     */
    public function shouldInstanceWithoutInput(): void
    {
        $email = new Email;
        self::assertInstanceOf(Email::class, $email);
    }

    /**
     * @test para garantir a possibilidade de gerar uma instância com os dados preenchidos
     */
    public function shouldInstanceWithInputs(): void
    {
        $id = Str::uuid();
        $email = $this->faker->email();
        [$username, $domain] = explode('@', $email);
        $createdAt = $this->now();
        $updatedAt = $this->tomorrow();
        $deletedAt = $this->nextMonth();
        $email = new Email(
            id: $id,
            username: $username,
            domain: $domain,
            privacy: 'PUBLIC',
            created_at: $createdAt,
            updated_at: $updatedAt,
            deleted_at: $deletedAt
        );
        self::assertEquals($id, $email->getId());
        self::assertEquals($username, $email->getUsername());
        self::assertEquals($domain, $email->getDomain());
        self::assertEquals(PrivacyEnum::PUBLIC(), $email->getPrivacy());
        self::assertEquals($createdAt, $email->getCreatedAt());
        self::assertEquals($updatedAt, $email->getUpdatedAt());
        self::assertEquals($deletedAt, $email->getDeletedAt());
    }

    /**
     * @test para garantir que o setter de username está funcionando
     */
    public function shouldSetUsernameWithSetter(): void
    {
        $email = $this->faker->email();
        [$username] = explode('@', $email);
        $email = new Email;
        $email->setUsername($username);
        self::assertEquals($username, $email->getUsername());
    }

    /**
     * @test para garantir que o setter de domain está funcionando
     */
    public function shouldSetAreaCodeWithSetter(): void
    {
        $email = $this->faker->email();
        [, $domain] = explode('@', $email);
        $email = new Email;
        $email->setDomain($domain);
        self::assertEquals($domain, $email->getDomain());
    }

    /**
     * @dataProvider privacyEnumsDataProvider
     * @test para garantir que o setter de privacy está funcionando
     */
    public function shouldSetPrivacyAsStringWithSetter(string $privacyValue): void
    {
        $email = new Email;
        $email->setPrivacy($privacyValue);
        self::assertEquals($privacyValue, (string) $email->getPrivacy());
    }


    /**
     * @dataProvider privacyEnumsDataProvider
     * @test para garantir que o setter de privacy está funcionando
     */
    public function shouldSetPrivacyAsEnumWithSetter(string $privacyValue): void
    {
        $privacy = PrivacyEnum::from($privacyValue);
        $email = new Email;
        $email->setPrivacy($privacy);
        self::assertEquals($privacy, $email->getPrivacy());
    }

    /**
     * @dataProvider privacyEnumsDataProvider
     * @test para garantir que a entidade faz o cast para PrivacyEnum
     */
    public function shouldInstancePrivacyAsString(string $privacyValue): void
    {
        $email = new Email(privacy: $privacyValue);
        self::assertEquals($privacyValue, (string) $email->getPrivacy());
    }

    /**
     * @dataProvider privacyEnumsDataProvider
     * @test para garantir que a entidade faz o cast para PrivacyEnum
     */
    public function shouldInstancePrivacyAsEnums(string $privacyValue): void
    {
        $privacy = PrivacyEnum::from($privacyValue);
        $email = new Email(privacy: $privacy);
        self::assertEquals($privacy, $email->getPrivacy());
    }

    public function privacyEnumsDataProvider(): array
    {
        return [
            ['PUBLIC'],
            ['SUBSCRIBERS'],
            ['PRIVATE'],
        ];
    }

    /**
     * @test para garantir que a entidade pode ser facilmente convertida para array
     */
    public function shouldCastToArray(): void
    {
        $id = Str::uuid();
        $email = $this->faker->email();
        [$username, $domain] = explode('@', $email);
        $createdAt = $this->now();
        $updatedAt = $this->tomorrow();
        $deletedAt = $this->nextMonth();
        $email = new Email(
            id: $id,
            username: $username,
            domain: $domain,
            privacy: PrivacyEnum::PUBLIC(),
            created_at: $createdAt,
            updated_at: $updatedAt,
            deleted_at: $deletedAt
        );
        $emailArray = $email->jsonSerialize();
        self::assertEquals([
            'id' => $id,
            'username' => $username,
            'domain' => $domain,
            'privacy' => 'PUBLIC',
            'created_at' => $createdAt,
            'updated_at' => $updatedAt,
            'deleted_at' => $deletedAt,
        ], $emailArray);
    }

    /**
     * @test para garantir que a entidade pode ser facilmente ser instanciada a partir de um array
     */
    public function shouldInstanceFromArray(): void
    {
        $id = Str::uuid();
        $email = $this->faker->email();
        [$username, $domain] = explode('@', $email);
        $createdAt = $this->now();
        $updatedAt = $this->tomorrow();
        $deletedAt = $this->nextMonth();
        $email = Email::fromArray([
            'id' => $id,
            'username' => $username,
            'domain' => $domain,
            'privacy' => 'PRIVATE',
            'created_at' => $createdAt,
            'updated_at' => $updatedAt,
            'deleted_at' => $deletedAt,
        ]);
        self::assertEquals($id, $email->getId());
        self::assertEquals($username, $email->getUsername());
        self::assertEquals($domain, $email->getDomain());
        self::assertEquals(PrivacyEnum::PRIVATE(), $email->getPrivacy());
        self::assertEquals($createdAt, $email->getCreatedAt());
        self::assertEquals($updatedAt, $email->getUpdatedAt());
        self::assertEquals($deletedAt, $email->getDeletedAt());
    }

    /**
     * @test
     */
    public function shouldReturnFullEmail(): void
    {
        $emailStr = $this->faker->email();
        [$username, $domain] = explode('@', $emailStr);
        $email = new Email(
            username: $username,
            domain: $domain
        );
        self::assertEquals($emailStr, $email->getFullEmail());
    }
}
