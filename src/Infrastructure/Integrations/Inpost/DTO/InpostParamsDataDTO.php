<?php

namespace App\Infrastructure\Integrations\Inpost\DTO;

readonly class InpostParamsDataDTO
{
    public function __construct(
        public string $methodName,
        public string $objectClass,
        public array $params = [],
    ) {
    }
}