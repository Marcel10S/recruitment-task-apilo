<?php

namespace App\Infrastructure\Integrations\Inpost\DTO;

use Symfony\Component\Serializer\Annotation\SerializedName;

class InpostResponseDTO
{
    public function __construct(
        #[SerializedName("count")]
        public readonly int $count,
        #[SerializedName("page")]
        public readonly int $pages,
        #[SerializedName("total_pages")]
        public readonly int $totalPages,
        /** @var InpostItemDTO[] */
        #[SerializedName("items")]
        public readonly array $items
    ) {
    }
}