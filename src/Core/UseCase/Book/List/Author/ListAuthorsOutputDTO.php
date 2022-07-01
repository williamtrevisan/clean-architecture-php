<?php

declare(strict_types=1);

namespace Core\UseCase\Book\List\Author;

class ListAuthorsOutputDTO
{
    public function __construct(public array $items)
    {
    }
}
