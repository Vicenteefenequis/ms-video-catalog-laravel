<?php

namespace App\Repositories\Eloquent;

use App\Models\Category as Model;
use App\Repositories\Presenters\PaginationPresenter;
use Core\Domain\Entity\Category as CategoryEntity;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\PaginationInterface;


class CategoryEloquentRepository implements CategoryRepositoryInterface
{

    protected $model;

    public function __construct(Model $category)
    {
        $this->model = $category;
    }

    public function insert(CategoryEntity $category): CategoryEntity
    {
        $category = $this->model->create([
            'id' => $category->id(),
            'name' => $category->name,
            'description' => $category->description,
            'is_active' => $category->isActive,
            'created_at' => $category->createdAt()
        ]);

        return $this->toCategory($category);
    }

    public function findById(string $id): CategoryEntity
    {
        if(!$category = $this->model->find($id)) {
            throw new NotFoundException();
        }

        return $this->toCategory($category);
    }

    public function findAll(string $filter = '', $order = 'DESC'): array
    {
        return [];
    }

    public function paginate(string $filter = '', $order = 'DESC', int $page = 1, int $totalPage = 15): PaginationInterface
    {
        return new PaginationPresenter();
    }

    public function update(CategoryEntity $category): CategoryEntity
    {
        return new CategoryEntity(
            name: 'any'
        );
    }

    public function delete(string $id): bool
    {
        return true;
    }

    private function toCategory(object $object): CategoryEntity
    {
        return new CategoryEntity(
            id: $object->id,
            name: $object->name
        );
    }
}

