<?php

use App\Models\Author as AuthorModel;
use App\Repositories\Author\Eloquent\AuthorEloquentRepository;
use Core\Domain\Author\Entity\Author as AuthorEntity;
use Core\Domain\shared\ValueObject\Uuid;
use Core\UseCase\Author\List\ListAuthorsUseCase;

beforeEach(function () {
    $authorModel = new AuthorModel();
    $authorRepository = new AuthorEloquentRepository($authorModel);
    $this->listAuthorsUseCase = new ListAuthorsUseCase($authorRepository);
});

test('should be able find all authors and get empty result', function () {
    $actualAuthors = $this->listAuthorsUseCase->execute();

    expect($actualAuthors->items)
        ->toBeArray()
        ->toBeEmpty();
});

test('should be able find all authors created', function () {
    $authors = AuthorModel::factory(2)->create()->toArray();
    $expectedAuthors = array_map(function ($author) {
        return new AuthorEntity(
            name: $author['name'],
            id: new Uuid($author['id'])
        );
    }, $authors);

    $actualAuthors = $this->listAuthorsUseCase->execute();

    expect($actualAuthors->items)
        ->toBeArray()
        ->toHaveCount(2);
});
