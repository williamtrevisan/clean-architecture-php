<?php

declare(strict_types=1);

namespace Core\UseCase\Book\List;

class ListBooksOutputDTO
{
    public function __construct(public array $items)
    {
    }
}
