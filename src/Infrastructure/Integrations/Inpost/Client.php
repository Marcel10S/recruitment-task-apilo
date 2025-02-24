<?php

namespace App\Infrastructure\Integrations\Inpost;

use GuzzleHttp\Client as GuzzleClient;
use Psr\Log\LoggerInterface;


class Client
{
    const API_URL_FORMAT = "https://api-shipx-pl.easypack24.net/v1/%s?city=%s";

    private GuzzleClient $client;

    public function __construct(private LoggerInterface $logger)
    {
        $this->client = new GuzzleClient();
    }

    public function getInpostData(string $name, string $city): string
    {
        try {
            $response = $this->client->request("GET", sprintf(self::API_URL_FORMAT, $name, $city));

            return $response->getBody()->getContents();
        } catch (\Exception $exception) {
            $this->logger->error("Inpost API request failed: " . $exception->getMessage());

            throw $exception;
        }
    }
}
