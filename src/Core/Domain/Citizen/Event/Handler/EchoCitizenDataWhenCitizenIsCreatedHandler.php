<?php

declare(strict_types=1);

namespace Core\Domain\Citizen\Event\Handler;

use Core\Domain\shared\Event\Event;
use Core\Domain\shared\Event\EventHandlerInterface;

class EchoCitizenDataWhenCitizenIsCreatedHandler implements EventHandlerInterface
{
    public function handle(Event $event): void
    {
        $data = $event->eventData;

        echo "Created citizen with id: $data->id, name: $data->name and email: $data->email";
    }
}
