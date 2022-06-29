<?php

declare(strict_types=1);

use Core\Domain\Citizen\Entity\Citizen;

it('should be throw an exception if name received dont has at least 3 characters', function () {
    $payload = [
        'id' => 'citizenId',
        'name' => 'Ci',
        'email' => 'email@citizen.com',
    ];

    new Citizen(
        id: $payload['id'],
        name: $payload['name'],
        email: $payload['email'],
    );
})->throws(InvalidArgumentException::class, 'The name must be at least 3 characters');

it('should be throw an exception if name received is greater than 255 characters', function () {
    $payload = [
        'id' => 'citizenId',
        'name' => random_bytes(256),
        'email' => 'email@citizen.com',
    ];

    new Citizen(
        id: $payload['id'],
        name: $payload['name'],
        email: $payload['email'],
    );
})->throws(InvalidArgumentException::class, 'The name must not be greater than 255 characters');

it('should be throw an exception if email received is invalid', function () {
    $payload = [
        'id' => 'citizenId',
        'name' => 'Citizen name',
        'email' => 'email.com',
    ];

    new Citizen(
        id: $payload['id'],
        name: $payload['name'],
        email: $payload['email'],
    );
})->throws(InvalidArgumentException::class, 'The email must be valid');

it('should be able to create a new citizen', function () {
    $payload = [
        'id' => 'citizenId',
        'name' => 'Citizen name',
        'email' => 'email@citizen.com',
    ];

    $citizen = new Citizen(
        id: $payload['id'],
        name: $payload['name'],
        email: $payload['email'],
    );

    expect($citizen)->toMatchObject([
        'id' => $payload['id'],
        'name' => $payload['name'],
        'email' => $payload['email'],
    ]);
});

it('should be throw an exception if name received in update method dont has at least 3 characters', function () {
    $payload = ['name' => 'Ci'];

    $citizen = new Citizen(
        id: 'citizenId',
        name: 'Citizen name',
        email: 'email@citizen.com',
    );
    $citizen->update(name: $payload['name']);
})->throws(InvalidArgumentException::class, 'The name must be at least 3 characters');

it('should be throw an exception if name received in update is greater than 255 characters', function () {
    $payload = ['name' => random_bytes(256)];

    $citizen = new Citizen(
        id: 'citizenId',
        name: 'Citizen name',
        email: 'email@citizen.com',
    );
    $citizen->update(name: $payload['name']);
})->throws(InvalidArgumentException::class, 'The name must not be greater than 255 characters');

it('should be throw an exception if email received in update is invalid', function () {
    $payload = ['email' => 'email.com'];

    $citizen = new Citizen(
        id: 'citizenId',
        name: 'Citizen name',
        email: 'email@citizen.com',
    );
    $citizen->update(email: $payload['email']);
})->throws(InvalidArgumentException::class, 'The email must be valid');

it('should be able to change citizen name', function () {
    $payload = ['name' => 'Citizen name updated'];

    $citizen = new Citizen(
        id: 'citizenId',
        name: 'Citizen name',
        email: 'email@citizen.com',
    );
    $citizen->update(name: $payload['name']);

    expect($citizen->name)->toBe($payload['name']);
});

it('should be able to change citizen email', function () {
    $payload = ['email' => 'citizen@email.com'];

    $citizen = new Citizen(
        id: 'citizenId',
        name: 'Citizen name',
        email: 'email@citizen.com',
    );
    $citizen->update(email: $payload['email']);

    expect($citizen->email)->toBe($payload['email']);
});

it('should be able to change citizen name and email', function () {
    $payload = ['name' => 'Citizen name updated', 'email' => 'citizen@email.com'];

    $citizen = new Citizen(
        id: 'citizenId',
        name: 'Citizen name',
        email: 'email@citizen.com',
    );
    $citizen->update(name: $payload['name'], email: $payload['email']);

    expect($citizen)->toMatchObject([
        'name' => $payload['name'],
        'email' => $payload['email'],
    ]);
});
