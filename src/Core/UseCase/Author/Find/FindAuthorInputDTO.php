<?php

declare(strict_types=1);

namespace Core\UseCase\Author\Find;

class FindAuthorInputDTO
{
    public function __construct(public string $id)
    {
    }
}
