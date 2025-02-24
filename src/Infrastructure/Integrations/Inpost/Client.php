<?php

namespace App\Infrastructure\Integrations\Inpost;

use GuzzleHttp\Client as GuzzleClient;
use Psr\Log\LoggerInterface;


class Client
{
    // Nikt nic nie wspominał o paginacij więc tymczasowo zostawiam domyślne
    const API_URL_FORMAT = "https://api-shipx-pl.easypack24.net/v1/%s?%s";
    const API_POINT_NAME = "points";

    private GuzzleClient $client;

    public function __construct(private LoggerInterface $logger)
    {
        $this->client = new GuzzleClient();
    }

    public function generateParams($params): string
    {
        return http_build_query($params);
    }
    

    public function getInpostData(string $name, array $params = []): string
    {
        try {
            $response = $this->client->request("GET", sprintf(self::API_URL_FORMAT, $name, $this->generateParams($params)));

            return $response->getBody()->getContents();
        } catch (\Exception $exception) {
            $this->logger->error("Inpost API request failed: " . $exception->getMessage());

            throw $exception;
        }
    }
}
