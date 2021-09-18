<?php

namespace Tests\Unit\Domain\Entities;

use App\Domain\Entities\Address;
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

    private function getEntityInput(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'title' => $this->faker->word(),
            'postal_code' => $this->faker->postcode(),
            'country' => $this->faker->countryCode(),
            'state' => $this->faker->state(),
            'city' => $this->faker->city(),
            'neighborhood' => $this->faker->words(2, true),
            'street' => $this->faker->streetName(),
            'number' => $this->faker->boolean() ? "{$this->faker->randomNumber()}" : 'S/N',
            'complement' => $this->faker->boolean() ? $this->faker->words(4, true) : '',
            'created_at' => $this->now(),
            'updated_at' => $this->tomorrow(),
            'deleted_at' => $this->nextMonth(),
        ];
    }

    private function getNewEntityInstance(array $input): Address
    {
        return new Address(
            id: $input['id'],
            title: $input['title'],
            postal_code: $input['postal_code'],
            country: $input['country'],
            state: $input['state'],
            city: $input['city'],
            neighborhood: $input['neighborhood'],
            street: $input['street'],
            number: $input['number'],
            complement: $input['complement'],
            privacy: 'PUBLIC',
            created_at: $input['created_at'],
            updated_at: $input['updated_at'],
            deleted_at: $input['deleted_at']
        );
    }

    private static function assertEntityGetters(array $input, Address $entity): void
    {
        self::assertEquals($input['id'], $entity->getId());
        self::assertEquals($input['title'], $entity->getTitle());
        self::assertEquals($input['postal_code'], $entity->getPostalCode());
        self::assertEquals($input['country'], $entity->getCountry());
        self::assertEquals($input['state'], $entity->getState());
        self::assertEquals($input['city'], $entity->getCity());
        self::assertEquals($input['neighborhood'], $entity->getNeighborhood());
        self::assertEquals($input['street'], $entity->getStreet());
        self::assertEquals($input['complement'], $entity->getComplement());
        self::assertEquals(PrivacyEnum::PUBLIC(), $entity->getPrivacy());
        self::assertEquals($input['created_at'], $entity->getCreatedAt());
        self::assertEquals($input['updated_at'], $entity->getUpdatedAt());
        self::assertEquals($input['deleted_at'], $entity->getDeletedAt());
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
     * @test para garantir que o setter de title está funcionando
     */
    public function shouldSetTitleWithSetter(): void
    {
        $input = $this->getEntityInput();
        $address = new Address;
        $address->setTitle($input['title']);
        self::assertEquals($input['title'], $address->getTitle());
    }

    /**
     * @test para garantir que o setter de postal_code está funcionando
     */
    public function shouldSetPostalCodeWithSetter(): void
    {
        $input = $this->getEntityInput();
        $address = new Address;
        $address->setPostalCode($input['postal_code']);
        self::assertEquals($input['postal_code'], $address->getPostalCode());
    }

    /**
     * @test para garantir que o setter de country está funcionando
     */
    public function shouldSetCountryWithSetter(): void
    {
        $input = $this->getEntityInput();
        $address = new Address;
        $address->setCountry($input['country']);
        self::assertEquals($input['country'], $address->getCountry());
    }

    /**
     * @test para garantir que o setter de state está funcionando
     */
    public function shouldSetStateWithSetter(): void
    {
        $input = $this->getEntityInput();
        $address = new Address;
        $address->setState($input['state']);
        self::assertEquals($input['state'], $address->getState());
    }

    /**
     * @test para garantir que o setter de city está funcionando
     */
    public function shouldSetCityWithSetter(): void
    {
        $input = $this->getEntityInput();
        $address = new Address;
        $address->setCity($input['city']);
        self::assertEquals($input['city'], $address->getCity());
    }

    /**
     * @test para garantir que o setter de neighborhood está funcionando
     */
    public function shouldSetNeighborhoodWithSetter(): void
    {
        $input = $this->getEntityInput();
        $address = new Address;
        $address->setNeighborhood($input['neighborhood']);
        self::assertEquals($input['neighborhood'], $address->getNeighborhood());
    }

    /**
     * @test para garantir que o setter de street está funcionando
     */
    public function shouldSetStreetWithSetter(): void
    {
        $input = $this->getEntityInput();
        $address = new Address;
        $address->setStreet($input['street']);
        self::assertEquals($input['street'], $address->getStreet());
    }

    /**
     * @test para garantir que o setter de complement está funcionando
     */
    public function shouldSetNumberWithSetter(): void
    {
        $input = $this->getEntityInput();
        $address = new Address;
        $address->setNumber($input['number']);
        self::assertEquals($input['number'], $address->getNumber());
    }

    /**
     * @test para garantir que o setter de complement está funcionando
     */
    public function shouldSetComplementWithSetter(): void
    {
        $input = $this->getEntityInput();
        $address = new Address;
        $address->setComplement($input['complement']);
        self::assertEquals($input['complement'], $address->getComplement());
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
        $input = $this->getEntityInput();
        $entity = $this->getNewEntityInstance($input);
        $entityArray = $entity->jsonSerialize();
        self::assertEquals([
            'id' => $input['id'],
            'title' => $input['title'],
            'postal_code' => $input['postal_code'],
            'country' => $input['country'],
            'state' => $input['state'],
            'city' => $input['city'],
            'neighborhood' => $input['neighborhood'],
            'street' => $input['street'],
            'number' => $input['number'],
            'complement' => $input['complement'],
            'privacy' => 'PUBLIC',
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
        $entity = Address::fromArray([
            'id' => $input['id'],
            'title' => $input['title'],
            'postal_code' => $input['postal_code'],
            'country' => $input['country'],
            'state' => $input['state'],
            'city' => $input['city'],
            'neighborhood' => $input['neighborhood'],
            'street' => $input['street'],
            'number' => $input['number'],
            'complement' => $input['complement'],
            'privacy' => 'PUBLIC',
            'created_at' => $input['created_at'],
            'updated_at' => $input['updated_at'],
            'deleted_at' => $input['deleted_at'],
        ]);
        self::assertEntityGetters($input, $entity);
    }
}
