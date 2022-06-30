<?php

namespace Core\UseCase\Citizen\Update;

use Core\Domain\Citizen\Repository\CitizenRepositoryInterface;
use Core\Domain\shared\Exception\NotFoundException;

class UpdateCitizenUseCase
{
    public function __construct(protected readonly CitizenRepositoryInterface $citizenRepository)
    {
    }

    public function execute(UpdateCitizenInputDTO $input): UpdateCitizenOutputDTO
    {
        $citizen = $this->citizenRepository->findByPk($input->id);
        if (! $citizen) {
            throw new NotFoundException("Citizen with id: $input->id not found");
        }

        $citizen->update(name: $input->name, email: $input->email);

        $persistCitizen = $this->citizenRepository->update($citizen);

        return new UpdateCitizenOutputDTO(
            id: $persistCitizen->getId(),
            name: $persistCitizen->name,
            email: $persistCitizen->email,
        );
    }
}
