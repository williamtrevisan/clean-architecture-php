<?php

declare(strict_types=1);

use Domain\shared\Event\Event;
use Domain\shared\Event\EventDispatcher;
use Domain\shared\Event\EventHandlerInterface;

afterEach(function () {
    Mockery::close();
});

it('should be able to register an event handler', function () {
    $eventDispatcher = new EventDispatcher();
    $eventHandler = Mockery::mock(stdClass::class, EventHandlerInterface::class);

    $eventDispatcher->register('CitizenCreatedEvent', $eventHandler);

    expect($eventDispatcher->eventHandlers())
        ->toHaveKey('CitizenCreatedEvent')
        ->toHaveCount(1);
});

it('should be able to unregister an event handler', function () {
    $eventDispatcher = new EventDispatcher();
    $eventHandler = Mockery::mock(stdClass::class, EventHandlerInterface::class);
    $eventDispatcher->register('CitizenCreatedEvent', $eventHandler);

    $eventDispatcher->unregister('CitizenCreatedEvent', $eventHandler);

    expect($eventDispatcher->eventHandlers())->toHaveKey('CitizenCreatedEvent')
        ->and($eventDispatcher->eventHandlers()['CitizenCreatedEvent'])->toBeEmpty();
});

it('should be able to unregister all event handlers', function () {
    $eventDispatcher = new EventDispatcher();
    $eventHandler = Mockery::mock(stdClass::class, EventHandlerInterface::class);
    $eventDispatcher->register('CitizenCreatedEvent', $eventHandler);

    $eventDispatcher->unregisterAll();

    expect($eventDispatcher->eventHandlers())
        ->not->toHaveKey('CitizenCreatedEvent')
        ->toBeEmpty();
});

it('should be able to notify all event handlers', function () {
    $eventDispatcher = new EventDispatcher();
    $event = Mockery::namedMock('CitizenCreatedEvent', Event::class, [
        ['name' => 'Citizen name', 'email' => 'email@citizen.com']
    ]);
    $eventHandler = Mockery::mock(stdClass::class, EventHandlerInterface::class);
    $eventHandler->shouldReceive('handle')->once()->with($event);
    $eventDispatcher->register('CitizenCreatedEvent', $eventHandler);

    $eventDispatcher->notify($event);

    expect($eventHandler)->toBeInstanceOf(EventHandlerInterface::class);
});
