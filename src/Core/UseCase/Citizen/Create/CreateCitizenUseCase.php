<?php

namespace Core\UseCase\Citizen\Create;

use Core\Domain\Citizen\Entity\Citizen;
use Core\Domain\Citizen\Repository\CitizenRepositoryInterface;

class CreateCitizenUseCase
{
    public function __construct(private readonly CitizenRepositoryInterface $citizenRepository)
    {
    }

    public function execute(CreateCitizenInputDTO $input): CreateCitizenOutputDTO
    {
        $citizen = new Citizen(name: $input->name, email: $input->email);

        $persistCitizen = $this->citizenRepository->create($citizen);

        return new CreateCitizenOutputDTO(
            id: $persistCitizen->getId(),
            name: $persistCitizen->name,
            email: $persistCitizen->email,
        );
    }
}
