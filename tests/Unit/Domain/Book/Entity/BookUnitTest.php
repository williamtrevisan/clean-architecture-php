<?php

declare(strict_types=1);

use Core\Domain\Book\Entity\Book;

it('should be throw an exception if library id received is null', function () {
    $payload = [
        'id' => 'bookId',
        'libraryId' => null,
        'title' => 'Book title',
        'pageNumber' => 196,
        'yearLaunched' => 2001,
    ];

    new Book(
        id: $payload['id'],
        libraryId: $payload['libraryId'],
        title: $payload['title'],
        pageNumber: $payload['pageNumber'],
        yearLaunched: $payload['yearLaunched'],
    );
})->throws(InvalidArgumentException::class, 'The library id is required');

it('should be throw an exception if name received dont has at least 3 characters', function () {
    $payload = [
        'id' => 'bookId',
        'libraryId' => 'libraryId',
        'title' => 'Bo',
        'pageNumber' => 196,
        'yearLaunched' => 2001,
    ];

    new Book(
        id: $payload['id'],
        libraryId: $payload['libraryId'],
        title: $payload['title'],
        pageNumber: $payload['pageNumber'],
        yearLaunched: $payload['yearLaunched'],
    );
})->throws(InvalidArgumentException::class, 'The title must be at least 3 characters');

it('should be throw an exception if name received is greater than 255 characters', function () {
    $payload = [
        'id' => 'bookId',
        'libraryId' => 'libraryId',
        'title' => random_bytes(256),
        'pageNumber' => 196,
        'yearLaunched' => 2001,
    ];

    new Book(
        id: $payload['id'],
        libraryId: $payload['libraryId'],
        title: $payload['title'],
        pageNumber: $payload['pageNumber'],
        yearLaunched: $payload['yearLaunched'],
    );
})->throws(InvalidArgumentException::class, 'The title must not be greater than 255 characters');

it('should be throw an exception if page number received is null', function () {
    $payload = [
        'id' => 'bookId',
        'libraryId' => 'libraryId',
        'title' => 'Book title',
        'pageNumber' => null,
        'yearLaunched' => 2001,
    ];

    new Book(
        id: $payload['id'],
        libraryId: $payload['libraryId'],
        title: $payload['title'],
        pageNumber: $payload['pageNumber'],
        yearLaunched: $payload['yearLaunched'],
    );
})->throws(InvalidArgumentException::class, 'The page number is required');

it('should be throw an exception if year launched received is null', function () {
    $payload = [
        'id' => 'bookId',
        'libraryId' => 'libraryId',
        'title' => 'Book title',
        'pageNumber' => 196,
        'yearLaunched' => null,
    ];

    new Book(
        id: $payload['id'],
        libraryId: $payload['libraryId'],
        title: $payload['title'],
        pageNumber: $payload['pageNumber'],
        yearLaunched: $payload['yearLaunched'],
    );
})->throws(InvalidArgumentException::class, 'The year launched is required');

it('should be able to create a new book', function () {
    $payload = [
        'id' => 'bookId',
        'libraryId' => 'libraryId',
        'title' => 'Book title',
        'pageNumber' => 196,
        'yearLaunched' => 2001,
    ];

    $book = new Book(
        id: $payload['id'],
        libraryId: $payload['libraryId'],
        title: $payload['title'],
        pageNumber: $payload['pageNumber'],
        yearLaunched: $payload['yearLaunched'],
    );

    expect($book->id)->not()->toBeEmpty()
        ->and($book)->toMatchObject([
            'libraryId' => $payload['libraryId'],
            'title' => $payload['title'],
            'pageNumber' => $payload['pageNumber'],
            'yearLaunched' => $payload['yearLaunched'],
        ]);
});

it('should be able to create a new book sending an id', function () {
    $payload = [
        'id' => 'bookId',
        'libraryId' => 'libraryId',
        'title' => 'Book title',
        'pageNumber' => 196,
        'yearLaunched' => 2001,
    ];

    $book = new Book(
        id: $payload['id'],
        libraryId: $payload['libraryId'],
        title: $payload['title'],
        pageNumber: $payload['pageNumber'],
        yearLaunched: $payload['yearLaunched'],
    );

    expect($book)->toMatchObject([
        'id' => $payload['id'],
        'libraryId' => $payload['libraryId'],
        'title' => $payload['title'],
        'pageNumber' => $payload['pageNumber'],
        'yearLaunched' => $payload['yearLaunched'],
    ]);
});

