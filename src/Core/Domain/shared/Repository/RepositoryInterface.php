<?php

declare(strict_types=1);

namespace Core\Domain\shared\Repository;

use Core\Domain\shared\Entity\Entity;

interface RepositoryInterface
{
    public function create(Entity $entity): void;

    public function findByPk(string $id): Entity;

    public function findAll(): array;

    public function update(Entity $entity): void;
}
