<?php

declare(strict_types=1);

namespace Core\UseCase\Author\Update;

class UpdateAuthorInputDTO
{
    public function __construct(public string $id, public string $name)
    {
    }
}
