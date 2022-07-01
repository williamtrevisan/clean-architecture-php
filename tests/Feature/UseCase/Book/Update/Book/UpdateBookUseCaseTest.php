<?php

use App\Models\Book as BookModel;
use App\Models\Library as LibraryModel;
use App\Repositories\Book\Eloquent\BookEloquentRepository;
use Core\Domain\shared\Exception\NotFoundException;
use Core\UseCase\Book\Update\Book\UpdateBookInputDTO;
use Core\UseCase\Book\Update\Book\UpdateBookUseCase;

beforeEach(function () {
    $bookModel = new BookModel();
    $bookRepository = new BookEloquentRepository($bookModel);
    $this->updateBookUseCase = new UpdateBookUseCase($bookRepository);
});

it('should be throw an expection if cannot find a book for update', function () {
    $updateBookInputDTO = new UpdateBookInputDTO(id: 'bookId');

    $this->updateBookUseCase->execute($updateBookInputDTO);
})->throws(NotFoundException::class, 'Book with id: bookId not found');

test('should be able to update a book', function (
    string $libraryId = '',
    string $title = '',
    ?int $numberOfPages = null,
    ?int $yearLaunched = null
) {
    $expectedBook = BookModel::factory()->create();
    $updateBookInputDTO = new UpdateBookInputDTO(
        id: $expectedBook->id,
        libraryId: $libraryId,
        title: $title,
        numberOfPages: $numberOfPages,
        yearLaunched: $yearLaunched,
    );

    $actualBook = $this->updateBookUseCase->execute($updateBookInputDTO);

    expect($actualBook)->toMatchObject([
        'id' => $expectedBook->id,
        'library_id' => $libraryId ?: $expectedBook->library_id,
        'title' => $title ?: $expectedBook->title,
        'number_of_pages' => $numberOfPages ?? $expectedBook->number_of_pages,
        'year_launched' => $yearLaunched ?? $expectedBook->year_launched,
    ]);
})->with([
    'sending only library id' => [
        'libraryId' => fn() => LibraryModel::factory()->create()->id,
        'title' => '',
        'numberOfPages' => null,
        'yearLaunched' => null,
    ],
    'sending only title' => [
        'libraryId' => '',
        'title' => 'Book title updated',
        'numberOfPages' => null,
        'yearLaunched' => null,
    ],
    'sending only number of pages' => [
        'libraryId' => '',
        'title' => '',
        'numberOfPages' => 102,
        'yearLaunched' => null,
    ],
    'sending only year launched' => [
        'libraryId' => '',
        'title' => '',
        'numberOfPages' => null,
        'yearLaunched' => 2022,
    ],
    'sending all data' => [
        'libraryId' => fn() => LibraryModel::factory()->create()->id,
        'title' => 'Book title updated',
        'numberOfPages' => 102,
        'yearLaunched' => 2022,
    ],
]);
