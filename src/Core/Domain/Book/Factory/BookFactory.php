<?php

declare(strict_types=1);

namespace Core\Domain\Book\Factory;

use Core\Domain\Book\Entity\Book;
use Ramsey\Uuid\Uuid;

class BookFactory
{
    public static function create(array $payload): Book
    {
        return new Book(
            id: Uuid::uuid4()->toString(),
            libraryId: $payload['libraryId'],
            title: $payload['title'],
            pageNumber: $payload['pageNumber'],
            yearLaunched: $payload['yearLaunched'],
        );
    }
}
