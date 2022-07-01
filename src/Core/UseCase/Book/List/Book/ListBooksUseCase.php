<?php

declare(strict_types=1);

namespace Core\UseCase\Book\List\Book;

use Core\Domain\Book\Repository\BookRepositoryInterface;

class ListBooksUseCase
{
    public function __construct(protected readonly BookRepositoryInterface $bookRepository)
    {
    }

    public function execute(): ListBooksOutputDTO
    {
        $books = $this->bookRepository->findAll();

        return new ListBooksOutputDTO(items: $books);
    }
}
