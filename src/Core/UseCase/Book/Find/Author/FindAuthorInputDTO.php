<?php

declare(strict_types=1);

namespace Core\UseCase\Book\Find\Author;

class FindAuthorInputDTO
{
    public function __construct(public string $id)
    {
    }
}
