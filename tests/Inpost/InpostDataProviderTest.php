<?php

namespace App\Tests\Inpost;

use App\Infrastructure\Integrations\Inpost\Client\Client;
use App\Infrastructure\Integrations\Inpost\DTO\InpostResponseDTO;
use App\Infrastructure\Integrations\Inpost\Provider\InpostDataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\SerializerInterface;

class InpostDataProviderTest extends TestCase
{
    private $serializer;
    private $client;
    private $inpostDataProvider;

    protected function setUp(): void
    {
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->client = $this->createMock(Client::class);
        $this->inpostDataProvider = new InpostDataProvider($this->serializer, $this->client);
    }

    public function testGetInpostDataSuccess()
    {
        $name = 'points';
        $params = ['city' => 'Jastarnia'];
        $rawResponse = '{"count": 2, "page": 1, "total_pages": 1, "items": [
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

        $expectedDTO = new InpostResponseDTO(2, 1, 1, [
            [
                "name" => "JST01M",
                "address" => [
                    "city" => "Jastarnia",
                    "province" => "pomorskie",
                    "postCode" => "84-140",
                    "street" =>"Główczewskiego",
                    "buildingNumber" => "7",
                    "flatNumber" => null
                ],
            ],
            [
                "name" => "JST01N",
                "address" => [
                    "city" => "Jastarnia",
                    "province" => "pomorskie",
                    "postCode" => "84-140",
                    "street" => "Mickiewicza",
                    "buildingNumber" => "127",
                    "flatNumber" => null
                ],
            ]
        ]);

        $this->client->expects($this->once())
            ->method('getInpostData')
            ->with($name, $params)
            ->willReturn($rawResponse);

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with($rawResponse, InpostResponseDTO::class, 'json')
            ->willReturn($expectedDTO);

        $result = $this->inpostDataProvider->getInpostData($name, InpostResponseDTO::class, $params);
        $this->assertEquals($expectedDTO, $result);
    }

    public function testGetInpostDataFailure()
    {
        $name = 'points';
        $params = ['city' => 'Jastarnia'];
        $exception = new \Exception('API request failed');

        $this->client->expects($this->once())
            ->method('getInpostData')
            ->with($name, $params)
            ->willThrowException($exception);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('API request failed');

        $this->inpostDataProvider->getInpostData($name, InpostResponseDTO::class, $params);
    }
}