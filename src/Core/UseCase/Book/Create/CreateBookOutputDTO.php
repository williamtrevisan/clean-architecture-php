<?php

declare(strict_types=1);

namespace Core\UseCase\Book\Create;

class CreateBookOutputDTO
{
    public function __construct(
        public string $id,
        public string $library_id,
        public string $title,
        public int $number_of_pages,
        public int $year_launched,
    ) {
    }
}
