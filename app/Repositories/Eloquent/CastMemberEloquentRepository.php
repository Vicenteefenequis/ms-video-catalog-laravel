<?php


namespace App\Repositories\Eloquent;


use App\Models\CastMember as Model;
use App\Repositories\Presenters\PaginationPresenter;
use Core\Domain\Entity\CastMember as Entity;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Exception\NotFoundException;
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

    /**
     * @throws NotFoundException
     */
    public function findById(string $id): Entity
    {

        if (!$castMember = $this->model->find($id)) {
            throw new NotFoundException("CastMember $id not found");
        }

        return $this->toEntity($castMember);

    }

    public function findAll(string $filter = '', $order = 'DESC'): array
    {
        $castMembers = $this->model
            ->where(function ($query) use ($filter) {
                if ($filter) {
                    $query->where('name', 'LIKE', "%$filter%");
                }
            })
            ->orderBy('name', $order)
            ->get();

        return $castMembers->toArray();
    }

    public function paginate(string $filter = '', $order = 'DESC', int $page = 1, int $totalPage = 15): PaginationInterface
    {
       $query = $this->model;
       if($filter) {
           $query->where('name','LIKE',"%$filter%");
       }
       $query->orderBy('name',$order);
       $dbData = $query->paginate($totalPage);

       return new PaginationPresenter($dbData);
    }

    public function update(Entity $castMember): Entity
    {
        if(!$castDb = $this->model->find($castMember->id())) {
            throw new NotFoundException("CastMember $$castMember->id not found");
        }

        $castDb->update([
            "name" => $castMember->name,
        ]);

        $castDb->refresh();

        return $this->toEntity($castDb);
    }

    /**
     * @throws NotFoundException
     */
    public function delete(string $id): bool
    {
        if(!$genreDb = $this->model->find($id)) {
            throw new NotFoundException("CastMember $id not found");
        }

       return $genreDb->delete();
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
