<?php

use App\Models\Author as AuthorModel;
use App\Repositories\Author\Eloquent\AuthorEloquentRepository;
use Core\Domain\shared\Exception\NotFoundException;
use Core\UseCase\Author\Find\FindAuthorInputDTO;
use Core\UseCase\Author\Find\FindAuthorUseCase;

beforeEach(function () {
    $authorModel = new AuthorModel();
    $authorRepository = new AuthorEloquentRepository($authorModel);
    $this->findAuthorUseCase = new FindAuthorUseCase($authorRepository);
});

it('should be throw an exception if cannot find a author by primary key', function () {
    $findAuthorInputDTO = new FindAuthorInputDTO(id: 'authorId');

    $this->findAuthorUseCase->execute($findAuthorInputDTO);
})->throws(NotFoundException::class, 'Author with id: authorId not found');

test('should be able to find a author by primary key', function () {
    $expectedAuthor = AuthorModel::factory()->create();
    $findAuthorInputDTO = new FindAuthorInputDTO(id: $expectedAuthor->id);

    $actualAuthor = $this->findAuthorUseCase->execute($findAuthorInputDTO);

    expect($actualAuthor)->toMatchObject([
        'id' => $expectedAuthor->id,
        'name' => $expectedAuthor->name,
    ]);
});
