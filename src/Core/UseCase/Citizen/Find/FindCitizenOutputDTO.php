<?php

namespace Core\UseCase\Citizen\Find;

class FindCitizenOutputDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public string $email,
    ) {
    }
}
