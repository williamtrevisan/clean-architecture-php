<?php

use Core\Domain\Book\Entity\Book as BookEntity;
use Core\Domain\Book\Repository\BookRepositoryInterface;
use Core\Domain\shared\Exception\NotFoundException;
use Core\Domain\shared\ValueObject\Uuid;
use Core\UseCase\Book\Update\Book\UpdateBookInputDTO;
use Core\UseCase\Book\Update\Book\UpdateBookOutputDTO;
use Core\UseCase\Book\Update\Book\UpdateBookUseCase;
use Ramsey\Uuid\Uuid as RamseyUuid;

it('should be throw an exception if cannot find a book for update', function () {
    $bookRepository = Mockery::mock(
        stdClass::class, BookRepositoryInterface::class
    );
    $bookRepository->shouldReceive('findByPk')->andReturn(null);
    $updateBookInputDTO = Mockery::mock(UpdateBookInputDTO::class, ['bookId']);

    $updateBookUseCase = new UpdateBookUseCase($bookRepository);
    $updateBookUseCase->execute($updateBookInputDTO);
})->throws(NotFoundException::class, 'Book with id: bookId not found');

test('should be able to update a book', function () {
    $expectedBookId = RamseyUuid::uuid4()->toString();
    $expectedLibraryId = RamseyUuid::uuid4()->toString();
    $expectedBook = Mockery::mock(BookEntity::class, [
        new Uuid($expectedLibraryId),
        'Book title',
        205,
        2000,
        new Uuid($expectedBookId)
    ]);
    $expectedBook->shouldReceive('update')->once();
    $expectedBook->shouldReceive('getId')->andReturn($expectedBookId);
    $expectedBook->shouldReceive('getLibraryId')->andReturn($expectedLibraryId);
    $bookRepository = Mockery::mock(
        stdClass::class, BookRepositoryInterface::class
    );
    $bookRepository->shouldReceive('update')->once()
        ->andReturn($expectedBook);
    $bookRepository->shouldReceive('findByPk')->once()
        ->with($expectedBookId)
        ->andReturn($expectedBook);
    $updateBookInputDTO = Mockery::mock(UpdateBookInputDTO::class, [
        $expectedBookId,
        new Uuid(RamseyUuid::uuid4()->toString()),
        'Book title updated',
        199,
        2021,
    ]);

    $updateBookUseCase = new UpdateBookUseCase($bookRepository);
    $actualBook = $updateBookUseCase->execute($updateBookInputDTO);

    expect($actualBook)->toBeInstanceOf(UpdateBookOutputDTO::class);
});
