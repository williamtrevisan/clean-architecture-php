<?php

declare(strict_types=1);

namespace Core\Domain\Book\Service;

use Core\Domain\shared\ValueObject\Uuid;

class BookService
{
    public static function changeLibraryId(
        array $listBooks,
        Uuid $libraryId
    ): array {
        foreach ($listBooks as $book) {
            $book->update(libraryId: $libraryId);
        }

        return $listBooks;
    }
}
