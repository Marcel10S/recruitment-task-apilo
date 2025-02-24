<?php

namespace App\Infrastructure\Integrations\Inpost\Provider;

use App\Infrastructure\Integrations\Inpost\Client;
use App\Infrastructure\Integrations\Inpost\DTO\InpostResponseDTO;
use Symfony\Component\Serializer\SerializerInterface;

class InpostDataProvider
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly Client $client,
    ) {
    }

    public function getInpostData(string $name, string $objectClass, array $params = []) : InpostResponseDTO 
    {
        try {
            $rawResponse = $this->client->getInpostData($name, $params);
            return $this->serializer->deserialize($rawResponse, $objectClass, 'json');
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}
