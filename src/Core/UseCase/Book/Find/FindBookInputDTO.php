<?php

declare(strict_types=1);

namespace Core\UseCase\Book\Find;

class FindBookInputDTO
{
    public function __construct(public string $id)
    {
    }
}
