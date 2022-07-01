<?php

use Core\Domain\Book\Entity\Book;
use Core\Domain\Book\Repository\BookRepositoryInterface;
use Core\Domain\shared\ValueObject\Uuid;
use Core\UseCase\Book\Create\Book\CreateBookInputDTO;
use Core\UseCase\Book\Create\Book\CreateBookOutputDTO;
use Core\UseCase\Book\Create\Book\CreateBookUseCase;
use Ramsey\Uuid\Uuid as RamseyUuid;

beforeAll(function () {
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
        new Uuid($expectedBookId)
    ]);
    $expectedBook->shouldReceive('getId')->andReturn($expectedBookId);
    $expectedBook->shouldReceive('getLibraryId')->andReturn($expectedLibraryId);
    $bookRepository = Mockery::mock(
        stdClass::class, BookRepositoryInterface::class
    );
    $bookRepository->shouldReceive('create')->andReturn($expectedBook);
    $createBookInputDTO = Mockery::mock(CreateBookInputDTO::class, [
        new Uuid($expectedLibraryId), 'Book title', 205, 2000
    ]);

    $createBookUseCase = new CreateBookUseCase($bookRepository);
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
