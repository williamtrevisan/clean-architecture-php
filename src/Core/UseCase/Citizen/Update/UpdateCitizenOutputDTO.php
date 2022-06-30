<?php

namespace Core\UseCase\Citizen\Update;

class UpdateCitizenOutputDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public string $email,
    ) {
    }
}
