<?php

use App\Models\Citizen as CitizenModel;
use App\Repositories\Citizen\Eloquent\CitizenEloquentRepository;
use Core\Domain\shared\Exception\NotFoundException;
use Core\UseCase\Citizen\Find\FindCitizenInputDTO;
use Core\UseCase\Citizen\Find\FindCitizenUseCase;

beforeEach(function () {
    $citizenModel = new CitizenModel();
    $citizenRepository = new CitizenEloquentRepository($citizenModel);
    $this->findCitizenUseCase = new FindCitizenUseCase($citizenRepository);
});

it('should be throw an exception if cannot find a citizen by primary key', function () {
    $findCitizenInputDTO = new FindCitizenInputDTO(id: 'citizenId');

    $this->findCitizenUseCase->execute($findCitizenInputDTO);
})->throws(NotFoundException::class, 'Citizen with id: citizenId not found');

test('should be able to find a citizen by primary key', function () {
    $expectedCitizen = CitizenModel::factory()->create();
    $findCitizenInputDTO = new FindCitizenInputDTO(id: $expectedCitizen->id);

    $actualCitizen = $this->findCitizenUseCase->execute($findCitizenInputDTO);

    expect($actualCitizen)->toMatchObject([
        'id' => $actualCitizen->id,
        'name' => $actualCitizen->name,
        'email' => $actualCitizen->email,
    ]);
});
