<?php

use App\Models\Citizen as CitizenModel;
use App\Repositories\Citizen\Eloquent\CitizenEloquentRepository;
use Core\Domain\shared\Exception\NotFoundException;
use Core\UseCase\Citizen\Update\UpdateCitizenInputDTO;
use Core\UseCase\Citizen\Update\UpdateCitizenUseCase;

beforeEach(function () {
    $citizenModel = new CitizenModel();
    $citizenRepository = new CitizenEloquentRepository($citizenModel);
    $this->updateCitizenUseCase = new UpdateCitizenUseCase($citizenRepository);
});

it('should be throw an expection if cannot find a citizen for update', function () {
    $updateCitizenInputDTO = new UpdateCitizenInputDTO(id: 'citizenId');

    $this->updateCitizenUseCase->execute($updateCitizenInputDTO);
})->throws(NotFoundException::class, 'Citizen with id: citizenId not found');

test('should be able to update a citizen', function (string $name = '', string $email = '') {
    $expectedCitizen = CitizenModel::factory()->create();
    $updateCitizenInputDTO = new UpdateCitizenInputDTO(
        id: $expectedCitizen->id, name: $name, email: $email
    );

    $actualCitizen = $this->updateCitizenUseCase->execute($updateCitizenInputDTO);

    expect($actualCitizen)->toMatchObject([
        'id' => $expectedCitizen->id,
        'name' => $name ?: $expectedCitizen->name,
        'email' => $email ?: $expectedCitizen->email,
    ]);
})->with([
    'sending only name' => ['name' => 'Citizen name updated', 'email' => ''],
    'sending only email' => ['name' => '', 'email' => 'email.updated@citizen.com'],
    'sending all data' => ['name' => 'Citizen name updated', 'email' => 'email.updated@citizen.com'],
]);
