<?php

namespace App\Tests\Inpost;

use App\Infrastructure\Integrations\Inpost\Client\Client;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class InpostClientTest extends TestCase
{
    private $logger;
    private $guzzleClient;
    private $client;

    protected function setUp(): void
    {
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->guzzleClient = $this->createMock(GuzzleClient::class);
        $this->client = new Client($this->logger);
        $this->setPrivateProperty($this->client, 'client', $this->guzzleClient);
    }

    public function testGetInpostDataSuccess()
    {
        $name = 'points';
        $params = ['city' => 'Jastarnia'];
        $responseBody = '{"count": 2, "page": 1, "total_pages": 1, "items": [
            {
                "address": {
                    "city": "Jastarnia",
                    "province": "pomorskie",
                    "postCode": "84-140",
                    "street": "Główczewskiego",
                    "buildingNumber": "7",
                    "flatNumber": null
                },
                "name": "JST01M"
            },
            {
                "address": {
                    "city": "Jastarnia",
                    "province": "pomorskie",
                    "postCode": "84-140",
                    "street": "Mickiewicza",
                    "buildingNumber": "127",
                    "flatNumber": null
                },
                "name": "JST01N"
            }
        ]}';

        $this->guzzleClient->expects($this->once())
            ->method('request')
            ->with('GET', sprintf(Client::API_URL_FORMAT, $name, http_build_query($params)))
            ->willReturn(new Response(200, [], $responseBody));

        $result = $this->client->getInpostData($name, $params);
        $this->assertEquals($responseBody, $result);
    }

    public function testGetInpostDataFailure()
    {
        $name = 'points';
        $params = ['param1' => 'value1'];
        $exception = new RequestException('API request failed', $this->createMock(\Psr\Http\Message\RequestInterface::class));

        $this->guzzleClient->expects($this->once())
            ->method('request')
            ->with('GET', sprintf(Client::API_URL_FORMAT, $name, http_build_query($params)))
            ->willThrowException($exception);

        $this->expectException(RequestException::class);
        $this->expectExceptionMessage('API request failed');

        $this->client->getInpostData($name, $params);
    }

    private function setPrivateProperty($object, $property, $value)
    {
        $reflection = new \ReflectionClass($object);
        $property = $reflection->getProperty($property);
        $property->setAccessible(true);
        $property->setValue($object, $value);
    }
}