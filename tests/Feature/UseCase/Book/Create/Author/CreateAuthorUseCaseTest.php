<?php

use App\Models\Author as AuthorModel;
use App\Repositories\Author\Eloquent\AuthorEloquentRepository;
use Core\UseCase\Author\Create\CreateAuthorInputDTO;
use Core\UseCase\Author\Create\CreateAuthorUseCase;

test('should be able to create a new author', function () {
    $authorModel = new AuthorModel();
    $authorRepository = new AuthorEloquentRepository($authorModel);
    $createAuthorInputDTO = new CreateAuthorInputDTO(name: 'Author name');

    $createAuthorUseCase = new CreateAuthorUseCase($authorRepository);
    $persistAuthor = $createAuthorUseCase->execute($createAuthorInputDTO);

    $this->assertDatabaseHas('authors', ['name' => $persistAuthor->getId()]);
    expect($persistAuthor->id)->not->toBeEmpty()
        ->and($persistAuthor)->toMatchObject([
            'name' => 'Author name'
        ]);
});
