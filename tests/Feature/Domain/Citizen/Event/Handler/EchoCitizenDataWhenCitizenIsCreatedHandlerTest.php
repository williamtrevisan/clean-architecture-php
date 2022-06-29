<?php

declare(strict_types=1);

use Domain\Citizen\Event\CitizenCreatedEvent;
use Domain\Citizen\Event\Handler\EchoCitizenDataWhenCitizenIsCreatedHandler;
use Domain\Citizen\Factory\CitizenFactory;
use Domain\shared\Event\EventDispatcher;

it('should be able to echo citizen data when citizen is created', function () {
    $eventDispatcher = new EventDispatcher();
    $eventHandler = new EchoCitizenDataWhenCitizenIsCreatedHandler();
    $eventDispatcher->register('CitizenCreatedEvent', $eventHandler);
    $citizen = CitizenFactory::create([
        'name' => 'Citizen name',
        'email' => 'email@citizen.com'
    ]);
    $citizenCreatedEvent = new CitizenCreatedEvent($citizen);

    $eventDispatcher->notify($citizenCreatedEvent);

    $this->expectOutputString(
        "Created citizen with id: $citizen->id, name: $citizen->name and email: $citizen->email"
    );
});
