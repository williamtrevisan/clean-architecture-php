<?php

use Core\Domain\Book\Entity\book as BookEntity;
use Core\Domain\Book\Repository\BookRepositoryInterface;
use Core\Domain\shared\ValueObject\Uuid;
use Core\UseCase\Book\List\Book\ListBooksOutputDTO;
use Core\UseCase\Book\List\Book\ListBooksUseCase;
use Ramsey\Uuid\Uuid as RamseyUuid;

test('should be able find all books and get empty result', function () {
    $bookRepository = Mockery::mock(
        stdClass::class, BookRepositoryInterface::class
    );
    $bookRepository->shouldReceive('findAll')->andReturn([]);

    $listBooksUseCase = new ListBooksUseCase($bookRepository);
    $actualBooks = $listBooksUseCase->execute();

    expect($actualBooks)->toBeInstanceOf(ListBooksOutputDTO::class)
        ->and($actualBooks->items)
            ->toBeArray()
            ->toBeEmpty();
});

test('should be able find all books created', function () {
    $book1 = new BookEntity(
        libraryId: new Uuid(RamseyUuid::uuid4()->toString()),
        title: 'Book title 1',
        numberOfPages: 200,
        yearLaunched: 2000,
    );
    $book2 = new BookEntity(
        libraryId: new Uuid(RamseyUuid::uuid4()->toString()),
        title: 'Book title 2',
        numberOfPages: 200,
        yearLaunched: 2000,
    );
    $bookRepository = Mockery::mock(
        stdClass::class, BookRepositoryInterface::class
    );
    $bookRepository->shouldReceive('findAll')->andReturn([$book1, $book2]);

    $listBooksUseCase = new ListBooksUseCase($bookRepository);
    $actualBooks = $listBooksUseCase->execute();

    expect($actualBooks)->toBeInstanceOf(ListBooksOutputDTO::class)
        ->and($actualBooks->items)
            ->toBeArray()
            ->toHaveCount(2);
});
