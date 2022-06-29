<?php

declare(strict_types=1);

namespace Core\Domain\Library\Entity;

use Core\Domain\shared\Entity\Entity;
use Core\Domain\shared\ValueObject\Uuid;
use InvalidArgumentException;

class Library extends Entity
{
    public function __construct(
        protected string $name,
        protected string $email,
        ?Uuid $id = null,
    ) {
        $this->id = $id ?? Uuid::create();

        $this->validate();
    }

    public function update(string $name = '', string $email = ''): void
    {
        $this->name = $name ?: $this->name;
        $this->email = $email ?: $this->email;

        $this->validate();
    }

    private function validate(): void
    {
        if (strlen($this->name) < 3) {
            throw new InvalidArgumentException('The name must be at least 3 characters');
        }

        if (strlen($this->name) > 255) {
            throw new InvalidArgumentException(
                'The name must not be greater than 255 characters'
            );
        }

        if (! str_contains($this->email, '@')) {
            throw new InvalidArgumentException('The email must be valid');
        }
    }
}
