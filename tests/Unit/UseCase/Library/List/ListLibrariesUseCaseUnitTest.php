<?php

use Core\Domain\Library\Entity\Library as LibraryEntity;
use Core\Domain\Library\Repository\LibraryRepositoryInterface;
use Core\UseCase\Library\List\ListLibrariesOutputDTO;
use Core\UseCase\Library\List\ListLibrariesUseCase;

test('should be able find all libraries and get empty result', function () {
    $libraryRepository = Mockery::mock(stdClass::class, LibraryRepositoryInterface::class);
    $libraryRepository->shouldReceive('findAll')->andReturn([]);

    $listLibrariesUseCase = new ListLibrariesUseCase($libraryRepository);
    $actualLibraries = $listLibrariesUseCase->execute();

    expect($actualLibraries)->toBeInstanceOf(ListLibrariesOutputDTO::class)
        ->and($actualLibraries->items)
            ->toBeArray()
            ->toBeEmpty();
});

test('should be able find all libraries created', function () {
    $library1 = new LibraryEntity(name: 'Library name 1', email: 'library@email.com');
    $library2 = new LibraryEntity(name: 'Library name 2', email: 'library@email.com');
    $libraryRepository = Mockery::mock(
        stdClass::class, LibraryRepositoryInterface::class
    );
    $libraryRepository->shouldReceive('findAll')->andReturn([$library1, $library2]);

    $listLibrariesUseCase = new ListLibrariesUseCase($libraryRepository);
    $actualLibraries = $listLibrariesUseCase->execute();

    expect($actualLibraries)->toBeInstanceOf(ListLibrariesOutputDTO::class)
        ->and($actualLibraries->items)
            ->toBeArray()
            ->toHaveCount(2)
            ->toMatchArray([
                [
                    'id' => $library1->getId(),
                    'name' => $library1->name,
                    'email' => $library1->email,
                ],
                [
                    'id' => $library2->getId(),
                    'name' => $library2->name,
                    'email' => $library2->email,
                ],
            ]);
});
