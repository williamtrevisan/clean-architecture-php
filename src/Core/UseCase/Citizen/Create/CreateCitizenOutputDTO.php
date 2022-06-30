<?php

namespace Core\UseCase\Citizen\Create;

class CreateCitizenOutputDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public string $email,
    ) {
    }
}
