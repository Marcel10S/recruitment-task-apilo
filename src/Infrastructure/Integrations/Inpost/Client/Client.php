<?php

namespace App\Infrastructure\Integrations\Inpost\Client;

use App\Infrastructure\Integrations\Inpost\DTO\InpostParamsDataDTO;
use GuzzleHttp\Client as GuzzleClient;
use Psr\Log\LoggerInterface;

class Client implements InpostClientInterface
{
    const string API_POINT_NAME = "points";

    private GuzzleClient $client;

    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly string $inpostApiURL,
    ) {
        $this->client = new GuzzleClient();
    }

    private function generateApiUrl(InpostParamsDataDTO $inpostParamsData): string
    {
        return sprintf("%s/%s?%s", $this->inpostApiURL, $inpostParamsData->methodName, http_build_query($inpostParamsData->params));
    }

    public function getInpostDataBody(InpostParamsDataDTO $inpostParamsData): string
    {
        try {
            $response = $this->client->request("GET", $this->generateApiUrl($inpostParamsData));

            return $response->getBody()->getContents();
        } catch (\Exception $exception) {
            $this->logger->error("Inpost API request failed: " . $exception->getMessage());

            throw $exception;
        }
    }
}