<?php

declare(strict_types=1);

namespace Core\Domain\shared\Entity;

use Core\Domain\shared\ValueObject\Uuid;

abstract class Entity
{
    protected Uuid $id;

    public function __get(string $property)
    {
        return $this->{$property};
    }

    public function getId(): string
    {
        return (string) $this->id;
    }
}
