<?php

use App\Models\Author as AuthorModel;
use App\Repositories\Book\Eloquent\AuthorEloquentRepository;
use Core\UseCase\Book\Create\Author\CreateAuthorInputDTO;
use Core\UseCase\Book\Create\Author\CreateAuthorUseCase;

test('should be able to create a new author', function () {
    $authorModel = new AuthorModel();
    $authorRepository = new AuthorEloquentRepository($authorModel);
    $createAuthorInputDTO = new CreateAuthorInputDTO(name: 'Author name');

    $createAuthorUseCase = new CreateAuthorUseCase($authorRepository);
    $persistAuthor = $createAuthorUseCase->execute($createAuthorInputDTO);

    $this->assertDatabaseHas('authors', ['id' => $persistAuthor->id]);
    expect($persistAuthor->id)->not->toBeEmpty()
        ->and($persistAuthor)->toMatchObject([
            'name' => 'Author name',
        ]);
});
