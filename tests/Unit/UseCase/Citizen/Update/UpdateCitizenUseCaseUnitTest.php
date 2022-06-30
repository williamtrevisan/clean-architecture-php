<?php

use Core\Domain\Citizen\Entity\Citizen as CitizenEntity;
use Core\Domain\Citizen\Repository\CitizenRepositoryInterface;
use Core\Domain\shared\Exception\NotFoundException;
use Core\Domain\shared\ValueObject\Uuid;
use Core\UseCase\Citizen\Update\UpdateCitizenInputDTO;
use Core\UseCase\Citizen\Update\UpdateCitizenOutputDTO;
use Core\UseCase\Citizen\Update\UpdateCitizenUseCase;
use Ramsey\Uuid\Uuid as RamseyUuid;

it('should be throw an exception if cannot find a citizen for update', function () {
    $citizenRepository = Mockery::mock(
        stdClass::class, CitizenRepositoryInterface::class
    );
    $citizenRepository->shouldReceive('findByPk')->andReturn(null);
    $updateCitizenInputDTO = Mockery::mock(UpdateCitizenInputDTO::class, [
        'citizenId', 'Citizen name updated', 'citizen.updated@email.com',
    ]);

    $updateCitizenUseCase = new UpdateCitizenUseCase($citizenRepository);
    $updateCitizenUseCase->execute($updateCitizenInputDTO);
})->throws(NotFoundException::class, 'Citizen with id: citizenId not found');

test('should be able to update a citizen', function () {
    $expectedCitizenId = RamseyUuid::uuid4()->toString();
    $expectedCitizen = Mockery::mock(CitizenEntity::class, [
        'Citizen name', 'citizen@email.com', new Uuid($expectedCitizenId),
    ]);
    $expectedCitizen->shouldReceive('update')->once();
    $expectedCitizen->shouldReceive('getId')->andReturn($expectedCitizenId);
    $citizenRepository = Mockery::mock(
        stdClass::class, CitizenRepositoryInterface::class
    );
    $citizenRepository->shouldReceive('update')->once()
        ->andReturn($expectedCitizen);
    $citizenRepository->shouldReceive('findByPk')->once()
        ->with($expectedCitizenId)
        ->andReturn($expectedCitizen);
    $updateCitizenInputDTO = Mockery::mock(UpdateCitizenInputDTO::class, [
        $expectedCitizenId, 'Citizen name updated', 'email.updated@citizen.com',
    ]);

    $updateCitizenUseCase = new UpdateCitizenUseCase($citizenRepository);
    $actualCitizen = $updateCitizenUseCase->execute($updateCitizenInputDTO);

    expect($actualCitizen)->toBeInstanceOf(UpdateCitizenOutputDTO::class);
});
