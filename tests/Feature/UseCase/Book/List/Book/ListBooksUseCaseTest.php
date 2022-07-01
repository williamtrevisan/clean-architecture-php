<?php

use App\Models\Book as BookModel;
use App\Repositories\Book\Eloquent\BookEloquentRepository;
use Core\Domain\Book\Entity\Book as BookEntity;
use Core\Domain\shared\ValueObject\Uuid;
use Core\UseCase\Book\List\ListBooksUseCase;

beforeEach(function () {
    $bookModel = new BookModel();
    $bookRepository = new BookEloquentRepository($bookModel);
    $this->listBooksUseCase = new ListBooksUseCase($bookRepository);
});

test('should be able find all books and get empty result', function () {
    $actualBooks = $this->listBooksUseCase->execute();

    expect($actualBooks->items)
        ->toBeArray()
        ->toBeEmpty();
});

test('should be able find all books created', function () {
    $books = BookModel::factory(2)->create()->toArray();
    $expectedBooks = array_map(function($book) {
        return new BookEntity(
            libraryId: $book['library_id'],
            title: $book['title'],
            numberOfPages: $book['number_of_pages'],
            yearLaunched: $book['year_launched'],
            id: new Uuid($book['id'])
        );
    }, $books);

    $actualBooks = $this->listBooksUseCase->execute();

    expect($actualBooks->items)
        ->toBeArray()
        ->toHaveCount(2);
});
