<?php

namespace Core\UseCase\Citizen\Find;

class FindCitizenInputDTO
{
    public function __construct(public string $id)
    {
    }
}
