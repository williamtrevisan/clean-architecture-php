<?php

declare(strict_types=1);

namespace Core\UseCase\Book\Update\Book;

use Core\Domain\Book\Repository\BookRepositoryInterface;
use Core\Domain\shared\Exception\NotFoundException;

class UpdateBookUseCase
{
    public function __construct(protected readonly BookRepositoryInterface $bookRepository)
    {
    }

    public function execute(UpdateBookInputDTO $input): UpdateBookOutputDTO
    {
        $book = $this->bookRepository->findByPk($input->id);
        if (! $book) {
            throw new NotFoundException("Book with id: $input->id not found");
        }

        $book->update(
            libraryId: $input->libraryId,
            title: $input->title,
            numberOfPages: $input->numberOfPages,
            yearLaunched: $input->yearLaunched,
        );

        $persistBook = $this->bookRepository->update($book);

        return new UpdateBookOutputDTO(
            id: $persistBook->getId(),
            library_id: $persistBook->getLibraryId(),
            title: $persistBook->title,
            number_of_pages: $persistBook->numberOfPages,
            year_launched: $persistBook->yearLaunched,
        );
    }
}
