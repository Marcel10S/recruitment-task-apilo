<?php

namespace App\Infrastructure\Integrations\Inpost\Provider;

use App\Infrastructure\Integrations\Inpost\Client;
use App\Infrastructure\Integrations\Inpost\DTO\InpostResponseDTO;
use Symfony\Component\Serializer\SerializerInterface;

class InpostDataProvider
{
    const API_POINT_NAME = "points";

    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly Client $client,
    ) {
    }

    public function getInpostData(string $name, string $city) : InpostResponseDTO 
    {
        try {
            $rawResponse = $this->client->getInpostData($name, $city);
            return $this->serializer->deserialize($rawResponse, InpostResponseDTO::class, 'json');
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}
