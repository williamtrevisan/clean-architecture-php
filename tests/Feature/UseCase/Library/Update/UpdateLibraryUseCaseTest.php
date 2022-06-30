<?php

use App\Models\Library as LibraryModel;
use App\Repositories\Eloquent\LibraryEloquentRepository;
use Core\Domain\shared\Exception\NotFoundException;
use Core\UseCase\Library\Update\UpdateLibraryInputDTO;
use Core\UseCase\Library\Update\UpdateLibraryUseCase;

beforeEach(function () {
    $libraryModel = new LibraryModel();
    $libraryRepository = new LibraryEloquentRepository($libraryModel);
    $this->updateLibraryUseCase = new UpdateLibraryUseCase($libraryRepository);
});

it('should be throw an expection if cannot find a library for update', function () {
    $updateLibraryInputDTO = new UpdateLibraryInputDTO(id: 'libraryId');

    $this->updateLibraryUseCase->execute($updateLibraryInputDTO);
})->throws(NotFoundException::class, "Library with id: libraryId not found");

test('should be able to update a library', function (string $name = '', string $email = '') {
    $expectedLibrary = LibraryModel::factory()->create();
    $updateLibraryInputDTO = new UpdateLibraryInputDTO(
        id: $expectedLibrary->id, name: $name, email: $email
    );

    $actualLibrary = $this->updateLibraryUseCase->execute($updateLibraryInputDTO);

    expect($actualLibrary)->toMatchObject([
        'id' => $expectedLibrary->id,
        'name' => $name ?: $expectedLibrary->name,
        'email' => $email ?: $expectedLibrary->email,
    ]);
})->with([
    'sending only name' => ['name' => 'Library name updated', 'email' => ''],
    'sending only email' => ['name' => '', 'email' => 'email.updated@library.com'],
    'sending all data' => ['name' => 'Library name updated', 'email' => 'email.updated@library.com'],
]);
