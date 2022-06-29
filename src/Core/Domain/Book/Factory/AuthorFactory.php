<?php

declare(strict_types=1);

namespace Core\Domain\Book\Factory;

use Core\Domain\Book\Entity\Author;
use Ramsey\Uuid\Uuid;

class AuthorFactory
{
    public static function create(array $payload): Author
    {
        return new Author(
            id: Uuid::uuid4()->toString(),
            name: $payload['name'],
        );
    }
}
