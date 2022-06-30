<?php

namespace Core\UseCase\Library\Find;

use Core\Domain\Library\Repository\LibraryRepositoryInterface;
use Core\Domain\shared\Exception\NotFoundException;

class FindLibraryUseCase
{
    public function __construct(protected readonly LibraryRepositoryInterface $libraryRepository)
    {
    }

    public function execute(FindLibraryInputDTO $input): FindLibraryOutputDTO
    {
        $library = $this->libraryRepository->findByPk(id: $input->id);
        if (! $library) {
            throw new NotFoundException("Library with id: $input->id not found");
        }

        return new FindLibraryOutputDTO(
            id: $library->id,
            name: $library->name,
            email: $library->email,
        );
    }
}
