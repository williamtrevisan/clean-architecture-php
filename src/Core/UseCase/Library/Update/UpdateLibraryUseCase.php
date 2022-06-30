<?php

namespace Core\UseCase\Library\Update;

use Core\Domain\Library\Repository\LibraryRepositoryInterface;
use Core\Domain\shared\Exception\NotFoundException;

class UpdateLibraryUseCase
{
    public function __construct(protected readonly LibraryRepositoryInterface $libraryRepository)
    {
    }

    public function execute(UpdateLibraryInputDTO $input): UpdateLibraryOutputDTO
    {
        $library = $this->libraryRepository->findByPk($input->id);
        if (! $library) {
            throw new NotFoundException("Library with id: $input->id not found");
        }

        $library->update(name: $input->name, email: $input->email);

        $persistLibrary = $this->libraryRepository->update($library);

        return new UpdateLibraryOutputDTO(
            id: $persistLibrary->getId(),
            name: $persistLibrary->name,
            email: $persistLibrary->email,
        );
    }
}
