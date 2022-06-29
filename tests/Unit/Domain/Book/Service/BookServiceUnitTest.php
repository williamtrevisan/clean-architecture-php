<?php

declare(strict_types=1);

use Core\Domain\Book\Entity\Book;
use Core\Domain\Book\Service\BookService;

it('should be able to change library id of all books', function () {
    $libraryId = 'libraryId2';
    $book1 = new Book(
        id: 'bookId1',
        libraryId: 'libraryId',
        title: 'Book1 title',
        pageNumber: 201,
        yearLaunched: 2009,
    );
    $book2 = new Book(
        id: 'bookId2',
        libraryId: 'libraryId',
        title: 'Book2 title',
        pageNumber: 201,
        yearLaunched: 2009,
    );

    BookService::changeLibraryId([$book1, $book2], $libraryId);

    expect($book1->libraryId)->toBe($libraryId)
        ->and($book2->libraryId)->toBe($libraryId);
});
