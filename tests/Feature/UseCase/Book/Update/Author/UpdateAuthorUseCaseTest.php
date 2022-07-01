<?php

use App\Models\Author as AuthorModel;
use App\Repositories\Book\Eloquent\AuthorEloquentRepository;
use Core\Domain\shared\Exception\NotFoundException;
use Core\UseCase\Book\Update\Author\UpdateAuthorInputDTO;
use Core\UseCase\Book\Update\Author\UpdateAuthorUseCase;

beforeEach(function () {
    $authorModel = new AuthorModel();
    $authorRepository = new AuthorEloquentRepository($authorModel);
    $this->updateAuthorUseCase = new UpdateAuthorUseCase($authorRepository);
});

it('should be throw an expection if cannot find a author for update', function () {
    $updateAuthorInputDTO = new UpdateAuthorInputDTO(
        id: 'authorId', name: 'Author name updated'
    );

    $this->updateAuthorUseCase->execute($updateAuthorInputDTO);
})->throws(NotFoundException::class, 'Author with id: authorId not found');

test('should be able to update a author', function () {
    $expectedAuthor = AuthorModel::factory()->create();
    $updateAuthorInputDTO = new UpdateAuthorInputDTO(
        id: $expectedAuthor->id, name: 'Author name updated'
    );

    $actualAuthor = $this->updateAuthorUseCase->execute($updateAuthorInputDTO);

    expect($actualAuthor)->toMatchObject([
        'id' => $expectedAuthor->id,
        'name' => 'Author name updated',
    ]);
});
