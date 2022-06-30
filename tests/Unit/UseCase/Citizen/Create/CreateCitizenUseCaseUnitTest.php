<?php

use Core\Domain\Citizen\Entity\Citizen;
use Core\Domain\Citizen\Repository\CitizenRepositoryInterface;
use Core\Domain\shared\ValueObject\Uuid;
use Core\UseCase\Citizen\Create\CreateCitizenInputDTO;
use Core\UseCase\Citizen\Create\CreateCitizenOutputDTO;
use Core\UseCase\Citizen\Create\CreateCitizenUseCase;
use Ramsey\Uuid\Uuid as RamseyUuid;

beforeAll(function () {
    Mockery::close();
});

test('should be able to create a new citizen', function () {
    $expectedCitizenId = RamseyUuid::uuid4()->toString();
    $expectedCitizen = Mockery::mock(
        Citizen::class,
        ['Citizen name', 'citizen@email.com', new Uuid($expectedCitizenId)]
    );
    $expectedCitizen->shouldReceive('getId')->andReturn($expectedCitizenId);
    $citizenRepository = Mockery::mock(
        stdClass::class, CitizenRepositoryInterface::class
    );
    $citizenRepository->shouldReceive('create')->andReturn($expectedCitizen);
    $createCitizenInputDTO = Mockery::mock(
        CreateCitizenInputDTO::class, ['Citizen name', 'citizen@email.com']
    );

    $createCitizenUseCase = new CreateCitizenUseCase($citizenRepository);
    $persistCitizen = $createCitizenUseCase->execute($createCitizenInputDTO);

    expect($persistCitizen)
        ->toBeInstanceOf(CreateCitizenOutputDTO::class)
        ->toMatchObject([
            'id' => $expectedCitizen->id,
            'name' => $expectedCitizen->name,
            'email' => $expectedCitizen->email,
        ]);
});
