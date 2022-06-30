<?php

use Core\Domain\Library\Entity\Library as LibraryEntity;
use Core\Domain\Library\Repository\LibraryRepositoryInterface;
use Core\Domain\shared\Exception\NotFoundException;
use Core\Domain\shared\ValueObject\Uuid;
use Core\UseCase\Library\Update\UpdateLibraryInputDTO;
use Core\UseCase\Library\Update\UpdateLibraryOutputDTO;
use Core\UseCase\Library\Update\UpdateLibraryUseCase;
use Ramsey\Uuid\Uuid as RamseyUuid;

it('should be throw an exception if cannot find a library for update', function () {
    $libraryRepository = Mockery::mock(
        stdClass::class, LibraryRepositoryInterface::class
    );
    $libraryRepository->shouldReceive('findByPk')->andReturn(null);
    $updateLibraryInputDTO = Mockery::mock(UpdateLibraryInputDTO::class, [
        'libraryId', 'Library name updated', 'library.updated@email.com'
    ]);

    $updateLibraryUseCase = new UpdateLibraryUseCase($libraryRepository);
    $updateLibraryUseCase->execute($updateLibraryInputDTO);
})->throws(NotFoundException::class, "Library with id: libraryId not found");

test('should be able to update a library', function () {
    $expectedLibraryId = RamseyUuid::uuid4()->toString();
    $expectedLibrary = Mockery::mock(LibraryEntity::class, [
        'Library name', 'library@email.com', new Uuid($expectedLibraryId)
    ]);
    $expectedLibrary->shouldReceive('update')->once();
    $expectedLibrary->shouldReceive('getId')->andReturn($expectedLibraryId);
    $libraryRepository = Mockery::mock(
        stdClass::class, LibraryRepositoryInterface::class
    );
    $libraryRepository->shouldReceive('update')->once()
        ->andReturn($expectedLibrary);
    $libraryRepository->shouldReceive('findByPk')->once()
        ->with($expectedLibraryId)
        ->andReturn($expectedLibrary);
    $updateLibraryInputDTO = Mockery::mock(UpdateLibraryInputDTO::class, [
        $expectedLibraryId, 'Library name updated', 'email.updated@library.com'
    ]);

    $updateLibraryUseCase = new UpdateLibraryUseCase($libraryRepository);
    $actualLibrary = $updateLibraryUseCase->execute($updateLibraryInputDTO);

    expect($actualLibrary)->toBeInstanceOf(UpdateLibraryOutputDTO::class);
});
