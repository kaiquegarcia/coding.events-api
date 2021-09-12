<?php

namespace Tests\Unit\Domain\Entities;

use Tests\TestCase;

class PhoneTest extends TestCase
{
    /**
     * @test para garantir que uma instância vazia não irá resultar em erro
     */
    public function shouldInstanceWithoutInput(): void
    {

    }

    /**
     * @test para garantir a possibilidade de gerar uma instância com os dados preenchidos
     */
    public function shouldInstanceWithInputs(): void
    {

    }

    /**
     * @test para garantir que o setter de DDI está funcionando
     */
    public function shouldSetDdiWithSetter(): void
    {

    }

    /**
     * @test para garantir que o setter de DDD está funcionando
     */
    public function shouldSetDddWithSetter(): void
    {

    }

    /**
     * @test para garantir que o setter de number está funcionando
     */
    public function shouldSetNumberWithSetter(): void
    {

    }

    /**
     * @test para garantir que o setter de privacy está funcionando
     */
    public function shouldSetPrivacyWithSetter(): void
    {

    }

    /**
     * @dataProvider privacyEnumsDataProvider
     * @test para garantir que a entidade faz o cast para PrivacyEnum
     */
    public function shouldInstancePrivacyEnums(string $privacyValue): void
    {

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
     * @test
     */
    public function shouldSerializeToJson(): void
    {

    }

    /**
     * @test
     */
    public function shouldUnserializeFromJson(): void
    {

    }
}
