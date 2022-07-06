<?php

declare(strict_types=1);

use Core\Domain\Author\Entity\Author;
use Core\Domain\shared\ValueObject\Uuid;
use Ramsey\Uuid\Uuid as RamseyUuid;

it('should be throw an exception if name received dont has at least 3 characters', function () {
    $payload = ['name' => 'Ci'];

    new Author(name: $payload['name']);
})->throws(InvalidArgumentException::class);

it('should be throw an exception if name received is greater than 255 characters', function () {
    $payload = ['name' => random_bytes(256)];

    new Author(name: $payload['name']);
})->throws(InvalidArgumentException::class);

test('should be able to create a new author', function () {
    $payload = ['name' => 'Author name'];

    $author = new Author(name: $payload['name']);

    expect($author->id)->not->toBeEmpty()
        ->and($author->name)->toBe($payload['name']);
});

test('should be able to create a new author sending an id', function () {
    $payload = ['id' => RamseyUuid::uuid4()->toString(), 'name' => 'Author name'];

    $author = new Author(name: $payload['name'], id: new Uuid($payload['id']));

    expect($author)->toMatchObject([
        'id' => $payload['id'],
        'name' => $payload['name'],
    ]);
});

it('should be throw an exception if name received in change name method dont has at least 3 characters', function () {
    $payload = ['name' => 'Ci'];

    $author = new Author(name: 'Author name');
    $author->changeName(name: $payload['name']);
})->throws(InvalidArgumentException::class);

it('should be throw an exception if name received in change name is greater than 255 characters', function () {
    $payload = ['name' => 'Ci'];

    $author = new Author(name: 'Author name');
    $author->changeName(name: $payload['name']);
})->throws(InvalidArgumentException::class);

test('should be able to change author name', function () {
    $payload = ['name' => 'Author name updated'];

    $author = new Author(name: 'Author name');
    $author->changeName(name: $payload['name']);

    expect($author->name)->toBe($payload['name']);
});
