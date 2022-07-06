<?php

use Core\Domain\Author\Repository\AuthorRepositoryInterface;
use Core\Domain\Book\Entity\Book as BookEntity;
use Core\Domain\Book\Repository\BookRepositoryInterface;
use Core\Domain\Library\Entity\Library as LibraryEntity;
use Core\Domain\Library\Repository\LibraryRepositoryInterface;
use Core\Domain\shared\Exception\NotFoundException;
use Core\Domain\shared\ValueObject\Uuid;
use Core\UseCase\Book\Update\UpdateBookInputDTO;
use Core\UseCase\Book\Update\UpdateBookOutputDTO;
use Core\UseCase\Book\Update\UpdateBookUseCase;
use Core\UseCase\Interface\DatabaseTransactionInterface;
use Ramsey\Uuid\Uuid as RamseyUuid;

it('should be throw an exception if cannot find a book for update', function () {
    $bookRepository = Mockery::mock(
        stdClass::class, BookRepositoryInterface::class
    );
    $bookRepository->shouldReceive('findByPk')->andReturn(null);
    $libraryRepository = Mockery::mock(
        stdClass::class, LibraryRepositoryInterface::class
    );
    $authorRepository = Mockery::mock(
        stdClass::class, AuthorRepositoryInterface::class
    );
    $databaseTransaction = Mockery::mock(
        stdClass::class, DatabaseTransactionInterface::class
    );
    $updateBookInputDTO = Mockery::mock(UpdateBookInputDTO::class, ['bookId']);

    $updateBookUseCase = new UpdateBookUseCase(
        $bookRepository, $libraryRepository, $authorRepository, $databaseTransaction
    );
    $updateBookUseCase->execute($updateBookInputDTO);
})->throws(NotFoundException::class, 'Book with id: bookId not found');

it('should be throw an exception if cannot find an library id', function () {
    $expectedBookId = RamseyUuid::uuid4()->toString();
    $expectedLibraryId = RamseyUuid::uuid4()->toString();
    $expectedBook = Mockery::mock(BookEntity::class, [
        new Uuid($expectedLibraryId),
        'Book title',
        205,
        2000,
        new Uuid($expectedBookId),
    ]);
    $bookRepository = Mockery::mock(
        stdClass::class, BookRepositoryInterface::class
    );
    $bookRepository->shouldReceive('findByPk')->andReturn($expectedBook);
    $libraryRepository = Mockery::mock(
        stdClass::class, LibraryRepositoryInterface::class
    );
    $libraryRepository->shouldReceive('findByPk')->andReturn(null);
    $authorRepository = Mockery::mock(
        stdClass::class, AuthorRepositoryInterface::class
    );
    $databaseTransaction = Mockery::mock(
        stdClass::class, DatabaseTransactionInterface::class
    );
    $databaseTransaction->shouldReceive('rollBack')->once();
    $updateBookInputDTO = Mockery::mock(UpdateBookInputDTO::class, [
        $expectedBookId, 'libraryId',
    ]);

    $updateBookUseCase = new UpdateBookUseCase(
        $bookRepository, $libraryRepository, $authorRepository, $databaseTransaction
    );
    $updateBookUseCase->execute($updateBookInputDTO);
})->throws(NotFoundException::class, 'Library with id: libraryId not found');

it('should be throw an exception if cannot find an author id', function () {
    $expectedBookId = RamseyUuid::uuid4()->toString();
    $expectedLibrary = new LibraryEntity(
        name: 'Library name',
        email: 'email@library.com',
    );
    $expectedBook = Mockery::mock(BookEntity::class, [
        new Uuid($expectedLibrary->getId()),
        'Book title',
        205,
        2000,
        new Uuid($expectedBookId),
    ]);
    $bookRepository = Mockery::mock(
        stdClass::class, BookRepositoryInterface::class
    );
    $bookRepository->shouldReceive('findByPk')->andReturn($expectedBook);
    $libraryRepository = Mockery::mock(
        stdClass::class, LibraryRepositoryInterface::class
    );
    $libraryRepository->shouldReceive('findByPk')->andReturn($expectedLibrary);
    $expectedAuthorId = RamseyUuid::uuid4()->toString();
    $authorRepository = Mockery::mock(
        stdClass::class, AuthorRepositoryInterface::class
    );
    $authorRepository->shouldReceive('findAuthorsIdByListId')
        ->andReturn([$expectedAuthorId]);
    $databaseTransaction = Mockery::mock(
        stdClass::class, DatabaseTransactionInterface::class
    );
    $databaseTransaction->shouldReceive('rollBack')->once();
    $updateBookInputDTO = Mockery::mock(UpdateBookInputDTO::class, [
        $expectedAuthorId,
        $expectedLibrary->getId(),
        'Book title',
        205,
        2000,
        [$expectedAuthorId, 'authorId'],
    ]);

    $updateBookUseCase = new UpdateBookUseCase(
        $bookRepository, $libraryRepository, $authorRepository, $databaseTransaction
    );
    $updateBookUseCase->execute($updateBookInputDTO);
})->throws(NotFoundException::class, 'Author with id: authorId not found');

