<?php

declare(strict_types=1);

use Core\Domain\Library\Factory\LibraryFactory;

it('should be able to create a new citizen', function () {
    $payload = ['name' => 'Library name', 'email' => 'email@email.com'];

    $library = LibraryFactory::create($payload);

    expect($library->id)->not->toBeEmpty()
        ->and($library)->toMatchObject([
            'name' => $payload['name'],
            'email' => $payload['email'],
        ]);
});
