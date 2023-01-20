<?php


namespace Core\UseCase\Category;


use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\DTO\Category\UpdateCategory\{
    UpdateCategoryOutputDto,
    UpdateCategoryInputDto
};

class UpdateCategoryUseCase
{

    protected $repository;

    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }


    public function execute(UpdateCategoryInputDto $input): UpdateCategoryOutputDto
    {
        $category = $this->repository->findById($input->id);

        $category->update(
            name: $input->name,
            description: $input->description ?? $category->description,
        );

        $response = $this->repository->update($category);

        return new UpdateCategoryOutputDto(
            id: $response->id,
            name: $response->name,
            description: $response->description,
            is_active: $response->isActive,
            created_at: $category->createdAt(),
        );

    }
}