<?php

namespace Core\Domain\shared\ValueObject;

use InvalidArgumentException;
use Ramsey\Uuid\Uuid as RamseyUuid;

class Uuid
{
    public function __construct(protected string $id)
    {
        $this->ensureIsValid($id);
    }

    public function __toString(): string
    {
        return $this->id;
    }

    public static function create(): self
    {
        return new self(id: RamseyUuid::uuid4()->toString());
    }

    private function ensureIsValid(string $id)
    {
        $isValid = RamseyUuid::isValid($id);
        if (! $isValid) {
            throw new InvalidArgumentException(
                sprintf('<%s> does not allow the value <%s>', static::class, $id)
            );
        }
    }
}
