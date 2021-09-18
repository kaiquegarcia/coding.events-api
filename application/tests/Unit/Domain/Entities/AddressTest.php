<?php

namespace Tests\Unit\Domain\Entities;

use App\Domain\Enums\PrivacyEnum;
use Faker\Provider\pt_BR\Address as FakerAddress;
use Illuminate\Support\Str;
use Tests\TestCase;

class AddressTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
        $this->faker->addProvider(new FakerAddress($this->faker));
    }

    /**
     * @test para garantir que uma instância vazia não irá resultar em erros inesperados
     */
    public function shouldInstanceWithoutInput(): void
    {
        $address = new Address;
        self::assertInstanceOf(Address::class, $address);
    }

    /**
     * @test para garantir a possibilidade de gerar uma instância com os dados preenchidos
     */
    public function shouldInstanceWithInputs(): void
    {
        $id = Str::uuid();
        $title = $this->faker->word();
        $postalCode = $this->faker->postcode();
        $country = $this->faker->countryCode();
        $state = $this->faker->state();
        $city = $this->faker->city();
        $neighborhood = $this->faker->words(2);
        $street = $this->faker->streetName();
        $number = $this->faker->boolean() ? "{$this->faker->randomNumber()}" : 'S/N';
        $complement = $this->faker->boolean() ? $this->faker->words(4) : null;
        $createdAt = $this->now();
        $updatedAt = $this->tomorrow();
        $deletedAt = $this->nextMonth();
        $address = new Address(
            id: $id,
            title: $title,
            postal_code: $postalCode,
            country: $country,
            state: $state,
            city: $city,
            neighborhood: $neighborhood,
            street: $street,
            number: $number,
            complement: $complement,
            privacy: 'PUBLIC',
            created_at: $createdAt,
            updated_at: $updatedAt,
            deleted_at: $deletedAt
        );
        self::assertEquals($id, $address->getId());
        self::assertEquals($title, $address->getTitle());
        self::assertEquals($postalCode, $address->getPostalCode());
        self::assertEquals($country, $address->getCountry());
        self::assertEquals($state, $address->getState());
        self::assertEquals($city, $address->getCity());
        self::assertEquals($neighborhood, $address->getNeighborhood());
        self::assertEquals($street, $address->getStreet());
        self::assertEquals($complement, $address->getComplement());
        self::assertEquals(PrivacyEnum::PUBLIC(), $address->getPrivacy());
        self::assertEquals($createdAt, $address->getCreatedAt());
        self::assertEquals($updatedAt, $address->getUpdatedAt());
        self::assertEquals($deletedAt, $address->getDeletedAt());
    }

    /**
     * @test para garantir que o setter de title está funcionando
     */
    public function shouldSetTitleWithSetter(): void
    {
        $title = $this->faker->word();
        $address = new Address;
        $address->setTitle($title);
        self::assertEquals($title, $address->getTitle());
    }

    /**
     * @test para garantir que o setter de postal_code está funcionando
     */
    public function shouldSetPostalCodeWithSetter(): void
    {
        $postalCode = $this->faker->postcode();
        $address = new Address;
        $address->setPostalCode($postalCode);
        self::assertEquals($postalCode, $address->getPostalCode());
    }

    /**
     * @test para garantir que o setter de country está funcionando
     */
    public function shouldSetCountryWithSetter(): void
    {
        $country = $this->faker->countryCode();
        $address = new Address;
        $address->setCountry($country);
        self::assertEquals($country, $address->getCountry());
    }

    /**
     * @test para garantir que o setter de state está funcionando
     */
    public function shouldSetStateWithSetter(): void
    {
        $state = $this->faker->state();
        $address = new Address;
        $address->setState($state);
        self::assertEquals($state, $address->getState());
    }

    /**
     * @test para garantir que o setter de city está funcionando
     */
    public function shouldSetCityWithSetter(): void
    {
        $city = $this->faker->city();
        $address = new Address;
        $address->setCity($city);
        self::assertEquals($city, $address->getCity());
    }

    /**
     * @test para garantir que o setter de neighborhood está funcionando
     */
    public function shouldSetNeighborhoodWithSetter(): void
    {
        $neighborhood = $this->faker->words(2);
        $address = new Address;
        $address->setNeighborhood($neighborhood);
        self::assertEquals($neighborhood, $address->getNeighborhood());
    }

    /**
     * @test para garantir que o setter de street está funcionando
     */
    public function shouldSetStreetWithSetter(): void
    {
        $street = $this->faker->streetName();
        $address = new Address;
        $address->setStreet($street);
        self::assertEquals($street, $address->getStreet());
    }

    /**
     * @test para garantir que o setter de complement está funcionando
     */
    public function shouldSetNumberWithSetter(): void
    {
        $number = $this->faker->boolean() ? "{$this->faker->randomNumber()}" : 'S/N';
        $address = new Address;
        $address->setNumber($number);
        self::assertEquals($number, $address->getNumber());
    }

    /**
     * @test para garantir que o setter de complement está funcionando
     */
    public function shouldSetComplementWithSetter(): void
    {
        $complement = $this->faker->boolean() ? $this->faker->words(4) : null;
        $address = new Address;
        $address->setComplement($complement);
        self::assertEquals($complement, $address->getComplement());
    }

    /**
     * @dataProvider privacyEnumsDataProvider
     * @test para garantir que o setter de privacy está funcionando
     */
    public function shouldSetPrivacyAsStringWithSetter(string $privacyValue): void
    {
        $address = new Address;
        $address->setPrivacy($privacyValue);
        self::assertEquals($privacyValue, (string)$address->getPrivacy());
    }


    /**
     * @dataProvider privacyEnumsDataProvider
     * @test para garantir que o setter de privacy está funcionando
     */
    public function shouldSetPrivacyAsEnumWithSetter(string $privacyValue): void
    {
        $privacy = PrivacyEnum::from($privacyValue);
        $address = new Address;
        $address->setPrivacy($privacy);
        self::assertEquals($privacy, $address->getPrivacy());
    }

    /**
     * @dataProvider privacyEnumsDataProvider
     * @test para garantir que a entidade faz os casts para PrivacyEnum
     */
    public function shouldInstancePrivacyAsString(string $privacyValue): void
    {
        $address = new Address(privacy: $privacyValue);
        self::assertEquals($privacyValue, (string)$address->getPrivacy());
    }

    /**
     * @dataProvider privacyEnumsDataProvider
     * @test para garantir que a entidade faz os casts para PrivacyEnum
     */
    public function shouldInstancePrivacyAndAddressTypeAsEnums(string $privacyValue): void
    {
        $privacy = PrivacyEnum::from($privacyValue);
        $address = new Address(privacy: $privacy);
        self::assertEquals($privacy, $address->getPrivacy());
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
        $title = $this->faker->word();
        $postalCode = $this->faker->postcode();
        $country = $this->faker->countryCode();
        $state = $this->faker->state();
        $city = $this->faker->city();
        $neighborhood = $this->faker->words(2);
        $street = $this->faker->streetName();
        $number = $this->faker->boolean() ? "{$this->faker->randomNumber()}" : 'S/N';
        $complement = $this->faker->boolean() ? $this->faker->words(4) : null;
        $createdAt = $this->now();
        $updatedAt = $this->tomorrow();
        $deletedAt = $this->nextMonth();
        $address = new Address(
            id: $id,
            title: $title,
            postal_code: $postalCode,
            country: $country,
            state: $state,
            city: $city,
            neighborhood: $neighborhood,
            street: $street,
            number: $number,
            complement: $complement,
            privacy: 'PUBLIC',
            created_at: $createdAt,
            updated_at: $updatedAt,
            deleted_at: $deletedAt
        );
        $addressArray = $address->jsonSerialize();
        self::assertEquals([
            'id' => $id,
            'title' => $title,
            'postal_code' => $postalCode,
            'country' => $country,
            'state' => $state,
            'city' => $city,
            'neighborhood' => $neighborhood,
            'street' => $street,
            'number' => $number,
            'complement' => $complement,
            'privacy' => 'PUBLIC',
            'created_at' => $createdAt,
            'updated_at' => $updatedAt,
            'deleted_at' => $deletedAt,
        ], $addressArray);
    }

    /**
     * @test para garantir que a entidade pode ser facilmente ser instanciada a partir de um array
     */
    public function shouldInstanceFromArray(): void
    {
        $id = Str::uuid();
        $title = $this->faker->word();
        $postalCode = $this->faker->postcode();
        $country = $this->faker->countryCode();
        $state = $this->faker->state();
        $city = $this->faker->city();
        $neighborhood = $this->faker->words(2);
        $street = $this->faker->streetName();
        $number = $this->faker->boolean() ? "{$this->faker->randomNumber()}" : 'S/N';
        $complement = $this->faker->boolean() ? $this->faker->words(4) : null;
        $createdAt = $this->now();
        $updatedAt = $this->tomorrow();
        $deletedAt = $this->nextMonth();
        $address = Address::fromArray([
            'id' => $id,
            'title' => $title,
            'postal_code' => $postalCode,
            'country' => $country,
            'state' => $state,
            'city' => $city,
            'neighborhood' => $neighborhood,
            'street' => $street,
            'number' => $number,
            'complement' => $complement,
            'privacy' => 'PUBLIC',
            'created_at' => $createdAt,
            'updated_at' => $updatedAt,
            'deleted_at' => $deletedAt,
        ]);
        self::assertEquals($id, $address->getId());
        self::assertEquals($title, $address->getTitle());
        self::assertEquals($postalCode, $address->getPostalCode());
        self::assertEquals($country, $address->getCountry());
        self::assertEquals($state, $address->getState());
        self::assertEquals($city, $address->getCity());
        self::assertEquals($neighborhood, $address->getNeighborhood());
        self::assertEquals($street, $address->getStreet());
        self::assertEquals($complement, $address->getComplement());
        self::assertEquals(PrivacyEnum::PUBLIC(), $address->getPrivacy());
        self::assertEquals($createdAt, $address->getCreatedAt());
        self::assertEquals($updatedAt, $address->getUpdatedAt());
        self::assertEquals($deletedAt, $address->getDeletedAt());
    }
}
