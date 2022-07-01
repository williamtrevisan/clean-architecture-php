<?php

use App\Models\Book as BookModel;
use App\Models\Library as LibraryModel;
use App\Repositories\Book\Eloquent\BookEloquentRepository;
use Core\Domain\Book\Entity\Book as BookEntity;
use Core\Domain\shared\ValueObject\Uuid;

beforeEach(function () {
    $bookModel = new BookModel();
    $this->bookRepository = new BookEloquentRepository($bookModel);
});

test('should be able to create a new book', function () {
    $library = LibraryModel::factory()->create();
    $expectedBook = new BookEntity(
        libraryId: new Uuid($library->id),
        title: 'Book title',
        numberOfPages: 102,
        yearLaunched: 2013,
    );

    $actualBook = $this->bookRepository->create($expectedBook);

    expect($actualBook)->toMatchObject([
        'id' => $expectedBook->getId(),
        'libraryId' => new Uuid($expectedBook->getLibraryId()),
        'title' => $expectedBook->title,
        'numberOfPages' => $expectedBook->numberOfPages,
        'yearLaunched' => $expectedBook->yearLaunched,
    ]);
});

test('should be return null if cannot find a book by primary key', function () {
    $actualBook = $this->bookRepository->findByPk('bookId');

    expect($actualBook)->toBeNull();
});

test('should be able to find a book by primary key', function () {
    $expectedBook = BookModel::factory()->create();

    $actualBook = $this->bookRepository->findByPk($expectedBook->id);

    expect($actualBook)->toMatchObject([
        'id' => $expectedBook->id,
        'libraryId' => $expectedBook->library_id,
        'title' => $expectedBook->title,
        'numberOfPages' => $expectedBook->number_of_pages,
        'yearLaunched' => $expectedBook->year_launched,
    ]);
});

test('should be able to find all books created', function () {
    BookModel::factory(3)->create();

    $actualBooks = $this->bookRepository->findAll();

    expect($actualBooks)->toBeArray()
        ->toHaveCount(3);
});
