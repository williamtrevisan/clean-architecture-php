<?php

namespace Core\UseCase\Library\Create;

class CreateLibraryOutputDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public string $email,
    ) {
    }
}
