<?php

declare(strict_types=1);

namespace Core\UseCase\Author\List;

class ListAuthorsOutputDTO
{
    public function __construct(public array $items)
    {
    }
}
