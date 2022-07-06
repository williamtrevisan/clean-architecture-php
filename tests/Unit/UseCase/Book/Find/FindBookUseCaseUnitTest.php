<?php

use Core\Domain\Book\Entity\Book;
use Core\Domain\Book\Repository\BookRepositoryInterface;
use Core\Domain\shared\Exception\NotFoundException;
use Core\Domain\shared\ValueObject\Uuid;
use Core\UseCase\Book\Find\FindBookInputDTO;
use Core\UseCase\Book\Find\FindBookOutputDTO;
use Core\UseCase\Book\Find\FindBookUseCase;
use Ramsey\Uuid\Uuid as RamseyUuid;

beforeAll(function () {
    Mockery::close();
});

it('should be throw an exception if cannot find a book by primary key', function () {
    $bookRepository = Mockery::mock(
        stdClass::class, BookRepositoryInterface::class
    );
    $bookRepository->shouldReceive('findByPk')->andReturn(null);
    $findBookInputDTO = Mockery::mock(FindBookInputDTO::class, ['fakeId']);

    $findBookUseCase = new FindBookUseCase($bookRepository);
    $findBookUseCase->execute($findBookInputDTO);
})->throws(NotFoundException::class, 'Book with id: fakeId not found');

test('should be able to find a book by primary key', function () {
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
    $bookRepository->shouldReceive('findByPk')->andReturn($expectedBook);
    $findBookInputDTO = Mockery::mock(
        FindBookInputDTO::class, [$expectedBookId]
    );

    $findBookUseCase = new FindBookUseCase($bookRepository);
    $persistBook = $findBookUseCase->execute($findBookInputDTO);

    expect($persistBook)
        ->toBeInstanceOf(FindBookOutputDTO::class)
        ->toMatchObject([
            'id' => $expectedBook->getId(),
            'library_id' => $expectedBook->getLibraryId(),
            'title' => $expectedBook->title,
            'number_of_pages' => $expectedBook->numberOfPages,
            'year_launched' => $expectedBook->yearLaunched,
        ]);
});
