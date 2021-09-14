<?php

namespace Tests\Unit\Domain\Entities;

use App\Domain\Enums\PrivacyEnum;
use Faker\Provider\pt_BR\Person;
use Illuminate\Support\Str;
use Tests\TestCase;

class DocumentTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
        $this->faker->addProvider(new Person($this->faker));
    }

    /**
     * @test para garantir que uma instância vazia não irá resultar em erros inesperados
     */
    public function shouldInstanceWithoutInput(): void
    {
        $document = new Document;
        self::assertInstanceOf(Document::class, $document);
    }

    /**
     * @test para garantir a possibilidade de gerar uma instância com os dados preenchidos
     */
    public function shouldInstanceWithInputs(): void
    {
        $id = Str::uuid();
        $value = $this->faker->cpf();
        $createdAt = $this->now();
        $updatedAt = $this->tomorrow();
        $deletedAt = $this->nextMonth();
        $document = new Document(
            id: $id,
            document_type: 'CPF',
            value: $value,
            privacy: 'PUBLIC',
            created_at: $createdAt,
            updated_at: $updatedAt,
            deleted_at: $deletedAt
        );
        self::assertEquals($id, $document->getId());
        self::assertEquals('CPF', (string)$document->getDocumentType());
        self::assertEquals($value, $document->getValue());
        self::assertEquals(PrivacyEnum::PUBLIC(), $document->getPrivacy());
        self::assertEquals($createdAt, $document->getCreatedAt());
        self::assertEquals($updatedAt, $document->getUpdatedAt());
        self::assertEquals($deletedAt, $document->getDeletedAt());
    }

    /**
     * @dataProvider documentTypeEnumsDataProvider
     * @test para garantir que o setter de document_type está funcionando
     */
    public function shouldSetDocumentTypeAsStringWithSetter(string $documentType): void
    {
        $document = new Document;
        $document->setDocumentType($documentType);
        self::assertEquals($documentType, (string)$document->getDocumentType());
    }

    /**
     * @dataProvider documentTypeEnumsDataProvider
     * @test para garantir que o setter de document_type está funcionando
     */
    public function shouldSetDocumentTypeAsEnumWithSetter(string $documentType): void
    {
        $enum = DocumentTypeEnum::from($documentType);
        $document = new Document;
        $document->setDocumentType($enum);
        self::assertEquals($enum, $document->getDocumentType());
    }

    /**
     * @test para garantir que o setter de value está funcionando
     */
    public function shouldSetValueWithSetter(): void
    {
        $cpf = $this->faker->cpf();
        $document = new Document;
        $document->setValue($cpf);
        self::assertEquals($cpf, $document->getValue());
    }

    /**
     * @dataProvider privacyEnumsDataProvider
     * @test para garantir que o setter de privacy está funcionando
     */
    public function shouldSetPrivacyAsStringWithSetter(string $privacyValue): void
    {
        $document = new Document;
        $document->setPrivacy($privacyValue);
        self::assertEquals($privacyValue, (string)$document->getPrivacy());
    }


    /**
     * @dataProvider privacyEnumsDataProvider
     * @test para garantir que o setter de privacy está funcionando
     */
    public function shouldSetPrivacyAsEnumWithSetter(string $privacyValue): void
    {
        $privacy = PrivacyEnum::from($privacyValue);
        $document = new Document;
        $document->setPrivacy($privacy);
        self::assertEquals($privacy, $document->getPrivacy());
    }

    /**
     * @dataProvider privacyEnumsDataProvider
     * @test para garantir que a entidade faz os casts para PrivacyEnum e DocumentType
     */
    public function shouldInstancePrivacyAndDocumentTypeAsString(string $privacyValue, string $documentType): void
    {
        $document = new Document(privacy: $privacyValue, document_type: $documentType);
        self::assertEquals($privacyValue, (string)$document->getPrivacy());
        self::assertEquals($documentType, (string)$document->getDocumentType());
    }

    /**
     * @dataProvider privacyEnumsDataProvider
     * @test para garantir que a entidade faz os casts para PrivacyEnum e DocumentType
     */
    public function shouldInstancePrivacyAndDocumentTypeAsEnums(string $privacyValue, string $documentType): void
    {
        $privacy = PrivacyEnum::from($privacyValue);
        $documentType = DocumentTypeEnum::from($documentType);
        $document = new Document(privacy: $privacy);
        self::assertEquals($privacy, $document->getPrivacy());
        self::assertEquals($documentType, $document->getDocumentType());
    }

    public function documentTypeEnumsDataProvider(): array
    {
        return [
            ['CPF'],
            ['CNPJ'],
            ['RG'],
        ];
    }

    public function privacyEnumsDataProvider(): array
    {
        return [
            ['PUBLIC'],
            ['SUBSCRIBERS'],
            ['PRIVATE'],
        ];
    }

    public function mergeOfDocumentTypeWithPrivacyEnumsDataProvider(): array
    {
        $result = [];
        $documentTypes = $this->documentTypeEnumsDataProvider();
        $privacyEnums = $this->privacyEnumsDataProvider();
        foreach ($documentTypes as $type) {
            foreach ($privacyEnums as $privacy) {
                $result[] = [$privacy, $type];
            }
        }
        return $result;
    }

    /**
     * @test para garantir que a entidade pode ser facilmente convertida para array
     */
    public function shouldCastToArray(): void
    {
        $id = Str::uuid();
        $cpf = $this->faker->cpf;
        $createdAt = $this->now();
        $updatedAt = $this->tomorrow();
        $deletedAt = $this->nextMonth();
        $document = new Document(
            id: $id,
            cpf: $cpf,
            documentType: DocumentTypeEnum::CPF(),
            privacy: PrivacyEnum::PUBLIC(),
            created_at: $createdAt,
            updated_at: $updatedAt,
            deleted_at: $deletedAt
        );
        $documentArray = $document->jsonSerialize();
        self::assertEquals([
            'id' => $id,
            'cpf' => $cpf,
            'documentType' => 'CPF',
            'privacy' => 'PUBLIC',
            'created_at' => $createdAt,
            'updated_at' => $updatedAt,
            'deleted_at' => $deletedAt,
        ], $documentArray);
    }

    /**
     * @test para garantir que a entidade pode ser facilmente ser instanciada a partir de um array
     */
    public function shouldInstanceFromArray(): void
    {
        $id = Str::uuid();
        $cpf = $this->faker->cpf();
        $createdAt = $this->now();
        $updatedAt = $this->tomorrow();
        $deletedAt = $this->nextMonth();
        $document = Document::fromArray([
            'id' => $id,
            'cpf' => $cpf,
            'documentType' => 'CPF',
            'privacy' => 'PRIVATE',
            'created_at' => $createdAt,
            'updated_at' => $updatedAt,
            'deleted_at' => $deletedAt,
        ]);
        self::assertEquals($id, $document->getId());
        self::assertEquals(DocumentTypeEnum::CPF(), $document->getDocumentType());
        self::assertEquals($cpf, $document->getValue());
        self::assertEquals(PrivacyEnum::PRIVATE(), $document->getPrivacy());
        self::assertEquals($createdAt, $document->getCreatedAt());
        self::assertEquals($updatedAt, $document->getUpdatedAt());
        self::assertEquals($deletedAt, $document->getDeletedAt());
    }

    /**
     * @test
     */
    public function shouldReturnFullDocument(): void
    {
        $documentStr = $this->faker->document();
        [$username, $domain] = explode('@', $documentStr);
        $document = new Document(
            username: $username,
            domain: $domain
        );
        self::assertEquals($documentStr, $document->getFullDocument());
    }
}
