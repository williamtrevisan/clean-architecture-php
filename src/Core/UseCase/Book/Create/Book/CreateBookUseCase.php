<?php

declare(strict_types=1);

namespace Core\UseCase\Book\Create\Book;

use Core\Domain\Book\Entity\Book;
use Core\Domain\Book\Repository\BookRepositoryInterface;
use Core\Domain\shared\ValueObject\Uuid;

class CreateBookUseCase
{
    public function __construct(private readonly BookRepositoryInterface $bookRepository)
    {
    }

    public function execute(CreateBookInputDTO $input): CreateBookOutputDTO
    {
        $library = new Book(
            libraryId: new Uuid($input->libraryId),
            title: $input->title,
            numberOfPages: $input->numberOfPages,
            yearLaunched: $input->yearLaunched,
        );

        $persistLibrary = $this->bookRepository->create($library);

        return new CreateBookOutputDTO(
            id: $persistLibrary->getId(),
            library_id: $persistLibrary->getLibraryId(),
            title: $persistLibrary->title,
            number_of_pages: $persistLibrary->numberOfPages,
            year_launched: $persistLibrary->yearLaunched,
        );
    }
}
