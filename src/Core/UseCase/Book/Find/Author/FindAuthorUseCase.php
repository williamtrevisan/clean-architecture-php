<?php

declare(strict_types=1);

namespace Core\UseCase\Book\Find\Author;

use Core\Domain\Book\Repository\AuthorRepositoryInterface;
use Core\Domain\shared\Exception\NotFoundException;

class FindAuthorUseCase
{
    public function __construct(protected readonly AuthorRepositoryInterface $authorRepository)
    {
    }

    public function execute(FindAuthorInputDTO $input): FindAuthorOutputDTO
    {
        $author = $this->authorRepository->findByPk(id: $input->id);
        if (! $author) {
            throw new NotFoundException("Author with id: $input->id not found");
        }

        return new FindAuthorOutputDTO(id: $author->getId(), name: $author->name);
    }
}
