<?php

namespace App\Infrastructure\Integrations\Inpost\Provider;

use App\Infrastructure\Integrations\Inpost\DTO\InpostParamsDataDTO;
use App\Infrastructure\Integrations\Inpost\Client\InpostClientInterface;
use Symfony\Component\Serializer\SerializerInterface;

class InpostDataProvider
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly InpostClientInterface $client,
    ) {
    }

    /**
     * @template T
     * @param class-string<T> $objectClass
     * @return T
     * @throws \Exception
     */
    public function getInpostData(InpostParamsDataDTO $inpostParamsData) : mixed
    {
        try {
            $rawResponse = $this->client->getInpostDataBody($inpostParamsData);
            return $this->serializer->deserialize($rawResponse, $inpostParamsData->objectClass, 'json');
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}
