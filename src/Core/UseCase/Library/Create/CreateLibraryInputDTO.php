<?php

namespace Core\UseCase\Library\Create;

class CreateLibraryInputDTO
{
    public function __construct(
        public string $name,
        public string $email,
    ) {
    }
}
