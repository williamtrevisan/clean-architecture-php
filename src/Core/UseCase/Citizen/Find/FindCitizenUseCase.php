<?php

namespace Core\UseCase\Citizen\Find;

use Core\Domain\Citizen\Repository\CitizenRepositoryInterface;
use Core\Domain\shared\Exception\NotFoundException;

class FindCitizenUseCase
{
    public function __construct(protected readonly CitizenRepositoryInterface $citizenRepository)
    {
    }

    public function execute(FindCitizenInputDTO $input): FindCitizenOutputDTO
    {
        $citizen = $this->citizenRepository->findByPk(id: $input->id);
        if (! $citizen) {
            throw new NotFoundException("Citizen with id: $input->id not found");
        }

        return new FindCitizenOutputDTO(
            id: $citizen->id,
            name: $citizen->name,
            email: $citizen->email,
        );
    }
}
