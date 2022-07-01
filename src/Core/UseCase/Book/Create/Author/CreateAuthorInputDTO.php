<?php

declare(strict_types=1);

namespace Core\UseCase\Book\Create\Author;

class CreateAuthorInputDTO
{
    public function __construct(public string $name)
    {
    }
}
