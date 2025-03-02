<?php

namespace App\Infrastructure\Integrations\Inpost\DTO;

use Symfony\Component\Serializer\Annotation\SerializedName;

readonly class InpostResponseDTO
{
    public function __construct(
        #[SerializedName("count")]
        public int $count,
        #[SerializedName("page")]
        public int $pages,
        #[SerializedName("total_pages")]
        public int $totalPages,
        /** @var InpostItemDTO[] */
        #[SerializedName("items")]
        public array $items
    ) {
    }
}