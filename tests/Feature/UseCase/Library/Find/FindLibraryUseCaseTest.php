<?php

use App\Models\Library as LibraryModel;
use App\Repositories\Eloquent\LibraryEloquentRepository;
use Core\Domain\shared\Exception\NotFoundException;
use Core\UseCase\Library\Find\FindLibraryInputDTO;
use Core\UseCase\Library\Find\FindLibraryUseCase;

beforeEach(function () {
    $libraryModel = new LibraryModel();
    $libraryRepository = new LibraryEloquentRepository($libraryModel);
    $this->findLibraryUseCase = new FindLibraryUseCase($libraryRepository);
});

it('should be throw an exception if cannot find a library by primary key', function () {
    $findLibraryInputDTO = new FindLibraryInputDTO(id: 'libraryId');

    $this->findLibraryUseCase->execute($findLibraryInputDTO);
})->throws(NotFoundException::class, 'Library with id: libraryId not found');

test('should be able to find a library by primary key', function () {
    $expectedLibrary = LibraryModel::factory()->create();
    $findLibraryInputDTO = new FindLibraryInputDTO(id: $expectedLibrary->id);

    $actualLibrary = $this->findLibraryUseCase->execute($findLibraryInputDTO);

    expect($actualLibrary)->toMatchObject([
        'id' => $actualLibrary->id,
        'name' => $actualLibrary->name,
        'email' => $actualLibrary->email,
    ]);
});
