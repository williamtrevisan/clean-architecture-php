<?php

use App\Models\Citizen as CitizenModel;
use App\Repositories\Citizen\Eloquent\CitizenEloquentRepository;
use Core\UseCase\Citizen\Create\CreateCitizenInputDTO;
use Core\UseCase\Citizen\Create\CreateCitizenUseCase;

test('should be able to create a new citizen', function () {
    $citizenModel = new CitizenModel();
    $citizenRepository = new CitizenEloquentRepository($citizenModel);
    $createCitizenInputDTO = new CreateCitizenInputDTO(
        name: 'Citizen name',
        email: 'citizen@email.com'
    );

    $createCitizenUseCase = new CreateCitizenUseCase($citizenRepository);
    $persistCitizen = $createCitizenUseCase->execute($createCitizenInputDTO);

    $this->assertDatabaseHas('citizens', [
        'name' => 'Citizen name',
        'email' => 'citizen@email.com',
    ]);
    expect($persistCitizen->id)->not->toBeEmpty()
        ->and($persistCitizen)->toMatchObject([
            'name' => 'Citizen name',
            'email' => 'citizen@email.com',
        ]);
});
