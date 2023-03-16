<?php


namespace App\Repositories\Eloquent;


use App\Models\CastMember as Model;
use Core\Domain\Entity\CastMember as Entity;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\Domain\Repository\PaginationInterface;
use Core\Domain\ValueObject\Uuid;

class CastMemberEloquentRepository implements CastMemberRepositoryInterface
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function insert(Entity $castMember): Entity
    {
        $dataDb = $this->model->create([
            'id' => $castMember->id(),
            'name' => $castMember->name,
            'type' => $castMember->type->value,
            'created_at' => $castMember->createdAt()
        ]);

        return $this->toEntity($dataDb);

    }

    public function findById(string $id): Entity
    {
        // TODO: Implement findById() method.
    }

    public function findAll(string $filter = '', $order = 'DESC'): array
    {
        // TODO: Implement findAll() method.
    }

    public function paginate(string $filter = '', $order = 'DESC', int $page = 1, int $totalPage = 15): PaginationInterface
    {
        // TODO: Implement paginate() method.
    }

    public function update(Entity $castMember): Entity
    {
        // TODO: Implement update() method.
    }

    public function delete(string $id): bool
    {
        // TODO: Implement delete() method.
    }

    private function toEntity(Model $model): Entity
    {
        return new Entity(
            name: $model->name,
            type: CastMemberType::from($model->type),
            id: new Uuid($model->id),
            createdAt: $model->created_at
        );
    }
}
