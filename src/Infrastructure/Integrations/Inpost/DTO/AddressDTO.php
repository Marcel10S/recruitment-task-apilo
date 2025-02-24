<?php

namespace App\Infrastructure\Integrations\Inpost\DTO;

use Symfony\Component\Serializer\Annotation\SerializedName;

class AddressDTO
{
    public function __construct(
        #[SerializedName("city")]
        public readonly string $city,
        #[SerializedName("province")]
        public readonly string $province,
        #[SerializedName("post_code")]
        public readonly string $postCode,
        #[SerializedName("street")]
        public readonly string $street,
        #[SerializedName("building_number")]
        public readonly string $buildingNumber,
        #[SerializedName("flat_number")]
        public readonly ?string $flatNumber
    ) {
    }
}