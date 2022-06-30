<?php

namespace Core\UseCase\Citizen\Update;

class UpdateCitizenInputDTO
{
    public function __construct(
        public string $id,
        public string $name = '',
        public string $email = '',
    ) {
    }
}
