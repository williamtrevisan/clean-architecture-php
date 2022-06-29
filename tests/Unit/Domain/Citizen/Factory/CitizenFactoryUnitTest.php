<?php

declare(strict_types=1);

use Domain\Citizen\Factory\CitizenFactory;

it('should be able to create a new citizen', function () {
    $payload = ['name' => 'Citizen name', 'email' => 'email@email.com'];

    $citizen = CitizenFactory::create($payload);

    expect($citizen->id)->not->toBeEmpty()
        ->and($citizen)->toMatchObject([
            'name' => $payload['name'],
            'email' => $payload['email'],
        ]);
});
