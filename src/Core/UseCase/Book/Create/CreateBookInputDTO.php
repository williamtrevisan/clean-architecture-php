<?php

declare(strict_types=1);

namespace Core\UseCase\Book\Create;

class CreateBookInputDTO
{
    public function __construct(
        public string $libraryId,
        public string $title,
        public int $numberOfPages,
        public int $yearLaunched,
        public array $authorsId = [],
    ) {
    }
}
