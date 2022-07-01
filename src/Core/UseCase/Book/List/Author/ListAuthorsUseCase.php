<?php

declare(strict_types=1);

namespace Core\UseCase\Book\List\Author;

use Core\Domain\Book\Repository\AuthorRepositoryInterface;

class ListAuthorsUseCase
{
    public function __construct(protected readonly AuthorRepositoryInterface $authorRepository)
    {
    }

    public function execute(): ListAuthorsOutputDTO
    {
        $authors = $this->authorRepository->findAll();

        return new ListAuthorsOutputDTO(items: $authors);
    }
}
