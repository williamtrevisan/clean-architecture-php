<?php

use App\Models\Author as AuthorModel;
use App\Repositories\Author\Eloquent\AuthorEloquentRepository;
use Core\Domain\Author\Entity\Author as AuthorEntity;

beforeEach(function () {
    $authorModel = new AuthorModel();
    $this->authorRepository = new AuthorEloquentRepository($authorModel);
});

test('should be able to create a new author', function () {
    $expectedAuthor = new AuthorEntity(name: 'Author name');

    $actualAuthor = $this->authorRepository->create($expectedAuthor);

    expect($actualAuthor)->toMatchObject([
        'id' => $expectedAuthor->getId(),
        'name' => $expectedAuthor->name,
    ]);
});

test('should be return null if cannot find a author by primary key', function () {
    $actualAuthor = $this->authorRepository->findByPk('authorId');

    expect($actualAuthor)->toBeNull();
});

test('should be able to find a author by primary key', function () {
    $expectedAuthor = AuthorModel::factory()->create();

    $actualAuthor = $this->authorRepository->findByPk($expectedAuthor->id);

    expect($actualAuthor)->toMatchObject([
        'id' => $expectedAuthor->id,
        'name' => $expectedAuthor->name,
    ]);
});

test('should be able to find all authors created', function () {
    AuthorModel::factory(3)->create();

    $actualAuthors = $this->authorRepository->findAll();

    expect($actualAuthors)->toBeArray()
        ->toHaveCount(3);
});