it('should be throw an exception if cannot find authors id', function () {
    $expectedBookId = RamseyUuid::uuid4()->toString();
    $expectedLibrary = new LibraryEntity(
        name: 'Library name',
        email: 'email@library.com',
    );
    $expectedBook = Mockery::mock(BookEntity::class, [
        new Uuid($expectedLibrary->getId()),
        'Book title',
        205,
        2000,
        new Uuid($expectedBookId),
    ]);
    $bookRepository = Mockery::mock(
        stdClass::class, BookRepositoryInterface::class
    );
    $bookRepository->shouldReceive('findByPk')->andReturn($expectedBook);
    $libraryRepository = Mockery::mock(
        stdClass::class, LibraryRepositoryInterface::class
    );
    $libraryRepository->shouldReceive('findByPk')->andReturn($expectedLibrary);
    $expectedAuthorId = RamseyUuid::uuid4()->toString();
    $authorRepository = Mockery::mock(
        stdClass::class, AuthorRepositoryInterface::class
    );
    $authorRepository->shouldReceive('findAuthorsIdByListId')
        ->andReturn([$expectedAuthorId]);
    $databaseTransaction = Mockery::mock(
        stdClass::class, DatabaseTransactionInterface::class
    );
    $databaseTransaction->shouldReceive('rollBack')->once();
    $updateBookInputDTO = Mockery::mock(UpdateBookInputDTO::class, [
        $expectedAuthorId,
        $expectedLibrary->getId(),
        'Book title',
        205,
        2000,
        [$expectedAuthorId, 'authorId1', 'authorId2'],
    ]);

    $updateBookUseCase = new UpdateBookUseCase(
        $bookRepository, $libraryRepository, $authorRepository, $databaseTransaction
    );
    $updateBookUseCase->execute($updateBookInputDTO);
})->throws(NotFoundException::class, 'Authors with id: authorId1, authorId2 not found');

test('should be able to update a book', function () {
    $expectedBookId = RamseyUuid::uuid4()->toString();
    $expectedLibrary = new LibraryEntity(
        name: 'Library name',
        email: 'email@library.com',
    );
    $expectedBook = Mockery::mock(BookEntity::class, [
        new Uuid($expectedLibrary->getId()),
        'Book title',
        205,
        2000,
        new Uuid($expectedBookId),
    ]);
    $expectedBook->shouldReceive('update')->once();
    $expectedBook->shouldReceive('getId')->andReturn($expectedBookId);
    $expectedBook->shouldReceive('getLibraryId')
        ->andReturn($expectedLibrary->getId());
    $bookRepository = Mockery::mock(
        stdClass::class, BookRepositoryInterface::class
    );
    $bookRepository->shouldReceive('update')->once()
        ->andReturn($expectedBook);
    $bookRepository->shouldReceive('findByPk')->once()
        ->with($expectedBookId)
        ->andReturn($expectedBook);
    $libraryRepository = Mockery::mock(
        stdClass::class, LibraryRepositoryInterface::class
    );
    $libraryRepository->shouldReceive('findByPk')->andReturn($expectedLibrary);
    $authorRepository = Mockery::mock(
        stdClass::class, AuthorRepositoryInterface::class
    );
    $databaseTransaction = Mockery::mock(
        stdClass::class, DatabaseTransactionInterface::class
    );
    $databaseTransaction->shouldReceive('commit')->once();
    $updateBookInputDTO = Mockery::mock(UpdateBookInputDTO::class, [
        $expectedBookId,
        new Uuid(RamseyUuid::uuid4()->toString()),
        'Book title updated',
        199,
        2021,
    ]);

    $updateBookUseCase = new UpdateBookUseCase(
        $bookRepository, $libraryRepository, $authorRepository, $databaseTransaction
    );
    $actualBook = $updateBookUseCase->execute($updateBookInputDTO);

    expect($actualBook)->toBeInstanceOf(UpdateBookOutputDTO::class);
});

test('should be able to update a book sending an author id', function () {
    $expectedBookId = RamseyUuid::uuid4()->toString();
    $expectedLibrary = new LibraryEntity(
        name: 'Library name',
        email: 'email@library.com',
    );
    $expectedBook = Mockery::mock(BookEntity::class, [
        new Uuid($expectedLibrary->getId()),
        'Book title',
        205,
        2000,
        new Uuid($expectedBookId),
    ]);
    $expectedBook->shouldReceive('update', 'addAuthor')->once();
    $expectedBook->shouldReceive('getId')->andReturn($expectedBookId);
    $expectedBook->shouldReceive('getLibraryId')
        ->andReturn($expectedLibrary->getId());
    $bookRepository = Mockery::mock(
        stdClass::class, BookRepositoryInterface::class
    );
    $bookRepository->shouldReceive('update')->once()
        ->andReturn($expectedBook);
    $bookRepository->shouldReceive('findByPk')->once()
        ->with($expectedBookId)
        ->andReturn($expectedBook);
    $libraryRepository = Mockery::mock(
        stdClass::class, LibraryRepositoryInterface::class
    );
    $libraryRepository->shouldReceive('findByPk')->andReturn($expectedLibrary);
    $expectedAuthorId = RamseyUuid::uuid4()->toString();
    $authorRepository = Mockery::mock(
        stdClass::class, AuthorRepositoryInterface::class
    );
    $authorRepository->shouldReceive('findAuthorsIdByListId')
        ->andReturn([$expectedAuthorId]);
    $databaseTransaction = Mockery::mock(
        stdClass::class, DatabaseTransactionInterface::class
    );
    $databaseTransaction->shouldReceive('commit')->once();
    $updateBookInputDTO = Mockery::mock(UpdateBookInputDTO::class, [
        $expectedBookId,
        new Uuid(RamseyUuid::uuid4()->toString()),
        'Book title updated',
        199,
        2021,
        [$expectedAuthorId],
    ]);

    $updateBookUseCase = new UpdateBookUseCase(
        $bookRepository, $libraryRepository, $authorRepository, $databaseTransaction
    );
    $actualBook = $updateBookUseCase->execute($updateBookInputDTO);

    expect($actualBook)->toBeInstanceOf(UpdateBookOutputDTO::class);
});
