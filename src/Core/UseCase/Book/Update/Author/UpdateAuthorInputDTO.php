<?php

declare(strict_types=1);

namespace Core\UseCase\Book\Update\Author;

class UpdateAuthorInputDTO
{
    public function __construct(public string $id, public string $name)
    {
    }
}
