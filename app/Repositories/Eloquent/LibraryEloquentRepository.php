<?php

namespace App\Repositories\Eloquent;

use App\Models\Library as LibraryModel;
use Core\Domain\Library\Entity\Library;
use Core\Domain\Library\Repository\LibraryRepositoryInterface;
use Core\Domain\shared\Entity\Entity;
use Core\Domain\shared\ValueObject\Uuid;

class LibraryEloquentRepository implements LibraryRepositoryInterface
{
    public function __construct(protected readonly LibraryModel $libraryModel)
    {
    }

    public function create(Entity $entity): Entity
    {
        $library = $this->libraryModel->create([
            'id' => $entity->getId(),
            'name' => $entity->name,
            'email' => $entity->email,
        ]);

        return $this->toDomainEntity($library);
    }

    public function findByPk(string $id): ?Entity
    {
        $library = $this->libraryModel->find($id);
        if (! $library) return null;

        return $this->toDomainEntity($library);
    }

    public function findAll(): array
    {
        return $this->libraryModel->all()->toArray();
    }

    public function update(Entity $entity): Entity
    {
        $library = $this->libraryModel->find($entity->getId());

        $library->update(['name' => $entity->name, 'email' => $entity->email]);
        $library->refresh();

        return $this->toDomainEntity($library);
    }

    private function toDomainEntity(LibraryModel $libraryModel): Entity
    {
        return new Library(
            name: $libraryModel->name,
            email: $libraryModel->email,
            id: new Uuid($libraryModel->id),
        );
    }
}
