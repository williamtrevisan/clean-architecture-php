<?php

declare(strict_types=1);

use Core\Domain\Library\Entity\Library;
use Core\Domain\shared\ValueObject\Uuid;
use Ramsey\Uuid\Uuid as RamseyUuid;

it('should be throw an exception if name received dont has at least 3 characters', function () {
    $payload = [
        'name' => 'Li',
        'email' => 'email@library.com',
    ];

    new Library(
        name: $payload['name'],
        email: $payload['email'],
    );
})->throws(InvalidArgumentException::class, 'The name must be at least 3 characters');

it('should be throw an exception if name received is greater than 255 characters', function () {
    $payload = [
        'name' => random_bytes(256),
        'email' => 'email@library.com',
    ];

    new Library(
        name: $payload['name'],
        email: $payload['email'],
    );
})->throws(InvalidArgumentException::class, 'The name must not be greater than 255 characters');

it('should be throw an exception if email received is invalid', function () {
    $payload = [
        'name' => 'Library name',
        'email' => 'email.com',
    ];

    new Library(
        name: $payload['name'],
        email: $payload['email'],
    );
})->throws(InvalidArgumentException::class, 'The email must be valid');

test('should be able to create a new library', function () {
    $payload = [
        'name' => 'Library name',
        'email' => 'email@library.com',
    ];

    $library = new Library(
        name: $payload['name'],
        email: $payload['email'],
    );

    expect($library->id)->not->toBeEmpty()
        ->and($library)->toMatchObject([
            'name' => $payload['name'],
            'email' => $payload['email'],
        ]);
});

test('should be able to create a new library sending an id', function () {
    $payload = [
        'id' => RamseyUuid::uuid4()->toString(),
        'name' => 'Library name',
        'email' => 'email@library.com',
    ];

    $library = new Library(
        name: $payload['name'],
        email: $payload['email'],
        id: new Uuid($payload['id']),
    );

    expect($library)->toMatchObject([
        'id' => $payload['id'],
        'name' => $payload['name'],
        'email' => $payload['email'],
    ]);
});

it('should be throw an exception if name received in update method dont has at least 3 characters', function () {
    $payload = ['name' => 'Li'];

    $library = new Library(
        name: 'Library name',
        email: 'email@library.com',
    );
    $library->update(name: $payload['name']);
})->throws(InvalidArgumentException::class, 'The name must be at least 3 characters');

it('should be throw an exception if name received in update is greater than 255 characters', function () {
    $payload = ['name' => random_bytes(256)];

    $library = new Library(
        name: 'Library name',
        email: 'email@library.com',
    );
    $library->update(name: $payload['name']);
})->throws(InvalidArgumentException::class, 'The name must not be greater than 255 characters');

it('should be throw an exception if email received in update is invalid', function () {
    $payload = ['email' => 'email.com'];

    $library = new Library(
        name: 'Library name',
        email: 'email@library.com',
    );
    $library->update(email: $payload['email']);
})->throws(InvalidArgumentException::class, 'The email must be valid');

test('should be able to change library name', function () {
    $payload = ['name' => 'Library name updated'];

    $library = new Library(
        name: 'Library name',
        email: 'email@library.com',
    );
    $library->update(name: $payload['name']);

    expect($library->name)->toBe($payload['name']);
});

test('should be able to change library email', function () {
    $payload = ['email' => 'library@email.com'];

    $library = new Library(
        name: 'Library name',
        email: 'email@library.com',
    );
    $library->update(email: $payload['email']);

    expect($library->email)->toBe($payload['email']);
});

test('should be able to change library name and email', function () {
    $payload = ['name' => 'Library name updated', 'email' => 'library@email.com'];

    $library = new Library(
        name: 'Library name',
        email: 'email@library.com',
    );
    $library->update(name: $payload['name'], email: $payload['email']);

    expect($library)->toMatchObject([
        'name' => $payload['name'],
        'email' => $payload['email'],
    ]);
});
