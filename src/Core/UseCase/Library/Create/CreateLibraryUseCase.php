<?php

namespace Core\UseCase\Library\Create;

use Core\Domain\Library\Entity\Library;
use Core\Domain\Library\Repository\LibraryRepositoryInterface;

class CreateLibraryUseCase
{
    public function __construct(private readonly LibraryRepositoryInterface $libraryRepository)
    {
    }

    public function execute(CreateLibraryInputDTO $input): CreateLibraryOutputDTO
    {
        $library = new Library(name: $input->name, email: $input->email);

        $persistLibrary = $this->libraryRepository->create($library);

        return new CreateLibraryOutputDTO(
            id: $persistLibrary->getId(),
            name: $persistLibrary->name,
            email: $persistLibrary->email,
        );
    }
}
