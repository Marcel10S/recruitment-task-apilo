<?php

namespace App\Infrastructure\Integrations\Inpost\Client;

use App\Infrastructure\Integrations\Inpost\DTO\InpostParamsDataDTO;

interface InpostClientInterface
{
    public function getInpostDataBody(InpostParamsDataDTO $inpostParamsData): string;
}
