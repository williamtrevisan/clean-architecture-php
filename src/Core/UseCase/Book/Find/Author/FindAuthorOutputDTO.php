<?php

declare(strict_types=1);

namespace Core\UseCase\Book\Find\Author;

class FindAuthorOutputDTO
{
    public function __construct(public string $id, public string $name)
    {
    }
}
