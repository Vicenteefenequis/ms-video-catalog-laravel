<?php

namespace App\Repositories\Eloquent;

use Core\Domain\Entity\Genre;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Domain\Repository\PaginationInterface;
use App\Models\Genre as Model;
use Core\Domain\ValueObject\Uuid;
use DateTime;

class GenreEloquentRepository implements GenreRepositoryInterface
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function insert(Genre $genre): Genre
    {
        $genreDb = $this->model->create([
            'id' => $genre->id(),
            'name' => $genre->name,
            'is_active' => $genre->isActive,
            'created_at' => $genre->createdAt()
        ]);


        if (count($genre->categoriesId) > 0) {
            $genreDb->categories()->sync($genre->categoriesId);
        }

        return $this->toGenre($genreDb);
    }

    public function findById(string $id): Genre
    {
        if (!$genreDb = $this->model->find($id)) {
            throw new NotFoundException("Genre $id not found");
        }

        return $this->toGenre($genreDb);
    }

    public function findAll(string $filter = '', $order = 'DESC'): array
    {
        // TODO: Implement findAll() method.
    }

    public function paginate(string $filter = '', $order = 'DESC', int $page = 1, int $totalPage = 15): PaginationInterface
    {
        // TODO: Implement paginate() method.
    }

    public function update(Genre $genre): Genre
    {
        // TODO: Implement update() method.
    }

    public function delete(string $id): bool
    {
        // TODO: Implement delete() method.
    }


    private function toGenre(Model $object): Genre
    {
        $entity = new Genre(
            name: $object->name,
            id: new Uuid($object->id),
            createdAt: new DateTime($object->created_at)
        );

        ($object->is_active) ? $entity->activate() : $entity->deactivate();

        return $entity;
    }
}
