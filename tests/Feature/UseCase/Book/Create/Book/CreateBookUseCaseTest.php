<?php

use App\Models\Author as AuthorModel;
use App\Models\Book as BookModel;
use App\Models\Library as LibraryModel;
use App\Repositories\Book\Eloquent\AuthorEloquentRepository;
use App\Repositories\Book\Eloquent\BookEloquentRepository;
use App\Repositories\Library\Eloquent\LibraryEloquentRepository;
use App\Repositories\Transaction\DatabaseTransaction;
use Core\Domain\shared\Exception\NotFoundException;
use Core\Domain\shared\ValueObject\Uuid;
use Core\UseCase\Book\Create\Book\CreateBookInputDTO;
use Core\UseCase\Book\Create\Book\CreateBookUseCase;

beforeEach(function () {
    $bookRepository = new BookEloquentRepository(new BookModel());
    $libraryRepository = new LibraryEloquentRepository(new LibraryModel());
    $authorRepository = new AuthorEloquentRepository(new AuthorModel());
    $databaseTransacton = new DatabaseTransaction();
    $this->createBookUseCase = new CreateBookUseCase(
        $bookRepository, $libraryRepository, $authorRepository, $databaseTransacton
    );
});

test('should be able to create a new book', function () {
    $library = LibraryModel::factory()->create();
    $createBookInputDTO = new CreateBookInputDTO(
        libraryId: $library->id,
        title: 'Book title',
        numberOfPages: 102,
        yearLaunched: 2013,
    );

    $persistBook = $this->createBookUseCase->execute($createBookInputDTO);

    $this->assertDatabaseHas('books', ['id' => $persistBook->id]);
    expect($persistBook->id)->not->toBeEmpty()
        ->and($persistBook)->toMatchObject([
            'library_id' => new Uuid($library->id),
            'title' => 'Book title',
            'number_of_pages' => 102,
            'year_launched' => 2013,
        ]);
});

it('should be throw an exception if cannot find a received library', function () {
    $createBookInputDTO = new CreateBookInputDTO(
        libraryId: 'libraryId',
        title: 'Book title',
        numberOfPages: 102,
        yearLaunched: 2013,
    );

    $this->createBookUseCase->execute($createBookInputDTO);
})->throws(NotFoundException::class, 'Library with id: libraryId not found');

it('should be throw an exception if cannot find a received author', function () {
    $library = LibraryModel::factory()->create();
    $createBookInputDTO = new CreateBookInputDTO(
        libraryId: $library->id,
        title: 'Book title',
        numberOfPages: 102,
        yearLaunched: 2013,
        authorsId: ['authorId']
    );

    $this->createBookUseCase->execute($createBookInputDTO);
})->throws(NotFoundException::class, 'Author with id: authorId not found');

it('should be throw an exception if cannot find a received authors', function () {
    $library = LibraryModel::factory()->create();
    $createBookInputDTO = new CreateBookInputDTO(
        libraryId: $library->id,
        title: 'Book title',
        numberOfPages: 102,
        yearLaunched: 2013,
        authorsId: ['authorId1', 'authorId2']
    );

    $this->createBookUseCase->execute($createBookInputDTO);
})->throws(NotFoundException::class, 'Authors with id: authorId1, authorId2 not found');

test('should be able to create a new book sending an author id', function () {
    $library = LibraryModel::factory()->create();
    $author = AuthorModel::factory()->create();
    $createBookInputDTO = new CreateBookInputDTO(
        libraryId: $library->id,
        title: 'Book title',
        numberOfPages: 102,
        yearLaunched: 2013,
        authorsId: [$author->id]
    );

    $persistBook = $this->createBookUseCase->execute($createBookInputDTO);

    $this->assertDatabaseHas('books', ['id' => $persistBook->id]);
    $this->assertDatabaseHas('books_authors', [
        'book_id' => $persistBook->id,
        'author_id' => $author->id,
    ]);
    expect($persistBook->id)->not->toBeEmpty()
        ->and($persistBook)->toMatchObject([
            'library_id' => new Uuid($library->id),
            'title' => 'Book title',
            'number_of_pages' => 102,
            'year_launched' => 2013,
        ]);
});
