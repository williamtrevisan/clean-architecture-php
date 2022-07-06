<?php

declare(strict_types=1);

namespace Core\UseCase\Book\Update;

use Core\Domain\Author\Repository\AuthorRepositoryInterface;
use Core\Domain\Book\Repository\BookRepositoryInterface;
use Core\Domain\Library\Repository\LibraryRepositoryInterface;
use Core\Domain\shared\Exception\NotFoundException;
use Core\UseCase\Interface\DatabaseTransactionInterface;
use Exception;

class UpdateBookUseCase
{
    public function __construct(
        protected readonly BookRepositoryInterface $bookRepository,
        protected readonly LibraryRepositoryInterface $libraryRepository,
        protected readonly AuthorRepositoryInterface $authorRepository,
        protected readonly DatabaseTransactionInterface $databaseTransaction
    ) {
    }

    public function execute(UpdateBookInputDTO $input): UpdateBookOutputDTO
    {
        $book = $this->bookRepository->findByPk($input->id);
        if (! $book) {
            throw new NotFoundException("Book with id: $input->id not found");
        }

        try {
            if ($input->libraryId) {
                $this->validateLibraryId($input->libraryId);
            }
            if ($input->authorsId) {
                $this->validateAuthorsId($input->authorsId);
            }

            $book->update(
                libraryId: $input->libraryId,
                title: $input->title,
                numberOfPages: $input->numberOfPages,
                yearLaunched: $input->yearLaunched,
            );
            foreach ($input->authorsId as $authorId) {
                $book->addAuthor($authorId);
            }

            $persistBook = $this->bookRepository->update($book);

            $this->databaseTransaction->commit();

            return new UpdateBookOutputDTO(
                id: $persistBook->getId(),
                library_id: $persistBook->getLibraryId(),
                title: $persistBook->title,
                number_of_pages: $persistBook->numberOfPages,
                year_launched: $persistBook->yearLaunched,
            );
        } catch (Exception $exception) {
            $this->databaseTransaction->rollBack();

            throw new NotFoundException($exception->getMessage());
        }
    }

    private function validateLibraryId(string $libraryId): void
    {
        $library = $this->libraryRepository->findByPk($libraryId);
        if (! $library) {
            throw new NotFoundException("Library with id: $libraryId not found");
        }
    }

    private function validateAuthorsId(array $authorsId): void
    {
        $persistAuthorsId = $this->authorRepository->findAuthorsIdByListId($authorsId);

        $arrayDifference = array_diff($authorsId, $persistAuthorsId);
        if ($arrayDifference) {
            $message = sprintf(
                '%s with id: %s not found',
                count($arrayDifference) > 1 ? 'Authors' : 'Author',
                implode(', ', $arrayDifference)
            );

            throw new NotFoundException($message);
        }
    }
}
