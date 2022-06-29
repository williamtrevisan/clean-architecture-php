<?php

use Core\Domain\Library\Entity\Library;
use Core\Domain\Library\Repository\LibraryRepositoryInterface;
use Core\Domain\shared\ValueObject\Uuid;
use Core\UseCase\Library\Create\CreateLibraryInputDTO;
use Core\UseCase\Library\Create\CreateLibraryOutputDTO;
use Core\UseCase\Library\Create\CreateLibraryUseCase;
use Ramsey\Uuid\Uuid as RamseyUuid;

beforeAll(function () {
    Mockery::close();
});

test('should be able to create a new library', function () {
    $expectedLibraryId = RamseyUuid::uuid4()->toString();
    $expectedLibrary = Mockery::mock(
        Library::class,
        ['Library name', 'library@email.com', new Uuid($expectedLibraryId)]
    );
    $expectedLibrary->shouldReceive('getId')->andReturn($expectedLibraryId);
    $libraryRepository = Mockery::mock(
        stdClass::class, LibraryRepositoryInterface::class
    );
    $libraryRepository->shouldReceive('create')->andReturn($expectedLibrary);
    $createLibraryInputDTO = Mockery::mock(
        CreateLibraryInputDTO::class, ['Library name', 'library@email.com']
    );

    $createLibraryUseCase = new CreateLibraryUseCase($libraryRepository);
    $persistLibrary = $createLibraryUseCase->execute($createLibraryInputDTO);

    expect($persistLibrary)
        ->toBeInstanceOf(CreateLibraryOutputDTO::class)
        ->toMatchObject([
            'id' => $expectedLibrary->id,
            'name' => $expectedLibrary->name,
            'email' => $expectedLibrary->email,
        ]);
});
