<?php

use App\Models\Book as BookModel;
use App\Models\Library as LibraryModel;
use App\Repositories\Book\Eloquent\BookEloquentRepository;
use Core\Domain\shared\ValueObject\Uuid;
use Core\UseCase\Book\Create\Book\CreateBookInputDTO;
use Core\UseCase\Book\Create\Book\CreateBookUseCase;

test('should be able to create a new book', function () {
    $library = LibraryModel::factory()->create();
    $bookModel = new BookModel();
    $bookRepository = new BookEloquentRepository($bookModel);
    $createBookInputDTO = new CreateBookInputDTO(
        libraryId: new Uuid($library->id),
        title: 'Book title',
        numberOfPages: 102,
        yearLaunched: 2013,
    );

    $createBookUseCase = new CreateBookUseCase($bookRepository);
    $persistBook = $createBookUseCase->execute($createBookInputDTO);

    $this->assertDatabaseHas('books', ['id' => $persistBook->id]);
    expect($persistBook->id)->not->toBeEmpty()
        ->and($persistBook)->toMatchObject([
            'library_id' => new Uuid($library->id),
            'title' => 'Book title',
            'number_of_pages' => 102,
            'year_launched' => 2013,
        ]);
});
