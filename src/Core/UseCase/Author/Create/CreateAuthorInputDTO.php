<?php

declare(strict_types=1);

namespace Core\UseCase\Author\Create;

class CreateAuthorInputDTO
{
    public function __construct(public string $name)
    {
    }
}
