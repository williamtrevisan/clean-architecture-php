<?php

use App\Models\Author as AuthorModel;
use App\Models\Book as BookModel;
use App\Models\Library as LibraryModel;
use App\Repositories\Author\Eloquent\AuthorEloquentRepository;
use App\Repositories\Book\Eloquent\BookEloquentRepository;
use App\Repositories\Library\Eloquent\LibraryEloquentRepository;
use App\Repositories\Transaction\DatabaseTransaction;
use Core\Domain\shared\Exception\NotFoundException;
use Core\UseCase\Book\Update\UpdateBookInputDTO;
use Core\UseCase\Book\Update\UpdateBookUseCase;

beforeEach(function () {
    $bookRepository = new BookEloquentRepository(new BookModel());
    $libraryRepository = new LibraryEloquentRepository(new LibraryModel());
    $authorRepository = new AuthorEloquentRepository(new AuthorModel());
    $databaseTransaction = new DatabaseTransaction();
    $this->updateBookUseCase = new UpdateBookUseCase(
        $bookRepository, $libraryRepository, $authorRepository, $databaseTransaction
    );
});

it('should be throw an expection if cannot find a book for update', function () {
    $updateBookInputDTO = new UpdateBookInputDTO(id: 'bookId');

    $this->updateBookUseCase->execute($updateBookInputDTO);
})->throws(NotFoundException::class, 'Book with id: bookId not found');

it('should be throw an exception if cannot find an library id', function () {
    $book = BookModel::factory()->create();
    $updateBookInputDTO = Mockery::mock(UpdateBookInputDTO::class, [
        $book->id, 'libraryId',
    ]);

    $this->updateBookUseCase->execute($updateBookInputDTO);
})->throws(NotFoundException::class, 'Library with id: libraryId not found');

it('should be throw an exception if cannot find an author id', function () {
    $book = BookModel::factory()->create();
    $library = LibraryModel::factory()->create();
    $author = AuthorModel::factory()->create();
    $updateBookInputDTO = Mockery::mock(UpdateBookInputDTO::class, [
        $book->id,
        $library->id,
        'Book title',
        205,
        2000,
        [$author->id, 'authorId'],
    ]);

    $this->updateBookUseCase->execute($updateBookInputDTO);
})->throws(NotFoundException::class, 'Author with id: authorId not found');

it('should be throw an exception if cannot find authors id', function () {
    $book = BookModel::factory()->create();
    $library = LibraryModel::factory()->create();
    $author = AuthorModel::factory()->create();
    $updateBookInputDTO = Mockery::mock(UpdateBookInputDTO::class, [
        $book->id,
        $library->id,
        'Book title',
        205,
        2000,
        [$author->id, 'authorId1', 'authorId2'],
    ]);

    $this->updateBookUseCase->execute($updateBookInputDTO);
})->throws(NotFoundException::class, 'Authors with id: authorId1, authorId2 not found');

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
        'libraryId' => fn () => LibraryModel::factory()->create()->id,
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
    'sending an author id' => [
        'libraryId' => '',
        'title' => '',
        'numberOfPages' => null,
        'yearLaunched' => null,
    ],
    'sending more than once author id' => [
        'libraryId' => '',
        'title' => '',
        'numberOfPages' => null,
        'yearLaunched' => null,
    ],
    'sending all data' => [
        'libraryId' => fn () => LibraryModel::factory()->create()->id,
        'title' => 'Book title updated',
        'numberOfPages' => 102,
        'yearLaunched' => 2022,
    ],
]);

test('should be able to update a book sendind authors id', function () {
    $expectedBook = BookModel::factory()->create();
    $author = AuthorModel::factory()->create();
    $updateBookInputDTO = new UpdateBookInputDTO(
        id: $expectedBook->id,
        authorsId: [$author->id]
    );

    $actualBook = $this->updateBookUseCase->execute($updateBookInputDTO);

    $this->assertDatabaseHas('books_authors', [
        'book_id' => $expectedBook->id,
        'author_id' => $author->id,
    ]);
    expect($actualBook)->toMatchObject([
        'id' => $expectedBook->id,
        'library_id' => $expectedBook->library_id,
        'title' => $expectedBook->title,
        'number_of_pages' => $expectedBook->number_of_pages,
        'year_launched' => $expectedBook->year_launched,
    ]);
});
