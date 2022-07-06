<?php

declare(strict_types=1);

namespace Core\UseCase\Author\Create;

class CreateAuthorOutputDTO
{
    public function __construct(public string $id, public string $name)
    {
    }
}
