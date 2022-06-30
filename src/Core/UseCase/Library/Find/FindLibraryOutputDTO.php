<?php

namespace Core\UseCase\Library\Find;

class FindLibraryOutputDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public string $email,
    ) {
    }
}
