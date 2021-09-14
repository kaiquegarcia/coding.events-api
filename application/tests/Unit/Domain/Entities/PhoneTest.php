<?php

namespace Tests\Unit\Domain\Entities;

use App\Domain\Entities\Phone;
use App\Domain\Enums\PrivacyEnum;
use Illuminate\Support\Str;
use Tests\TestCase;

class PhoneTest extends TestCase
{
    /**
     * @test para garantir que uma instância vazia não irá resultar em erros inesperados
     */
    public function shouldInstanceWithoutInput(): void
    {
        $phone = new Phone;
        self::assertInstanceOf(Phone::class, $phone);
    }

    /**
     * @test para garantir a possibilidade de gerar uma instância com os dados preenchidos
     */
    public function shouldInstanceWithInputs(): void
    {
        $id = Str::uuid();
        $createdAt = $this->now();
        $updatedAt = $this->tomorrow();
        $deletedAt = $this->nextMonth();
        $phone = new Phone(
            id: $id,
            country_code: '+55',
            area_code: '79',
            number: '912341234',
            privacy: 'PUBLIC',
            created_at: $createdAt,
            updated_at: $updatedAt,
            deleted_at: $deletedAt
        );
        self::assertEquals($id, $phone->getId());
        self::assertEquals('+55', $phone->getCountryCode());
        self::assertEquals('79', $phone->getAreaCode());
        self::assertEquals('912341234', $phone->getNumber());
        self::assertEquals(PrivacyEnum::PUBLIC(), $phone->getPrivacy());
        self::assertEquals($createdAt, $phone->getCreatedAt());
        self::assertEquals($updatedAt, $phone->getUpdatedAt());
        self::assertEquals($deletedAt, $phone->getDeletedAt());
    }

    /**
     * @test para garantir que o setter de country_code está funcionando
     */
    public function shouldSetCountryCodeWithSetter(): void
    {
        $phone = new Phone;
        $phone->setCountryCode('+55');
        self::assertEquals('+55', $phone->getCountryCode());
    }

    /**
     * @test para garantir que o setter de area_code está funcionando
     */
    public function shouldSetAreaCodeWithSetter(): void
    {
        $phone = new Phone;
        $phone->setAreaCode('79');
        self::assertEquals('79', $phone->getAreaCode());
    }

    /**
     * @test para garantir que o setter de number está funcionando
     */
    public function shouldSetNumberWithSetter(): void
    {
        $phone = new Phone;
        $phone->setNumber('912341234');
        self::assertEquals('912341234', $phone->getNumber());
    }

    /**
     * @dataProvider privacyEnumsDataProvider
     * @test para garantir que o setter de privacy está funcionando
     */
    public function shouldSetPrivacyAsStringWithSetter(string $privacyValue): void
    {
        $phone = new Phone;
        $phone->setPrivacy($privacyValue);
        self::assertEquals($privacyValue, (string) $phone->getPrivacy());
    }


    /**
     * @dataProvider privacyEnumsDataProvider
     * @test para garantir que o setter de privacy está funcionando
     */
    public function shouldSetPrivacyAsEnumWithSetter(string $privacyValue): void
    {
        $privacy = PrivacyEnum::from($privacyValue);
        $phone = new Phone;
        $phone->setPrivacy($privacy);
        self::assertEquals($privacy, $phone->getPrivacy());
    }

    /**
     * @dataProvider privacyEnumsDataProvider
     * @test para garantir que a entidade faz o cast para PrivacyEnum
     */
    public function shouldInstancePrivacyAsString(string $privacyValue): void
    {
        $phone = new Phone(privacy: $privacyValue);
        self::assertEquals($privacyValue, (string) $phone->getPrivacy());
    }

    /**
     * @dataProvider privacyEnumsDataProvider
     * @test para garantir que a entidade faz o cast para PrivacyEnum
     */
    public function shouldInstancePrivacyAsEnums(string $privacyValue): void
    {
        $privacy = PrivacyEnum::from($privacyValue);
        $phone = new Phone(privacy: $privacy);
        self::assertEquals($privacy, $phone->getPrivacy());
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
        $createdAt = $this->now();
        $updatedAt = $this->tomorrow();
        $deletedAt = $this->nextMonth();
        $phone = new Phone(
            id: $id,
            country_code: '+51',
            area_code: '71',
            number: '912341232',
            privacy: PrivacyEnum::PUBLIC(),
            created_at: $createdAt,
            updated_at: $updatedAt,
            deleted_at: $deletedAt
        );
        $phoneArray = $phone->jsonSerialize();
        self::assertEquals([
            'id' => $id,
            'country_code' => '+51',
            'area_code' => '71',
            'number' => '912341232',
            'privacy' => 'PUBLIC',
            'created_at' => $createdAt,
            'updated_at' => $updatedAt,
            'deleted_at' => $deletedAt,
        ], $phoneArray);
    }

    /**
     * @test para garantir que a entidade pode ser facilmente ser instanciada a partir de um array
     */
    public function shouldInstanceFromArray(): void
    {
        $id = Str::uuid();
        $createdAt = $this->now();
        $updatedAt = $this->tomorrow();
        $deletedAt = $this->nextMonth();
        $phone = Phone::fromArray([
            'id' => $id,
            'country_code' => '+53',
            'area_code' => '72',
            'number' => '912341233',
            'privacy' => 'PRIVATE',
            'created_at' => $createdAt,
            'updated_at' => $updatedAt,
            'deleted_at' => $deletedAt,
        ]);
        self::assertEquals($id, $phone->getId());
        self::assertEquals('+53', $phone->getCountryCode());
        self::assertEquals('72', $phone->getAreaCode());
        self::assertEquals('912341233', $phone->getNumber());
        self::assertEquals(PrivacyEnum::PRIVATE(), $phone->getPrivacy());
        self::assertEquals($createdAt, $phone->getCreatedAt());
        self::assertEquals($updatedAt, $phone->getUpdatedAt());
        self::assertEquals($deletedAt, $phone->getDeletedAt());
    }

    /**
     * @test
     */
    public function shouldReturnFullPhone(): void
    {
        $phone = new Phone(
            country_code: '+55',
            area_code: '79',
            number: '912341234'
        );
        self::assertEquals('+5579912341234', $phone->getFullPhone());
    }
}
