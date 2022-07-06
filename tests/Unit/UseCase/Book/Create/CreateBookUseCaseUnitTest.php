<?php

use Core\Domain\Author\Repository\AuthorRepositoryInterface;
use Core\Domain\Book\Entity\Book;
use Core\Domain\Book\Repository\BookRepositoryInterface;
use Core\Domain\Library\Entity\Library;
use Core\Domain\Library\Repository\LibraryRepositoryInterface;
use Core\Domain\shared\Exception\NotFoundException;
use Core\Domain\shared\ValueObject\Uuid;
use Core\UseCase\Book\Create\CreateBookInputDTO;
use Core\UseCase\Book\Create\CreateBookOutputDTO;
use Core\UseCase\Book\Create\CreateBookUseCase;
use Core\UseCase\Interface\DatabaseTransactionInterface;
use Ramsey\Uuid\Uuid as RamseyUuid;

afterEach(function () {
    Mockery::close();
});

test('should be able to create a new book', function () {
    $expectedBookId = RamseyUuid::uuid4()->toString();
    $expectedLibraryId = RamseyUuid::uuid4()->toString();
    $expectedBook = Mockery::mock(Book::class, [
        new Uuid($expectedLibraryId),
        'Book title',
        205,
        2000,
        new Uuid($expectedBookId),
    ]);
    $expectedBook->shouldReceive('getId')->andReturn($expectedBookId);
    $expectedBook->shouldReceive('getLibraryId')->andReturn($expectedLibraryId);
    $bookRepository = Mockery::mock(
        stdClass::class, BookRepositoryInterface::class
    );
    $bookRepository->shouldReceive('create')->andReturn($expectedBook);
    $libraryRepository = Mockery::mock(
        stdClass::class, LibraryRepositoryInterface::class
    );
    $libraryRepository->shouldReceive('findByPk')->andReturn($expectedBook);
    $authorRepository = Mockery::mock(
        stdClass::class, AuthorRepositoryInterface::class
    );
    $authorRepository->shouldReceive('findAuthorsIdByListId')
        ->andReturn($expectedBook);
    $databaseTransaction = Mockery::mock(
        stdClass::class, DatabaseTransactionInterface::class
    );
    $databaseTransaction->shouldReceive('commit')->once();
    $createBookInputDTO = Mockery::mock(CreateBookInputDTO::class, [
        new Uuid($expectedLibraryId), 'Book title', 205, 2000,
    ]);

    $createBookUseCase = new CreateBookUseCase(
        $bookRepository, $libraryRepository, $authorRepository, $databaseTransaction
    );
    $persistBook = $createBookUseCase->execute($createBookInputDTO);

    expect($persistBook)
        ->toBeInstanceOf(CreateBookOutputDTO::class)
        ->toMatchObject([
            'id' => $expectedBook->getId(),
            'library_id' => $expectedBook->getLibraryId(),
            'title' => $expectedBook->title,
            'number_of_pages' => $expectedBook->numberOfPages,
            'year_launched' => $expectedBook->yearLaunched,
        ]);
});

it('should be throw an exception if cannot find an library id', function () {
    $bookRepository = Mockery::mock(
        stdClass::class, BookRepositoryInterface::class
    );
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
    $createBookInputDTO = Mockery::mock(CreateBookInputDTO::class, [
        'libraryId', 'Book title', 205, 2000,
    ]);

    $createBookUseCase = new CreateBookUseCase(
        $bookRepository, $libraryRepository, $authorRepository, $databaseTransaction
    );
    $createBookUseCase->execute($createBookInputDTO);
})->throws(NotFoundException::class, 'Library with id: libraryId not found');

it('should be throw an exception if cannot find an author id', function () {
    $expectedLibrary = new Library(
        name: 'Library name',
        email: 'email@library.com',
    );
    $expectedAuthorId = RamseyUuid::uuid4()->toString();
    $bookRepository = Mockery::mock(
        stdClass::class, BookRepositoryInterface::class
    );
    $libraryRepository = Mockery::mock(
        stdClass::class, LibraryRepositoryInterface::class
    );
    $libraryRepository->shouldReceive('findByPk')->andReturn($expectedLibrary);
    $authorRepository = Mockery::mock(
        stdClass::class, AuthorRepositoryInterface::class
    );
    $authorRepository->shouldReceive('findAuthorsIdByListId')
        ->andReturn([$expectedAuthorId]);
    $databaseTransaction = Mockery::mock(
        stdClass::class, DatabaseTransactionInterface::class
    );
    $databaseTransaction->shouldReceive('rollBack')->once();
    $createBookInputDTO = Mockery::mock(CreateBookInputDTO::class, [
        $expectedLibrary->getId(),
        'Book title',
        205,
        2000,
        [$expectedAuthorId, 'authorId'],
    ]);

    $createBookUseCase = new CreateBookUseCase(
        $bookRepository, $libraryRepository, $authorRepository, $databaseTransaction
    );
    $createBookUseCase->execute($createBookInputDTO);
})->throws(NotFoundException::class, 'Author with id: authorId not found');

