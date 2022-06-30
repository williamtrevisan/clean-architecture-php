<?php

use App\Models\Library as LibraryModel;
use App\Repositories\Library\Eloquent\LibraryEloquentRepository;
use Core\Domain\Library\Entity\Library as LibraryEntity;
use Core\Domain\shared\ValueObject\Uuid;
use Core\UseCase\Library\List\ListLibrariesUseCase;

beforeEach(function () {
    $libraryModel = new LibraryModel();
    $libraryRepository = new LibraryEloquentRepository($libraryModel);
    $this->listLibrariesUseCase = new ListLibrariesUseCase($libraryRepository);
});

test('should be able find all libraries and get empty result', function () {
    $actualLibraries = $this->listLibrariesUseCase->execute();

    expect($actualLibraries->items)
        ->toBeArray()
        ->toBeEmpty();
});

test('should be able find all libraries created', function () {
    $libraries = LibraryModel::factory(2)->create()->toArray();
    $expectedLibraries = array_map(function($library) {
        return new LibraryEntity(
            name: $library['name'],
            email: $library['email'],
            id: new Uuid($library['id'])
        );
    }, $libraries);

    $actualLibraries = $this->listLibrariesUseCase->execute();

    expect($actualLibraries->items)
        ->toBeArray()
        ->toHaveCount(2);
});
