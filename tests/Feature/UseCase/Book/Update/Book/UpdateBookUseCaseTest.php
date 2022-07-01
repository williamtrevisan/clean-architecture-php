<?php

use App\Models\Book as BookModel;
use App\Repositories\Book\Eloquent\BookEloquentRepository;
use Core\Domain\shared\Exception\NotFoundException;
use Core\UseCase\Book\Update\UpdateBookInputDTO;
use Core\UseCase\Book\Update\UpdateBookUseCase;

beforeEach(function () {
    $bookModel = new BookModel();
    $bookRepository = new BookEloquentRepository($bookModel);
    $this->updateBookUseCase = new UpdateBookUseCase($bookRepository);
});

it('should be throw an expection if cannot find a book for update', function () {
    $updateBookInputDTO = new UpdateBookInputDTO(id: 'bookId');

    $this->updateBookUseCase->execute($updateBookInputDTO);
})->throws(NotFoundException::class, 'Book with id: bookId not found');

//test('should be able to update a book', function (string $name = '', string $email = '') {
//    $expectedBook = BookModel::factory()->create();
//    $updateBookInputDTO = new UpdateBookInputDTO(
//        id: $expectedBook->id, name: $name, email: $email
//    );
//
//    $actualBook = $this->updateBookUseCase->execute($updateBookInputDTO);
//
//    expect($actualBook)->toMatchObject([
//        'id' => $expectedBook->id,
//        'name' => $name ?: $expectedBook->name,
//        'email' => $email ?: $expectedBook->email,
//    ]);
//})->with([
//    'sending only name' => ['name' => 'Book name updated', 'email' => ''],
//    'sending only email' => ['name' => '', 'email' => 'email.updated@book.com'],
//    'sending all data' => ['name' => 'Book name updated', 'email' => 'email.updated@book.com'],
//]);
