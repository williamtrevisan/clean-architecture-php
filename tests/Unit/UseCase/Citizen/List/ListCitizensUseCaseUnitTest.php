<?php

use Core\Domain\Citizen\Entity\Citizen as CitizenEntity;
use Core\Domain\Citizen\Repository\CitizenRepositoryInterface;
use Core\UseCase\Citizen\List\ListCitizensOutputDTO;
use Core\UseCase\Citizen\List\ListCitizensUseCase;

test('should be able find all citizens and get empty result', function () {
    $citizenRepository = Mockery::mock(stdClass::class, CitizenRepositoryInterface::class);
    $citizenRepository->shouldReceive('findAll')->andReturn([]);

    $listCitizensUseCase = new ListCitizensUseCase($citizenRepository);
    $actualCitizens = $listCitizensUseCase->execute();

    expect($actualCitizens)->toBeInstanceOf(ListCitizensOutputDTO::class)
        ->and($actualCitizens->items)
            ->toBeArray()
            ->toBeEmpty();
});

test('should be able find all citizens created', function () {
    $citizen1 = new CitizenEntity(name: 'Citizen name 1', email: 'citizen@email.com');
    $citizen2 = new CitizenEntity(name: 'Citizen name 2', email: 'citizen@email.com');
    $citizenRepository = Mockery::mock(
        stdClass::class, CitizenRepositoryInterface::class
    );
    $citizenRepository->shouldReceive('findAll')->andReturn([$citizen1, $citizen2]);

    $listCitizensUseCase = new ListCitizensUseCase($citizenRepository);
    $actualCitizens = $listCitizensUseCase->execute();

    expect($actualCitizens)->toBeInstanceOf(ListCitizensOutputDTO::class)
        ->and($actualCitizens->items)
            ->toBeArray()
            ->toHaveCount(2);
});
