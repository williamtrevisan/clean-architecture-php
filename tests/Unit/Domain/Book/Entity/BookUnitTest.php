<?php

declare(strict_types=1);

use Core\Domain\Book\Entity\Book;
use Core\Domain\shared\ValueObject\Uuid;
use Ramsey\Uuid\Uuid as RamseyUuid;

it('should be throw an exception if name received dont has at least 3 characters', function () {
    $payload = [
        'libraryId' => RamseyUuid::uuid4()->toString(),
        'title' => 'Bo',
        'numberOfPages' => 196,
        'yearLaunched' => 2001,
    ];

    new Book(
        libraryId: new Uuid($payload['libraryId']),
        title: $payload['title'],
        numberOfPages: $payload['numberOfPages'],
        yearLaunched: $payload['yearLaunched'],
    );
})->throws(InvalidArgumentException::class, 'The title must be at least 3 characters');

it('should be throw an exception if name received is greater than 255 characters', function () {
    $payload = [
        'libraryId' => RamseyUuid::uuid4()->toString(),
        'title' => random_bytes(256),
        'numberOfPages' => 196,
        'yearLaunched' => 2001,
    ];

    new Book(
        libraryId: new Uuid($payload['libraryId']),
        title: $payload['title'],
        numberOfPages: $payload['numberOfPages'],
        yearLaunched: $payload['yearLaunched'],
    );
})->throws(InvalidArgumentException::class, 'The title must not be greater than 255 characters');

it('should be throw an exception if number of pages received is null', function () {
    $payload = [
        'libraryId' => RamseyUuid::uuid4()->toString(),
        'title' => 'Book title',
        'numberOfPages' => null,
        'yearLaunched' => 2001,
    ];

    new Book(
        libraryId: new Uuid($payload['libraryId']),
        title: $payload['title'],
        numberOfPages: $payload['numberOfPages'],
        yearLaunched: $payload['yearLaunched'],
    );
})->throws(InvalidArgumentException::class, 'The number of pages is required');

it('should be throw an exception if year launched received is null', function () {
    $payload = [
        'libraryId' => RamseyUuid::uuid4()->toString(),
        'title' => 'Book title',
        'numberOfPages' => 196,
        'yearLaunched' => null,
    ];

    new Book(
        libraryId: new Uuid($payload['libraryId']),
        title: $payload['title'],
        numberOfPages: $payload['numberOfPages'],
        yearLaunched: $payload['yearLaunched'],
    );
})->throws(InvalidArgumentException::class, 'The year launched is required');

test('should be able to create a new book', function () {
    $payload = [
        'libraryId' => RamseyUuid::uuid4()->toString(),
        'title' => 'Book title',
        'numberOfPages' => 196,
        'yearLaunched' => 2001,
    ];

    $book = new Book(
        libraryId: new Uuid($payload['libraryId']),
        title: $payload['title'],
        numberOfPages: $payload['numberOfPages'],
        yearLaunched: $payload['yearLaunched'],
    );

    expect($book->id)->not()->toBeEmpty()
        ->and($book)->toMatchObject([
            'libraryId' => new Uuid($payload['libraryId']),
            'title' => $payload['title'],
            'numberOfPages' => $payload['numberOfPages'],
            'yearLaunched' => $payload['yearLaunched'],
        ]);
});

test('should be able to create a new book sending an id', function () {
    $payload = [
        'id' => RamseyUuid::uuid4()->toString(),
        'libraryId' => RamseyUuid::uuid4()->toString(),
        'title' => 'Book title',
        'numberOfPages' => 196,
        'yearLaunched' => 2001,
    ];

    $book = new Book(
        libraryId: new Uuid($payload['libraryId']),
        title: $payload['title'],
        numberOfPages: $payload['numberOfPages'],
        yearLaunched: $payload['yearLaunched'],
        id: new Uuid($payload['id']),
    );

    expect($book)->toMatchObject([
        'id' => $payload['id'],
        'libraryId' => new Uuid($payload['libraryId']),
        'title' => $payload['title'],
        'numberOfPages' => $payload['numberOfPages'],
        'yearLaunched' => $payload['yearLaunched'],
    ]);
});

test('should be able to add an author to book', function () {
    $authorId = 'authorId';
    $book = new Book(
        libraryId: new Uuid(RamseyUuid::uuid4()->toString()),
        title: 'Book title',
        numberOfPages: 201,
        yearLaunched: 2009,
    );

    $book->addAuthor(authorId: $authorId);

    expect($book->authorsId)->toHaveCount(1)
        ->and($book->authorsId)->toBe([$authorId]);
});

