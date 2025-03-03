<?php

namespace App\Infrastructure\Integrations\Inpost\DTO;

use App\Infrastructure\Integrations\Inpost\DTO\AddressDTO;
use Symfony\Component\Serializer\Annotation\SerializedName;

readonly class InpostItemDTO
{
    public AddressDTO $address;

    public function __construct(
        #[SerializedName("name")]
        public string $name,
        #[SerializedName("address_details")]
        AddressDTO $address_details,
    ) {
        $this->address = $address_details;
    }
}