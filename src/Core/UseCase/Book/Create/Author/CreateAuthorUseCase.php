<?php

declare(strict_types=1);

namespace Core\UseCase\Book\Create\Author;

use Core\Domain\Book\Entity\Author;
use Core\Domain\Book\Repository\AuthorRepositoryInterface;

class CreateAuthorUseCase
{
    public function __construct(private readonly AuthorRepositoryInterface $authorRepository)
    {
    }

    public function execute(CreateAuthorInputDTO $input): CreateAuthorOutputDTO
    {
        $library = new Author(name: $input->name);

        $persistLibrary = $this->authorRepository->create($library);

        return new CreateAuthorOutputDTO(
            id: $persistLibrary->getId(),
            name: $persistLibrary->name,
        );
    }
}
