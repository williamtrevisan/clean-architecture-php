<?php

declare(strict_types=1);

namespace Core\UseCase\Book\Update\Book;

class UpdateBookInputDTO
{
    public function __construct(
        public string $id,
        public string $libraryId = '',
        public string $title = '',
        public ?int $numberOfPages = null,
        public ?int $yearLaunched = null,
    ) {
    }
}
