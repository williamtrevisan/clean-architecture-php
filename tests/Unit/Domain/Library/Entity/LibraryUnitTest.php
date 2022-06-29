<?php

declare(strict_types=1);

use Domain\Library\Entity\Library;

it('should be throw an exception if name received dont has at least 3 characters', function () {
    $payload = [
        'id' => 'librayId',
        'name' => 'Li',
        'email' => 'email@library.com',
    ];

    new Library(
        id: $payload['id'],
        name: $payload['name'],
        email: $payload['email'],
    );
})->throws(InvalidArgumentException::class, 'The name must be at least 3 characters');

it('should be throw an exception if name received is greater than 255 characters', function () {
    $payload = [
        'id' => 'librayId',
        'name' => random_bytes(256),
        'email' => 'email@library.com',
    ];

    new Library(
        id: $payload['id'],
        name: $payload['name'],
        email: $payload['email'],
    );
})->throws(InvalidArgumentException::class, 'The name must not be greater than 255 characters');

it('should be throw an exception if email received is invalid', function () {
    $payload = [
        'id' => 'librayId',
        'name' => 'Library name',
        'email' => 'email.com',
    ];

    new Library(
        id: $payload['id'],
        name: $payload['name'],
        email: $payload['email'],
    );
})->throws(InvalidArgumentException::class, 'The email must be valid');

it('should be able to create a new library', function () {
    $payload = [
        'id' => 'librayId',
        'name' => 'Library name',
        'email' => 'email@library.com',
    ];

    $library = new Library(
        id: $payload['id'],
        name: $payload['name'],
        email: $payload['email'],
    );

    expect($library->id)->not->toBeEmpty()
        ->and($library)->toMatchObject([
            'name' => $payload['name'],
            'email' => $payload['email'],
        ]);
});

it('should be throw an exception if name received in update method dont has at least 3 characters', function () {
    $payload = ['name' => 'Li'];

    $library = new Library(
        id: 'libraryId',
        name: 'Library name',
        email: 'email@library.com',
    );
    $library->update(name: $payload['name']);
})->throws(InvalidArgumentException::class, 'The name must be at least 3 characters');

it('should be throw an exception if name received in update is greater than 255 characters', function () {
    $payload = ['name' => random_bytes(256)];

    $library = new Library(
        id: 'libraryId',
        name: 'Library name',
        email: 'email@library.com',
    );
    $library->update(name: $payload['name']);
})->throws(InvalidArgumentException::class, 'The name must not be greater than 255 characters');

it('should be throw an exception if email received in update is invalid', function () {
    $payload = ['email' => 'email.com'];

    $library = new Library(
        id: 'libraryId',
        name: 'Library name',
        email: 'email@library.com',
    );
    $library->update(email: $payload['email']);
})->throws(InvalidArgumentException::class, 'The email must be valid');

it('should be able to change library name', function () {
    $payload = ['name' => 'Library name updated'];

    $library = new Library(
        id: 'libraryId',
        name: 'Library name',
        email: 'email@library.com',
    );
    $library->update(name: $payload['name']);

    expect($library->name)->toBe($payload['name']);
});

it('should be able to change library email', function () {
    $payload = ['email' => 'library@email.com'];

    $library = new Library(
        id: 'libraryId',
        name: 'Library name',
        email: 'email@library.com',
    );
    $library->update(email: $payload['email']);

    expect($library->email)->toBe($payload['email']);
});

it('should be able to change library name and email', function () {
    $payload = ['name' => 'Library name updated', 'email' => 'library@email.com'];

    $library = new Library(
        id: 'libraryId',
        name: 'Library name',
        email: 'email@library.com',
    );
    $library->update(name: $payload['name'], email: $payload['email']);

    expect($library)->toMatchObject([
        'name' => $payload['name'],
        'email' => $payload['email'],
    ]);
});