it('should be throw an exception if cannot find authors id', function () {
    $expectedLibrary = new Library(
        name: 'Library name',
        email: 'email@library.com',
    );
    $expectedAuthorId = RamseyUuid::uuid4()->toString();
    $bookRepository = Mockery::mock(
        stdClass::class, BookRepositoryInterface::class
    );
    $libraryRepository = Mockery::mock(
        stdClass::class, LibraryRepositoryInterface::class
    );
    $libraryRepository->shouldReceive('findByPk')->andReturn($expectedLibrary);
    $authorRepository = Mockery::mock(
        stdClass::class, AuthorRepositoryInterface::class
    );
    $authorRepository->shouldReceive('findAuthorsIdByListId')
        ->andReturn([$expectedAuthorId]);
    $databaseTransaction = Mockery::mock(
        stdClass::class, DatabaseTransactionInterface::class
    );
    $databaseTransaction->shouldReceive('rollBack')->once();
    $createBookInputDTO = Mockery::mock(CreateBookInputDTO::class, [
        $expectedLibrary->getId(),
        'Book title',
        205,
        2000,
        [$expectedAuthorId, 'authorId1', 'authorId2'],
    ]);

    $createBookUseCase = new CreateBookUseCase(
        $bookRepository, $libraryRepository, $authorRepository, $databaseTransaction
    );
    $createBookUseCase->execute($createBookInputDTO);
})->throws(NotFoundException::class, 'Authors with id: authorId1, authorId2 not found');

test('should be able to create a new book sending an author id', function () {
    $expectedBookId = RamseyUuid::uuid4()->toString();
    $expectedLibrary = new Library(
        name: 'Library name',
        email: 'email@library.com',
    );
    $expectedAuthorId = RamseyUuid::uuid4()->toString();
    $expectedBook = Mockery::mock(Book::class, [
        new Uuid($expectedLibrary->getId()),
        'Book title',
        205,
        2000,
        new Uuid($expectedBookId),
    ]);
    $expectedBook->shouldReceive('getId')->andReturn($expectedBookId);
    $expectedBook->shouldReceive('getLibraryId')
        ->andReturn($expectedLibrary->getId());
    $bookRepository = Mockery::mock(
        stdClass::class, BookRepositoryInterface::class
    );
    $bookRepository->shouldReceive('create')->andReturn($expectedBook);
    $libraryRepository = Mockery::mock(
        stdClass::class, LibraryRepositoryInterface::class
    );
    $libraryRepository->shouldReceive('findByPk')->andReturn($expectedLibrary);
    $authorRepository = Mockery::mock(
        stdClass::class, AuthorRepositoryInterface::class
    );
    $authorRepository->shouldReceive('findAuthorsIdByListId')
        ->andReturn([$expectedAuthorId]);
    $databaseTransaction = Mockery::mock(
        stdClass::class, DatabaseTransactionInterface::class
    );
    $databaseTransaction->shouldReceive('commit')->once();
    $createBookInputDTO = Mockery::mock(CreateBookInputDTO::class, [
        new Uuid($expectedLibrary->getId()),
        'Book title',
        205,
        2000,
        [$expectedAuthorId],
    ]);

    $createBookUseCase = new CreateBookUseCase(
        $bookRepository, $libraryRepository, $authorRepository, $databaseTransaction
    );
    $persistBook = $createBookUseCase->execute($createBookInputDTO);

    expect($persistBook)
        ->toBeInstanceOf(CreateBookOutputDTO::class)
        ->toMatchObject([
            'id' => $expectedBook->getId(),
            'library_id' => $expectedBook->getLibraryId(),
            'title' => $expectedBook->title,
            'number_of_pages' => $expectedBook->numberOfPages,
            'year_launched' => $expectedBook->yearLaunched,
        ]);
});