test('should be able to add more than once authors to book', function () {
    $authorId1 = 'authorId1';
    $authorId2 = 'authorId2';
    $book = new Book(
        libraryId: new Uuid(RamseyUuid::uuid4()->toString()),
        title: 'Book title',
        numberOfPages: 201,
        yearLaunched: 2009,
    );

    $book->addAuthor(authorId: $authorId1);
    $book->addAuthor(authorId: $authorId2);

    expect($book->authorsId)->toHaveCount(2)
        ->and($book->authorsId)->toBe([$authorId1, $authorId2]);
});

test('should be able to remove an author to book', function () {
    $authorId = 'authorId';
    $book = new Book(
        libraryId: new Uuid(RamseyUuid::uuid4()->toString()),
        title: 'Book title',
        numberOfPages: 201,
        yearLaunched: 2009,
    );
    $book->addAuthor(authorId: $authorId);

    $book->removeAuthor(authorId: $authorId);

    expect($book->authorsId)->toHaveCount(0)
        ->and($book->authorsId)->toBeEmpty();
});

test('should be able to update a book library id', function () {
    $payload = ['libraryId' => RamseyUuid::uuid4()->toString()];

    $book = new Book(
        libraryId: new Uuid(RamseyUuid::uuid4()->toString()),
        title: 'Book title',
        numberOfPages: 201,
        yearLaunched: 2009,
    );
    $book->update(libraryId: new Uuid($payload['libraryId']));

    expect($book->getLibraryId())->toBe($payload['libraryId']);
});

it('should be throw an exception if name received to update dont has at least 3 characters', function () {
    $payload = ['title' => 'Bo'];

    $book = new Book(
        libraryId: new Uuid(RamseyUuid::uuid4()->toString()),
        title: 'Book title',
        numberOfPages: 201,
        yearLaunched: 2009,
    );
    $book->update(title: $payload['title']);
})->throws(InvalidArgumentException::class, 'The title must be at least 3 characters');

it('should be throw an exception if name received to update is greater than 255 characters', function () {
    $payload = ['title' => random_bytes(256)];

    $book = new Book(
        libraryId: new Uuid(RamseyUuid::uuid4()->toString()),
        title: 'Book title',
        numberOfPages: 201,
        yearLaunched: 2009,
    );
    $book->update(title: $payload['title']);
})->throws(InvalidArgumentException::class, 'The title must not be greater than 255 characters');

test('should be able to update a book title', function () {
    $payload = ['title' => 'Book title updated'];

    $book = new Book(
        libraryId: new Uuid(RamseyUuid::uuid4()->toString()),
        title: 'Book title',
        numberOfPages: 201,
        yearLaunched: 2009,
    );
    $book->update(title: $payload['title']);

    expect($payload['title'])->toBe($book->title);
});

test('should be able to update a book number of pages', function () {
    $payload = ['numberOfPages' => 251];

    $book = new Book(
        libraryId: new Uuid(RamseyUuid::uuid4()->toString()),
        title: 'Book title',
        numberOfPages: 201,
        yearLaunched: 2009,
    );
    $book->update(numberOfPages: $payload['numberOfPages']);

    expect($payload['numberOfPages'])->toBe($book->numberOfPages);
});

test('should be able to update a book year launched', function () {
    $payload = ['yearLaunched' => 2010];

    $book = new Book(
        libraryId: new Uuid(RamseyUuid::uuid4()->toString()),
        title: 'Book title',
        numberOfPages: 201,
        yearLaunched: 2009,
    );
    $book->update(yearLaunched: $payload['yearLaunched']);

    expect($payload['yearLaunched'])->toBe($book->yearLaunched);
});

test('should be able to update all book data', function () {
    $payload = [
        'libraryId' => RamseyUuid::uuid4()->toString(),
        'title' => 'Book title updated',
        'numberOfPages' => 251,
        'yearLaunched' => 2010,
    ];

    $book = new Book(
        libraryId: new Uuid(RamseyUuid::uuid4()->toString()),
        title: 'Book title',
        numberOfPages: 201,
        yearLaunched: 2009,
    );
    $book->update(
        libraryId: new Uuid($payload['libraryId']),
        title: $payload['title'],
        numberOfPages: $payload['numberOfPages'],
        yearLaunched: $payload['yearLaunched'],
    );

    expect($book)->toMatchObject([
        'libraryId' => new Uuid($payload['libraryId']),
        'title' => $payload['title'],
        'numberOfPages' => $payload['numberOfPages'],
        'yearLaunched' => $payload['yearLaunched'],
    ]);
});