it('should be able to add an author to book', function () {
    $authorId = 'authorId';
    $book = new Book(
        id: 'bookId',
        libraryId: 'libraryId',
        title: 'Book title',
        pageNumber: 201,
        yearLaunched: 2009,
    );

    $book->addAuthor(authorId: $authorId);

    expect($book->authorsId)->toHaveCount(1)
        ->and($book->authorsId)->toBe([$authorId]);
});

it('should be able to add more than once authors to book', function () {
    $authorId1 = 'authorId1';
    $authorId2 = 'authorId2';
    $book = new Book(
        id: 'bookId',
        libraryId: 'libraryId',
        title: 'Book title',
        pageNumber: 201,
        yearLaunched: 2009,
    );

    $book->addAuthor(authorId: $authorId1);
    $book->addAuthor(authorId: $authorId2);

    expect($book->authorsId)->toHaveCount(2)
        ->and($book->authorsId)->toBe([$authorId1, $authorId2]);
});

it('should be able to remove an author to book', function () {
    $authorId = 'authorId';
    $book = new Book(
        id: 'bookId',
        libraryId: 'libraryId',
        title: 'Book title',
        pageNumber: 201,
        yearLaunched: 2009,
    );
    $book->addAuthor(authorId: $authorId);

    $book->removeAuthor(authorId: $authorId);

    expect($book->authorsId)->toHaveCount(0)
        ->and($book->authorsId)->toBeEmpty();
});

it('should be able to update a book library id', function () {
    $payload = ['libraryId' => 'libraryId'];

    $book = new Book(
        id: 'bookId',
        libraryId: 'libraryId',
        title: 'Book title',
        pageNumber: 201,
        yearLaunched: 2009,
    );
    $book->update(libraryId: $payload['libraryId']);

    expect($payload['libraryId'])->toBe($book->libraryId);
});

it('should be throw an exception if name received to update dont has at least 3 characters', function () {
    $payload = ['title' => 'Bo'];

    $book = new Book(
        id: 'bookId',
        libraryId: 'libraryId',
        title: 'Book title',
        pageNumber: 201,
        yearLaunched: 2009,
    );
    $book->update(title: $payload['title']);
})->throws(InvalidArgumentException::class, 'The title must be at least 3 characters');

it('should be throw an exception if name received to update is greater than 255 characters', function () {
    $payload = ['title' => random_bytes(256)];

    $book = new Book(
        id: 'bookId',
        libraryId: 'libraryId',
        title: 'Book title',
        pageNumber: 201,
        yearLaunched: 2009,
    );
    $book->update(title: $payload['title']);
})->throws(InvalidArgumentException::class, 'The title must not be greater than 255 characters');

it('should be able to update a book title', function () {
    $payload = ['title' => 'Book title updated'];

    $book = new Book(
        id: 'bookId',
        libraryId: 'libraryId',
        title: 'Book title',
        pageNumber: 201,
        yearLaunched: 2009,
    );
    $book->update(title: $payload['title']);

    expect($payload['title'])->toBe($book->title);
});

it('should be able to update a book page number', function () {
    $payload = ['pageNumber' => 251];

    $book = new Book(
        id: 'bookId',
        libraryId: 'libraryId',
        title: 'Book title',
        pageNumber: 201,
        yearLaunched: 2009,
    );
    $book->update(pageNumber: $payload['pageNumber']);

    expect($payload['pageNumber'])->toBe($book->pageNumber);
});

it('should be able to update a book year launched', function () {
    $payload = ['yearLaunched' => 2010];

    $book = new Book(
        id: 'bookId',
        libraryId: 'libraryId',
        title: 'Book title',
        pageNumber: 201,
        yearLaunched: 2009,
    );
    $book->update(yearLaunched: $payload['yearLaunched']);

    expect($payload['yearLaunched'])->toBe($book->yearLaunched);
});

it('should be able to update all book data', function () {
    $payload = [
        'libraryId' => 'libraryId',
        'title' => 'Book title updated',
        'pageNumber' => 251,
        'yearLaunched' => 2010,
    ];

    $book = new Book(
        id: 'bookId',
        libraryId: 'libraryId',
        title: 'Book title',
        pageNumber: 201,
        yearLaunched: 2009,
    );
    $book->update(
        libraryId: $payload['libraryId'],
        title: $payload['title'],
        pageNumber: $payload['pageNumber'],
        yearLaunched: $payload['yearLaunched'],
    );

    expect($book)->toMatchObject([
        'libraryId' => $payload['libraryId'],
        'title' => $payload['title'],
        'pageNumber' => $payload['pageNumber'],
        'yearLaunched' => $payload['yearLaunched'],
    ]);
});
