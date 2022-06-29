<?php

declare(strict_types=1);

namespace Core\Domain\Citizen\Factory;

use Core\Domain\Citizen\Entity\Citizen;
use Ramsey\Uuid\Uuid;

class CitizenFactory
{
    public static function create(array $payload): Citizen
    {
        return new Citizen(
            id: Uuid::uuid4()->toString(),
            name: $payload['name'],
            email: $payload['email'],
        );
    }
}
