<?php

use Core\Domain\Author\Entity\Author as AuthorEntity;
use Core\Domain\Author\Repository\AuthorRepositoryInterface;
use Core\UseCase\Author\List\ListAuthorsOutputDTO;
use Core\UseCase\Author\List\ListAuthorsUseCase;

test('should be able find all authors and get empty result', function () {
    $authorRepository = Mockery::mock(stdClass::class, AuthorRepositoryInterface::class);
    $authorRepository->shouldReceive('findAll')->andReturn([]);

    $listAuthorsUseCase = new ListAuthorsUseCase($authorRepository);
    $actualAuthors = $listAuthorsUseCase->execute();

    expect($actualAuthors)->toBeInstanceOf(ListAuthorsOutputDTO::class)
        ->and($actualAuthors->items)
            ->toBeArray()
            ->toBeEmpty();
});

test('should be able find all authors created', function () {
    $author1 = new AuthorEntity(name: 'Author name 1');
    $author2 = new AuthorEntity(name: 'Author name 2');
    $authorRepository = Mockery::mock(
        stdClass::class, AuthorRepositoryInterface::class
    );
    $authorRepository->shouldReceive('findAll')->andReturn([$author1, $author2]);

    $listAuthorsUseCase = new ListAuthorsUseCase($authorRepository);
    $actualAuthors = $listAuthorsUseCase->execute();

    expect($actualAuthors)->toBeInstanceOf(ListAuthorsOutputDTO::class)
        ->and($actualAuthors->items)
            ->toBeArray()
            ->toHaveCount(2);
});
