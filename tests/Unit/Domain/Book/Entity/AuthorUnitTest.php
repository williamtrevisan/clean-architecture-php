<?php

declare(strict_types=1);

use Core\Domain\Book\Entity\Author;

it('should be throw an exception if name received dont has at least 3 characters', function () {
    $payload = ['id' => 'authorId', 'name' => 'Ci'];

    new Author(id: $payload['id'], name: $payload['name']);
})->throws(InvalidArgumentException::class);

it('should be throw an exception if name received is greater than 255 characters', function () {
    $payload = ['id' => 'authorId', 'name' => random_bytes(256)];

    new Author(id: $payload['id'], name: $payload['name']);
})->throws(InvalidArgumentException::class);

it('should be able to create a new author', function () {
    $payload = ['id' => 'authorId', 'name' => 'Author name'];

    $author = new Author(id: $payload['id'], name: $payload['name']);

    expect($author)->toMatchObject([
        'id' => $payload['id'],
        'name' => $payload['name'],
    ]);
});

it('should be throw an exception if name received in change name method dont has at least 3 characters', function () {
    $payload = ['name' => 'Ci'];

    $author = new Author(id: 'authorId', name: 'Author name');
    $author->changeName(name: $payload['name']);
})->throws(InvalidArgumentException::class);

it('should be throw an exception if name received in change name is greater than 255 characters', function () {
    $payload = ['name' => 'Ci'];

    $author = new Author(id: 'authorId', name: 'Author name');
    $author->changeName(name: $payload['name']);
})->throws(InvalidArgumentException::class);

it('should be able to change author name', function () {
    $payload = ['name' => 'Author name updated'];

    $author = new Author(id: 'authorId', name: 'Author name');
    $author->changeName(name: $payload['name']);

    expect($author->name)->toBe($payload['name']);
});
