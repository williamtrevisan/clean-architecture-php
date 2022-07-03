<?php

namespace App\Repositories\Book\Eloquent;

use App\Models\Book as BookModel;
use Core\Domain\Book\Entity\Book as BookEntity;
use Core\Domain\Book\Repository\BookRepositoryInterface;
use Core\Domain\shared\Entity\Entity;
use Core\Domain\shared\ValueObject\Uuid;

class BookEloquentRepository implements BookRepositoryInterface
{
    public function __construct(protected readonly BookModel $bookModel)
    {
    }

    public function create(Entity $entity): Entity
    {
        $book = $this->bookModel->create([
            'id' => $entity->getId(),
            'library_id' => $entity->getLibraryId(),
            'title' => $entity->title,
            'number_of_pages' => $entity->numberOfPages,
            'year_launched' => $entity->yearLaunched,
        ]);

        if ($entity->authorsId) {
            $book->authors()->sync($entity->authorsId);
        }

        return $this->toDomainEntity($book);
    }

    public function findByPk(string $id): ?Entity
    {
        $book = $this->bookModel->find($id);
        if (! $book) {
            return null;
        }

        return $this->toDomainEntity($book);
    }

    public function findAll(): array
    {
        $books = $this->bookModel->all();

        return $books
            ->map(fn ($book) => $this->toDomainEntity($book))
            ->toArray();
    }

    public function update(Entity $entity): Entity
    {
        $book = $this->bookModel->find($entity->getId());

        $book->update([
            'library_id' => $entity->getLibraryId(),
            'title' => $entity->title,
            'number_of_pages' => $entity->numberOfPages,
            'year_launched' => $entity->yearLaunched,
        ]);
        if ($entity->authorsId) {
            $book->authors()->sync($entity->authorsId);
        }
        $book->refresh();

        return $this->toDomainEntity($book);
    }

    private function toDomainEntity(BookModel $bookModel): Entity
    {
        return new BookEntity(
            libraryId: new Uuid($bookModel->library_id),
            title: $bookModel->title,
            numberOfPages: $bookModel->number_of_pages,
            yearLaunched: $bookModel->year_launched,
            id: new Uuid($bookModel->id),
        );
    }
}
