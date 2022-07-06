<?php

namespace App\Repositories\Author\Eloquent;

use App\Models\Author as AuthorModel;
use Core\Domain\Author\Entity\Author as AuthorEntity;
use Core\Domain\Author\Repository\AuthorRepositoryInterface;
use Core\Domain\shared\Entity\Entity;
use Core\Domain\shared\ValueObject\Uuid;

class AuthorEloquentRepository implements AuthorRepositoryInterface
{
    public function __construct(protected readonly AuthorModel $authorModel)
    {
    }

    public function create(Entity $entity): Entity
    {
        $author = $this->authorModel->create([
            'id' => $entity->getId(), 'name' => $entity->name,
        ]);

        return $this->toDomainEntity($author);
    }

    public function findAuthorsIdByListId(array $authorsId = []): array
    {
        return $this->authorModel->whereIn('id', $authorsId)->pluck('id')->toArray();
    }

    public function findByPk(string $id): ?Entity
    {
        $author = $this->authorModel->find($id);
        if (! $author) {
            return null;
        }

        return $this->toDomainEntity($author);
    }

    public function findAll(): array
    {
        $authors = $this->authorModel->all();

        return $authors
            ->map(fn ($author) => $this->toDomainEntity($author))
            ->toArray();
    }

    public function update(Entity $entity): Entity
    {
        $author = $this->authorModel->find($entity->getId());

        $author->update(['name' => $entity->name]);
        $author->refresh();

        return $this->toDomainEntity($author);
    }

    private function toDomainEntity(AuthorModel $authorModel): Entity
    {
        return new AuthorEntity(
            name: $authorModel->name,
            id: new Uuid($authorModel->id),
        );
    }
}
