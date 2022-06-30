<?php

namespace Core\UseCase\Library\List;

use Core\Domain\Library\Repository\LibraryRepositoryInterface;

class ListLibrariesUseCase
{
    public function __construct(protected readonly LibraryRepositoryInterface $libraryRepository)
    {
    }

    public function execute(): ListLibrariesOutputDTO
    {
        $libraries = $this->libraryRepository->findAll();

        return new ListLibrariesOutputDTO(items: $libraries);
    }
}
