<?php

namespace Core\UseCase\Citizen\Create;

class CreateCitizenInputDTO
{
    public function __construct(
        public string $name,
        public string $email,
    ) {
    }
}
