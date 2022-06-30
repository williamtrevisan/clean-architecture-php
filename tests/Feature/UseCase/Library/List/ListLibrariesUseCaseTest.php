<?php

use App\Models\Library as LibraryModel;
use App\Repositories\Eloquent\LibraryEloquentRepository;
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
    $expectedLibraries = LibraryModel::factory(2)->create();

    $actualLibraries = $this->listLibrariesUseCase->execute();

    expect($actualLibraries->items)
        ->toBeArray()
        ->toHaveCount(2)
        ->toMatchArray([
            [
                'id' => $expectedLibraries[0]->getId(),
                'name' => $expectedLibraries[0]->name,
                'email' => $expectedLibraries[0]->email,
            ],
            [
                'id' => $expectedLibraries[1]->getId(),
                'name' => $expectedLibraries[1]->name,
                'email' => $expectedLibraries[1]->email,
            ],
        ]);
});
