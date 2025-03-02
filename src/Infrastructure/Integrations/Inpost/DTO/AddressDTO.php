<?php

namespace App\Infrastructure\Integrations\Inpost\DTO;

use Symfony\Component\Serializer\Annotation\SerializedName;

readonly class AddressDTO
{
    public function __construct(
        #[SerializedName("city")]
        public string $city,
        #[SerializedName("province")]
        public string $province,
        #[SerializedName("post_code")]
        public string $postCode,
        #[SerializedName("street")]
        public string $street,
        #[SerializedName("building_number")]
        public string $buildingNumber,
        #[SerializedName("flat_number")]
        public ?string $flatNumber
    ) {
    }
}