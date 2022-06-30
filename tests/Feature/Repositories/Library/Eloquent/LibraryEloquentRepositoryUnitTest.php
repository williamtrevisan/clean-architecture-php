<?php

use App\Models\Library as LibraryModel;
use App\Repositories\Library\Eloquent\LibraryEloquentRepository;
use Core\Domain\Library\Entity\Library as LibraryEntity;

beforeEach(function () {
    $libraryModel = new LibraryModel();
    $this->libraryRepository = new LibraryEloquentRepository($libraryModel);
});

test('should be able to create a new library', function () {
    $expectedLibrary = new LibraryEntity(
        name: 'Library name', email: 'library@email.com'
    );

    $actualLibrary = $this->libraryRepository->create($expectedLibrary);

    expect($actualLibrary)->toMatchObject([
        'id' => $expectedLibrary->getId(),
        'name' => $expectedLibrary->name,
        'email' => $expectedLibrary->email,
    ]);
});

test('should be return null if cannot find a library by primary key', function () {
    $actualLibrary = $this->libraryRepository->findByPk('libraryId');

    expect($actualLibrary)->toBeNull();
});

test('should be able to find a library by primary key', function () {
    $expectedLibrary = LibraryModel::factory()->create();

    $actualLibrary = $this->libraryRepository->findByPk($expectedLibrary->id);

    expect($actualLibrary)->toMatchObject([
        'id' => $expectedLibrary->id,
        'name' => $expectedLibrary->name,
        'email' => $expectedLibrary->email,
    ]);
});

test('should be able to find all libraries created', function () {
    $expectedLibraries = LibraryModel::factory(3)->create();

    $actualLibraries = $this->libraryRepository->findAll();

    expect($actualLibraries)->toBeArray()
        ->toHaveCount(3)
        ->toMatchArray([
            [
                'id' => $expectedLibraries[0]->id,
                'name' => $expectedLibraries[0]->name,
                'email' => $expectedLibraries[0]->email,
            ],
            [
                'id' => $expectedLibraries[1]->id,
                'name' => $expectedLibraries[1]->name,
                'email' => $expectedLibraries[1]->email,
            ],
            [
                'id' => $expectedLibraries[2]->id,
                'name' => $expectedLibraries[2]->name,
                'email' => $expectedLibraries[2]->email,
            ],
        ]);
});
