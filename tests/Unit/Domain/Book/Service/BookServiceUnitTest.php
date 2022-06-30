<?php

declare(strict_types=1);

use Core\Domain\Book\Entity\Book;
use Core\Domain\Book\Service\BookService;
use Core\Domain\shared\ValueObject\Uuid;
use Ramsey\Uuid\Uuid as RamseyUuid;

it('should be able to change library id of all books', function () {
    $libraryId = RamseyUuid::uuid4()->toString();
    $libraryIdForUpdate = RamseyUuid::uuid4()->toString();
    $book1 = new Book(
        libraryId: new Uuid($libraryId),
        title: 'Book1 title',
        pageNumber: 201,
        yearLaunched: 2009,
        id: new Uuid(RamseyUuid::uuid4()->toString()),
    );
    $book2 = new Book(
        libraryId: new Uuid($libraryId),
        title: 'Book2 title',
        pageNumber: 201,
        yearLaunched: 2009,
        id: new Uuid(RamseyUuid::uuid4()->toString()),
    );

    BookService::changeLibraryId([$book1, $book2], new Uuid($libraryIdForUpdate));

    expect($book1->getLibraryId())->toBe($libraryIdForUpdate)
        ->and($book2->getLibraryId())->toBe($libraryIdForUpdate);
});
