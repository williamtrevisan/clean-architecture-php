<?php

use Core\Domain\Citizen\Entity\Citizen;
use Core\Domain\Citizen\Repository\CitizenRepositoryInterface;
use Core\Domain\shared\Exception\NotFoundException;
use Core\Domain\shared\ValueObject\Uuid;
use Core\UseCase\Citizen\Find\FindCitizenInputDTO;
use Core\UseCase\Citizen\Find\FindCitizenOutputDTO;
use Core\UseCase\Citizen\Find\FindCitizenUseCase;
use Ramsey\Uuid\Uuid as RamseyUuid;

beforeAll(function () {
    Mockery::close();
});

it('should be throw an exception if cannot find a citizen by primary key', function () {
    $citizenRepository = Mockery::mock(
        stdClass::class, CitizenRepositoryInterface::class
    );
    $citizenRepository->shouldReceive('findByPk')->andReturn(null);
    $findCitizenInputDTO = Mockery::mock(FindCitizenInputDTO::class, ['fakeId']);

    $findCitizenUseCase = new FindCitizenUseCase($citizenRepository);
    $findCitizenUseCase->execute($findCitizenInputDTO);
})->throws(NotFoundException::class, 'Citizen with id: fakeId not found');

test('should be able to find a citizen by primary key', function () {
    $expectedCitizenId = RamseyUuid::uuid4()->toString();
    $expectedCitizen = Mockery::mock(
        Citizen::class,
        ['Citizen name', 'citizen@email.com', new Uuid($expectedCitizenId)]
    );
    $expectedCitizen->shouldReceive('getId')->andReturn($expectedCitizenId);
    $citizenRepository = Mockery::mock(
        stdClass::class, CitizenRepositoryInterface::class
    );
    $citizenRepository->shouldReceive('findByPk')->andReturn($expectedCitizen);
    $findCitizenInputDTO = Mockery::mock(
        FindCitizenInputDTO::class, [$expectedCitizenId]
    );

    $findCitizenUseCase = new FindCitizenUseCase($citizenRepository);
    $persistCitizen = $findCitizenUseCase->execute($findCitizenInputDTO);

    expect($persistCitizen)
        ->toBeInstanceOf(FindCitizenOutputDTO::class)
        ->toMatchObject([
            'id' => $expectedCitizen->id,
            'name' => $expectedCitizen->name,
            'email' => $expectedCitizen->email,
        ]);
});
