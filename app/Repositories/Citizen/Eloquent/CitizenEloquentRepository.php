<?php

namespace App\Repositories\Citizen\Eloquent;

use App\Models\Citizen as CitizenModel;
use Core\Domain\Citizen\Entity\Citizen;
use Core\Domain\Citizen\Repository\CitizenRepositoryInterface;
use Core\Domain\shared\Entity\Entity;
use Core\Domain\shared\ValueObject\Uuid;

class CitizenEloquentRepository implements CitizenRepositoryInterface
{
    public function __construct(protected readonly CitizenModel $citizenModel)
    {
    }

    public function create(Entity $entity): Entity
    {
        $citizen = $this->citizenModel->create([
            'id' => $entity->getId(),
            'name' => $entity->name,
            'email' => $entity->email,
        ]);

        return $this->toDomainEntity($citizen);
    }

    public function findByPk(string $id): ?Entity
    {
        $citizen = $this->citizenModel->find($id);
        if (! $citizen) {
            return null;
        }

        return $this->toDomainEntity($citizen);
    }

    public function findAll(): array
    {
        $citizens = $this->citizenModel->all();

        return $citizens
            ->map(fn ($citizen) => $this->toDomainEntity($citizen))
            ->toArray();
    }

    public function update(Entity $entity): Entity
    {
        $citizen = $this->citizenModel->find($entity->getId());

        $citizen->update(['name' => $entity->name, 'email' => $entity->email]);
        $citizen->refresh();

        return $this->toDomainEntity($citizen);
    }

    private function toDomainEntity(CitizenModel $citizenModel): Entity
    {
        return new Citizen(
            name: $citizenModel->name,
            email: $citizenModel->email,
            id: new Uuid($citizenModel->id),
        );
    }
}
