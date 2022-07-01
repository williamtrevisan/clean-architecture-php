<?php

declare(strict_types=1);

namespace Core\Domain\Book\Entity;

use Core\Domain\shared\Entity\Entity;
use Core\Domain\shared\ValueObject\Uuid;
use InvalidArgumentException;

class Book extends Entity
{
    protected array $authorsId = [];

    public function __construct(
        protected Uuid $libraryId,
        protected string $title,
        protected ?int $numberOfPages,
        protected ?int $yearLaunched,
        ?Uuid $id = null,
    ) {
        $this->id = $id ?? Uuid::create();

        $this->validate();
    }

    public function getLibraryId(): string
    {
        return (string) $this->libraryId;
    }

    public function update(
        string $libraryId = '',
        string $title = '',
        ?int $numberOfPages = null,
        ?int $yearLaunched = null
    ): void {
        $this->libraryId = $libraryId ? new Uuid($libraryId) : $this->libraryId;
        $this->title = $title ?: $this->title;
        $this->numberOfPages = $numberOfPages ?? $this->numberOfPages;
        $this->yearLaunched = $yearLaunched ?? $this->yearLaunched;

        $this->validate();
    }

    public function addAuthor(string $authorId): void
    {
        $this->authorsId[] = $authorId;
    }

    public function removeAuthor(string $authorId): void
    {
        $authorIdKey = array_search($authorId, $this->authorsId);

        unset($this->authorsId[$authorIdKey]);
    }

    private function validate(): void
    {
        if (! $this->libraryId) {
            throw new InvalidArgumentException('The library id is required');
        }

        if (strlen($this->title) < 3) {
            throw new InvalidArgumentException('The title must be at least 3 characters');
        }

        if (strlen($this->title) > 255) {
            throw new InvalidArgumentException(
                'The title must not be greater than 255 characters'
            );
        }

        if (! $this->numberOfPages) {
            throw new InvalidArgumentException('The number of pages is required');
        }

        if (! $this->yearLaunched) {
            throw new InvalidArgumentException('The year launched is required');
        }
    }
}
