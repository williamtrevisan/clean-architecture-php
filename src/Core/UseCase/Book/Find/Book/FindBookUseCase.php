<?php

declare(strict_types=1);

namespace Core\UseCase\Book\Find\Book;

use Core\Domain\Book\Repository\BookRepositoryInterface;
use Core\Domain\shared\Exception\NotFoundException;

class FindBookUseCase
{
    public function __construct(protected readonly BookRepositoryInterface $bookRepository)
    {
    }

    public function execute(FindBookInputDTO $input): FindBookOutputDTO
    {
        $book = $this->bookRepository->findByPk(id: $input->id);
        if (! $book) {
            throw new NotFoundException("Book with id: $input->id not found");
        }

        return new FindBookOutputDTO(
            id: $book->getId(),
            library_id: $book->getLibraryId(),
            title: $book->title,
            number_of_pages: $book->numberOfPages,
            year_launched: $book->yearLaunched,
        );
    }
}
