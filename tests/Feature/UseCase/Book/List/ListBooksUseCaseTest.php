<?php

declare(strict_types=1);

use App\Models\Book as BookModel;
use App\Repositories\Book\Eloquent\BookEloquentRepository;
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
    BookModel::factory(2)->create()->toArray();

    $actualBooks = $this->listBooksUseCase->execute();

    expect($actualBooks->items)
        ->toBeArray()
        ->toHaveCount(2);
});
