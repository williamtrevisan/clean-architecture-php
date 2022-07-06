<?php

declare(strict_types=1);

namespace Core\UseCase\Book\Create;

use Core\Domain\Author\Repository\AuthorRepositoryInterface;
use Core\Domain\Book\Entity\Book;
use Core\Domain\Book\Repository\BookRepositoryInterface;
use Core\Domain\Library\Repository\LibraryRepositoryInterface;
use Core\Domain\shared\Exception\NotFoundException;
use Core\Domain\shared\ValueObject\Uuid;
use Core\UseCase\Interface\DatabaseTransactionInterface;
use Exception;

class CreateBookUseCase
{
    public function __construct(
        private readonly BookRepositoryInterface $bookRepository,
        private readonly LibraryRepositoryInterface $libraryRepository,
        private readonly AuthorRepositoryInterface $authorRepository,
        private readonly DatabaseTransactionInterface $databaseTransaction
    ) {
    }

    public function execute(CreateBookInputDTO $input): CreateBookOutputDTO
    {
        try {
            $this->validateLibraryId($input->libraryId);
            if ($input->authorsId) {
                $this->validateAuthorsId($input->authorsId);
            }

            $book = new Book(
                libraryId: new Uuid($input->libraryId),
                title: $input->title,
                numberOfPages: $input->numberOfPages,
                yearLaunched: $input->yearLaunched,
            );
            foreach ($input->authorsId as $authorId) {
                $book->addAuthor($authorId);
            }

            $persistLibrary = $this->bookRepository->create($book);

            $this->databaseTransaction->commit();

            return new CreateBookOutputDTO(
                id: $persistLibrary->getId(),
                library_id: $persistLibrary->getLibraryId(),
                title: $persistLibrary->title,
                number_of_pages: $persistLibrary->numberOfPages,
                year_launched: $persistLibrary->yearLaunched,
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
