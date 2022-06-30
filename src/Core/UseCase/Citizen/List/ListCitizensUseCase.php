<?php

namespace Core\UseCase\Citizen\List;

use Core\Domain\Citizen\Repository\CitizenRepositoryInterface;

class ListCitizensUseCase
{
    public function __construct(protected readonly CitizenRepositoryInterface $citizenRepository)
    {
    }

    public function execute(): ListCitizensOutputDTO
    {
        $citizens = $this->citizenRepository->findAll();

        return new ListCitizensOutputDTO(items: $citizens);
    }
}
