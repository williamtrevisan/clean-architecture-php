<?php

use App\Models\Book as BookModel;
use App\Repositories\Book\Eloquent\BookEloquentRepository;
use Core\Domain\shared\Exception\NotFoundException;
use Core\UseCase\Book\Find\Book\FindBookInputDTO;
use Core\UseCase\Book\Find\Book\FindBookUseCase;

beforeEach(function () {
    $bookModel = new BookModel();
    $bookRepository = new BookEloquentRepository($bookModel);
    $this->findBookUseCase = new FindBookUseCase($bookRepository);
});

it('should be throw an exception if cannot find a book by primary key', function () {
    $findBookInputDTO = new FindBookInputDTO(id: 'bookId');

    $this->findBookUseCase->execute($findBookInputDTO);
})->throws(NotFoundException::class, 'Book with id: bookId not found');

test('should be able to find a book by primary key', function () {
    $expectedBook = BookModel::factory()->create();
    $findBookInputDTO = new FindBookInputDTO(id: $expectedBook->id);

    $actualBook = $this->findBookUseCase->execute($findBookInputDTO);

    expect($actualBook)->toMatchObject([
        'id' => $expectedBook->id,
        'library_id' => $expectedBook->library_id,
        'title' => $expectedBook->title,
        'number_of_pages' => $expectedBook->number_of_pages,
        'year_launched' => $expectedBook->year_launched,
    ]);
});
