<?php

use App\Models\Citizen as CitizenModel;
use App\Repositories\Citizen\Eloquent\CitizenEloquentRepository;
use Core\UseCase\Citizen\List\ListCitizensUseCase;

beforeEach(function () {
    $citizenModel = new CitizenModel();
    $citizenRepository = new CitizenEloquentRepository($citizenModel);
    $this->listCitizensUseCase = new ListCitizensUseCase($citizenRepository);
});

test('should be able find all citizens and get empty result', function () {
    $actualCitizens = $this->listCitizensUseCase->execute();

    expect($actualCitizens->items)
        ->toBeArray()
        ->toBeEmpty();
});

test('should be able find all citizens created', function () {
    $expectedCitizens = CitizenModel::factory(2)->create();

    $actualCitizens = $this->listCitizensUseCase->execute();

    expect($actualCitizens->items)
        ->toBeArray()
        ->toHaveCount(2);
});
