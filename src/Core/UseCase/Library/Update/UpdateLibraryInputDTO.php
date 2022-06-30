<?php

namespace Core\UseCase\Library\Update;

class UpdateLibraryInputDTO
{
    public function __construct(
        public string $id,
        public string $name = '',
        public string $email = '',
    ) {
    }
}
