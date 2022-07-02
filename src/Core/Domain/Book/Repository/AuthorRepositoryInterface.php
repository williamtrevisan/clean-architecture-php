<?php

declare(strict_types=1);

namespace Core\Domain\Book\Repository;

use Core\Domain\shared\Repository\RepositoryInterface;

interface AuthorRepositoryInterface extends RepositoryInterface
{
    public function findAuthorsIdByListId(array $authorsId = []): array;
}
