<?php

declare(strict_types=1);

use Domain\Book\Factory\BookFactory;

it('should be able to create a new book', function () {
    $payload = [
        'libraryId' => 'libraryId',
        'title' => 'Book title',
        'pageNumber' => 200,
        'yearLaunched' => 2001
    ];

    $book = BookFactory::create($payload);

    expect($book->id)->not->toBeEmpty()
        ->and($book)->toMatchObject([
            'libraryId' => $payload['libraryId'],
            'title' => $payload['title'],
            'pageNumber' => $payload['pageNumber'],
            'yearLaunched' => $payload['yearLaunched'],
        ]);
});
