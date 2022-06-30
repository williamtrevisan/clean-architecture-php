<?php

use Core\Domain\Library\Entity\Library;
use Core\Domain\Library\Repository\LibraryRepositoryInterface;
use Core\Domain\shared\Exception\NotFoundException;
use Core\Domain\shared\ValueObject\Uuid;
use Core\UseCase\Library\Find\FindLibraryInputDTO;
use Core\UseCase\Library\Find\FindLibraryOutputDTO;
use Core\UseCase\Library\Find\FindLibraryUseCase;
use Ramsey\Uuid\Uuid as RamseyUuid;

beforeAll(function () {
    Mockery::close();
});

it('should be throw an exception if cannot find a library by primary key', function () {
    $libraryRepository = Mockery::mock(
        stdClass::class, LibraryRepositoryInterface::class
    );
    $libraryRepository->shouldReceive('findByPk')->andReturn(null);
    $findLibraryInputDTO = Mockery::mock(FindLibraryInputDTO::class, ['fakeId']);

    $findLibraryUseCase = new FindLibraryUseCase($libraryRepository);
    $findLibraryUseCase->execute($findLibraryInputDTO);
})->throws(NotFoundException::class, "Library with id: fakeId not found");

test('should be able to find a library by primary key', function () {
    $expectedLibraryId = RamseyUuid::uuid4()->toString();
    $expectedLibrary = Mockery::mock(
        Library::class,
        ['Library name', 'library@email.com', new Uuid($expectedLibraryId)]
    );
    $expectedLibrary->shouldReceive('getId')->andReturn($expectedLibraryId);
    $libraryRepository = Mockery::mock(
        stdClass::class, LibraryRepositoryInterface::class
    );
    $libraryRepository->shouldReceive('findByPk')->andReturn($expectedLibrary);
    $findLibraryInputDTO = Mockery::mock(
        FindLibraryInputDTO::class, [$expectedLibraryId]
    );

    $findLibraryUseCase = new FindLibraryUseCase($libraryRepository);
    $persistLibrary = $findLibraryUseCase->execute($findLibraryInputDTO);

    expect($persistLibrary)
        ->toBeInstanceOf(FindLibraryOutputDTO::class)
        ->toMatchObject([
            'id' => $expectedLibrary->id,
            'name' => $expectedLibrary->name,
            'email' => $expectedLibrary->email,
        ]);
});
