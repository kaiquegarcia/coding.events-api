<?php

namespace App\Domain\Components\Http;

interface ResponseInterface
{
    public function getString(): string;
    public function getFromJson(): array;
    public function getFromQuery(): array;
}
