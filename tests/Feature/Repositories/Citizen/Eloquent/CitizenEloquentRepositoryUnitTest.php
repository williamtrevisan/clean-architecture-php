<?php

use App\Models\Citizen as CitizenModel;
use App\Repositories\Citizen\Eloquent\CitizenEloquentRepository;
use Core\Domain\Citizen\Entity\Citizen as CitizenEntity;

beforeEach(function () {
    $citizenModel = new CitizenModel();
    $this->citizenRepository = new CitizenEloquentRepository($citizenModel);
});

test('should be able to create a new citizen', function () {
    $expectedCitizen = new CitizenEntity(
        name: 'Citizen name', email: 'citizen@email.com'
    );

    $actualCitizen = $this->citizenRepository->create($expectedCitizen);

    expect($actualCitizen)->toMatchObject([
        'id' => $expectedCitizen->getId(),
        'name' => $expectedCitizen->name,
        'email' => $expectedCitizen->email,
    ]);
});

test('should be return null if cannot find a citizen by primary key', function () {
    $actualCitizen = $this->citizenRepository->findByPk('citizenId');

    expect($actualCitizen)->toBeNull();
});

test('should be able to find a citizen by primary key', function () {
    $expectedCitizen = CitizenModel::factory()->create();

    $actualCitizen = $this->citizenRepository->findByPk($expectedCitizen->id);

    expect($actualCitizen)->toMatchObject([
        'id' => $expectedCitizen->id,
        'name' => $expectedCitizen->name,
        'email' => $expectedCitizen->email,
    ]);
});

test('should be able to find all citizens created', function () {
    $expectedCitizens = CitizenModel::factory(3)->create();

    $actualCitizens = $this->citizenRepository->findAll();

    expect($actualCitizens)->toBeArray()
        ->toHaveCount(3);
});
