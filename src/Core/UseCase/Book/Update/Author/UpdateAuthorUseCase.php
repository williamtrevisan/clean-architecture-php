<?php

declare(strict_types=1);

namespace Core\UseCase\Book\Update\Author;

use Core\Domain\Book\Repository\AuthorRepositoryInterface;
use Core\Domain\shared\Exception\NotFoundException;

class UpdateAuthorUseCase
{
    public function __construct(protected readonly AuthorRepositoryInterface $authorRepository)
    {
    }

    public function execute(UpdateAuthorInputDTO $input): UpdateAuthorOutputDTO
    {
        $author = $this->authorRepository->findByPk($input->id);
        if (! $author) {
            throw new NotFoundException("Author with id: $input->id not found");
        }

        $author->changeName(name: $input->name);

        $persistAuthor = $this->authorRepository->update($author);

        return new UpdateAuthorOutputDTO(
            id: $persistAuthor->getId(),
            name: $persistAuthor->name
        );
    }
}
