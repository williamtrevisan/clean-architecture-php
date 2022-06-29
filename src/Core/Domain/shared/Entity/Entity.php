<?php

declare(strict_types=1);

namespace Core\Domain\shared\Entity;

use Core\Domain\shared\ValueObject\Uuid;

abstract class Entity
{
    public function __construct(protected ?Uuid $id)
    {
        $this->id = $id ?? Uuid::create();
    }

    public function __get(string $property)
    {
        return $this->{$property};
    }
}
