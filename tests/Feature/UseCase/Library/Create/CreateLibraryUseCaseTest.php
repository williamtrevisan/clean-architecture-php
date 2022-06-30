<?php

use App\Models\Library as LibraryModel;
use App\Repositories\Eloquent\LibraryEloquentRepository;
use Core\UseCase\Library\Create\CreateLibraryInputDTO;
use Core\UseCase\Library\Create\CreateLibraryUseCase;

test('should be able to create a new library', function () {
    $libraryModel = new LibraryModel();
    $libraryRepository = new LibraryEloquentRepository($libraryModel);
    $createLibraryInputDTO = new CreateLibraryInputDTO(
        name: 'Library name',
        email: 'library@email.com'
    );

    $createLibraryUseCase = new CreateLibraryUseCase($libraryRepository);
    $persistLibrary = $createLibraryUseCase->execute($createLibraryInputDTO);

    $this->assertDatabaseHas('libraries', [
        'name' => 'Library name',
        'email' => 'library@email.com'
    ]);
    expect($persistLibrary->id)->not->toBeEmpty()
        ->and($persistLibrary)->toMatchObject([
            'name' => 'Library name',
            'email' => 'library@email.com',
        ]);
});

